<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\ReviewInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

Carbon::setLocale('vi');

class ReviewController extends Controller
{
    private function getOrderDetail($orderId, $orderDetailId)
    {
        return OrderDetail::where('order_id', $orderId)
            ->where('id', $orderDetailId)
            ->with('productDetail.color', 'productDetail.size')
            ->first();
    }

    private function formDataBack($review)
    {
        $orderDetail = $this->getOrderDetail($review->order_id, $review->order_detail_id);

        return [
            'user_name' => $review->user->name,
            'user_role' => $review->user->role,
            'product_name' => $review->orderDetail->productDetail->product->name,
            'content' => $review->content,
            'images' => $review->images->map(fn($img) => asset('storage/' . $img->image_path)),
            'reply' => $review->reply,
            'number_of_likes' => $review->helpful_count,
            'created_at' => $review->created_at->diffForHumans(),
            'is_anonymous' => $review->is_anonymous,
            'rating' => $review->rating,
            'size' => $orderDetail->productDetail->size->name ?? 'N/A',
            'color' => $orderDetail->productDetail->color->name ?? 'N/A',
        ];
    }

    public function index($productId)
    {
        try {
            $allReviews = Review::whereHas('orderDetail.productDetail', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
                ->with([
                    'user:id,name,role',
                    'orderDetail.productDetail.color',
                    'orderDetail.productDetail.size'
                ])
                ->select('id', 'user_id', 'order_detail_id', 'content', 'reply', 'helpful_count', 'created_at', 'is_anonymous', 'rating', 'order_id')
                ->get();

            return response()->json([
                'reviews' => $allReviews->map(fn($r) => $this->formDataBack($r)),
                'total_reviews' => $allReviews->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể truy vấn đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function myReviews()
    {
        try {
            $user = Auth::user();

            $reviews = Review::where('user_id', $user->id)
                ->with('orderDetail.productDetail.color', 'orderDetail.productDetail.size')
                ->select('id', 'order_detail_id', 'order_id', 'rating', 'content', 'created_at', 'is_anonymous', 'is_edited', 'helpful_count', 'is_reported')
                ->orderByDesc('created_at')
                ->get();

            $data = $reviews->map(function ($r) {
                $od = $this->getOrderDetail($r->order_id, $r->order_detail_id);
                return [
                    'product_name' => $od->productDetail->product->name,
                    'product_id' => $od->productDetail->product->id,
                    'order_id' => $r->order_id,
                    'rating' => $r->rating,
                    'content' => $r->content,
                    'images' => $r->images->map(fn($img) => asset('storage/' . $img->image_path)),
                    'created_at' => $r->created_at->diffForHumans(),
                    'is_anonymous' => $r->is_anonymous,
                    'is_edited' => $r->is_edited,
                    'helpful_count' => $r->helpful_count,
                    'is_reported' => $r->is_reported,
                    'size' => $od->productDetail->size->name ?? 'N/A',
                    'color' => $od->productDetail->color->name ?? 'N/A',
                ];
            });

            return response()->json(['my_reviews' => $data, 'total_reviews' => $reviews->count()]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể lấy danh sách đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($reviewId)
    {
        try {
            $user = Auth::user();

            $review = Review::with('orderDetail.productDetail.product', 'orderDetail.productDetail.color', 'orderDetail.productDetail.size')->find($reviewId);

            if (!$review || ($user->role === 'user' && $review->user_id !== $user->id)) {
                return response()->json(['message' => !$review ? 'Không tồn tại' : 'Không có quyền truy cập'], 403);
            }

            $productDetail = $review->orderDetail->productDetail;
            $product = $productDetail->product;

            return response()->json([
                'product' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'size' => $productDetail->size->name ?? 'N/A',
                    'color' => $productDetail->color->name ?? 'N/A',
                    'product_price' => $review->orderDetail->price,
                    'quantity' => $review->orderDetail->quantity,
                    'total_price' => $review->orderDetail->total_price,
                ],
                'review' => $this->formDataBack($review),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể lấy chi tiết đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function getOrderReview($orderId)
    {
        try {
            $reviews = Review::with([
                'order',
                'orderDetail.productDetail.product',
                'orderDetail.productDetail.color',
                'orderDetail.productDetail.size',
            ])->where('order_id', $orderId)->get();

            if ($reviews->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đánh giá nào cho đơn hàng này.'
                ], 404);
            }

            $summary = $reviews->map(function ($review) {
                $detail = $review->orderDetail;
                $productDetail = $detail->productDetail;

                return [
                    'product' => [
                        'product_name' => $productDetail->product->name ?? 'N/A',
                        'variant' => [
                            'size' => $productDetail->size->name ?? 'N/A',
                            'color' => $productDetail->color->name ?? 'N/A',
                        ],
                        'quantity' => $detail->quantity,
                        'product_price' => $detail->price,
                        'total_price' => $detail->total_price,
                    ],
                    'is_reviewed' => true,
                    'review' => [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'service' => $review->service,
                        'packaging' => $review->packaging,
                        'customer_service' => $review->customer_service,
                        'content' => $review->content,
                        'images' => $review->images->map(fn($img) => asset('storage/' . $img->image_path)),
                        'is_anonymous' => $review->is_anonymous,
                        'liked_count' => $review->likes()->count(),
                        'reported_count' => $review->reports()->count(),
                        'reply' => $review->reply,
                    ]
                ];
            });

            $totalReviews = $reviews->count();
            $orderTotalPrice = $reviews->first()->order->total_price ?? 0;

            return response()->json([
                'success' => true,
                'data' => $summary,
                'total_reviews' => $totalReviews,
                'all_price' => $orderTotalPrice,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeReviewAfterDelivery(Request $request, $orderId)
    {
        try {
            $user = Auth::user();
            $userId = $user->id;

            $order = Order::where('id', $orderId)->where('user_id', $userId)->first();
            if (!$order) {
                return response()->json(['message' => 'Đơn hàng không tồn tại hoặc không phải của bạn'], 404);
            }

            if (!Order::where('id', $orderId)->where('status', 'delivered')->exists()) {
                return response()->json(['message' => 'Bạn chỉ có thể đánh giá sau khi đơn hàng hoàn tất'], 403);
            }

            $validator = Validator::make($request->all(), [
                'reviews' => 'required|array',
                'reviews.*.order_detail_id' => 'required|integer|exists:order_details,id',
                'reviews.*.rating' => 'required|integer|min:1|max:5',
                'reviews.*.content' => 'nullable|string|max:500',
                'reviews.*.service' => 'nullable|integer|min:1|max:5',
                'reviews.*.packaging' => 'nullable|integer|min:1|max:5',
                'reviews.*.shipping' => 'nullable|integer|min:1|max:5',
                'reviews.*.customer_service' => 'nullable|integer|min:1|max:5',
                'reviews.*.is_anonymous' => 'nullable|boolean',
                'reviews.*.images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => 'Dữ liệu đầu vào không hợp lệ', 'details' => $validator->errors()], 400);
            }

            $responseReviews = [];

            foreach ($request->reviews as $reviewData) {
                $orderDetailId = $reviewData['order_detail_id'];

                $orderDetail = OrderDetail::where('order_id', $orderId)->where('id', $orderDetailId)->first();
                if (!$orderDetail) {
                    continue;
                }

                if (Review::where('user_id', $userId)->where('order_detail_id', $orderDetailId)->where('order_id', $orderId)->exists()) {
                    continue;
                }

                $review = Review::create([
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'order_detail_id' => $orderDetailId,
                    'rating' => $reviewData['rating'],
                    'content' => $reviewData['content'] ?? '',
                    'service' => $reviewData['service'] ?? 1,
                    'packaging' => $reviewData['packaging'] ?? 1,
                    'shipping' => $reviewData['shipping'] ?? 1,
                    'customer_service' => $reviewData['customer_service'] ?? 1,
                    'is_anonymous' => $reviewData['is_anonymous'] ?? false,
                ]);

                // Xử lý lưu ảnh
                if (isset($reviewData['images']) && is_array($reviewData['images'])) {
                    foreach ($reviewData['images'] as $imageFile) {
                        if ($imageFile instanceof \Illuminate\Http\UploadedFile) {
                            $path = $imageFile->store('review_images', 'public');

                            ReviewImage::create([
                                'review_id' => $review->id,
                                'image_path' => $path,
                            ]);
                        }
                    }
                }

                $review->load('user:id,name,role');
                $productDetail = $orderDetail->productDetail;

                $responseReviews[] = [
                    'user_name' => $review->user->name,
                    'user_role' => $review->user->role,
                    'content' => $review->content,
                    'images' => $review->images->map(fn($img) => asset('storage/' . $img->image_path)),
                    'rating' => $review->rating,
                    'number_of_likes' => $review->helpful_count,
                    'created_at' => $review->created_at->diffForHumans(),
                    'is_anonymous' => $review->is_anonymous,
                    'size' => $productDetail ? $productDetail->size->name : 'N/A',
                    'color' => $productDetail ? $productDetail->color->name : 'N/A',
                ];
            }

            return response()->json([
                'message' => 'Các đánh giá đã được gửi thành công!',
                'reviews' => $responseReviews,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể gửi đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function reply(Request $request, $reviewId)
    {
        try {
            if (Auth::user()->role == 'user') {
                return response()->json(['message' => 'Bạn không có quyền phản hồi đánh giá'], 403);
            }

            $review = Review::find($reviewId);
            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            if ($review->is_replied) {
                return response()->json(['message' => 'Đánh giá này đã được phản hồi trước đó'], 400);
            }

            $validator = Validator::make($request->all(), [
                'reply' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => 'Dữ liệu phản hồi không hợp lệ', 'details' => $validator->errors()], 400);
            }

            $review->update([
                'reply' => $request->reply,
                'is_replied' => true,
            ]);

            return response()->json(['message' => 'Phản hồi đánh giá thành công', 'review' => $review]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể phản hồi đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $reviewId)
    {
        try {
            $user = Auth::user();
            $review = Review::find($reviewId);

            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            if ($review->user_id !== $user->id) {
                return response()->json(['message' => 'Bạn không có quyền chỉnh sửa đánh giá này'], 403);
            }

            if ($review->is_edited) {
                return response()->json(['message' => 'Bạn chỉ có thể chỉnh sửa đánh giá một lần'], 400);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'sometimes|integer|min:1|max:5',
                'content' => 'required|string|max:1000',
                'service' => 'sometimes|integer|min:1|max:5',
                'packaging' => 'sometimes|integer|min:1|max:5',
                'shipping' => 'sometimes|integer|min:1|max:5',
                'customer_service' => 'sometimes|integer|min:1|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => 'Dữ liệu chỉnh sửa không hợp lệ', 'details' => $validator->errors()], 400);
            }

            $updatedData = [
                'rating' => $request->input('rating', $review->rating),
                'content' => $request->input('content'),
                'service' => $request->input('service', $review->service),
                'packaging' => $request->input('packaging', $review->packaging),
                'shipping' => $request->input('shipping', $review->shipping),
                'customer_service' => $request->input('customer_service', $review->customer_service),
                'is_edited' => true,
            ];

            $review->update($updatedData);

            // Xử lý xóa ảnh theo yêu cầu
            if ($request->has('deleted_image_ids')) {
                $imageIdsToDelete = $request->input('deleted_image_ids');
                foreach ($imageIdsToDelete as $imageId) {
                    $image = ReviewImage::where('review_id', $review->id)->where('id', $imageId)->first();
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('review_images', 'public');
                    ReviewImage::create([
                        'review_id' => $review->id,
                        'image_path' => $path,
                    ]);
                }
            }

            return response()->json(['message' => 'Đánh giá đã được cập nhật thành công', 'review' => $review]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể cập nhật đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function like($reviewId)
    {
        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
        }

        $userId = Auth::id();

        if ($review->likes()->where('user_id', $userId)->exists()) {
            $review->likes()->where('user_id', $userId)->delete();
            $review->decrement('helpful_count');
            return response()->json(['message' => 'Đã bỏ thích đánh giá'], 200);
        }

        $review->increment('helpful_count');
        $review->save();

        ReviewInteraction::create([
            'review_id' => $reviewId,
            'user_id' => $userId,
            'type' => 1
        ]);

        return response()->json(['message' => 'Đã thích đánh giá']);
    }

    public function report($reviewId)
    {
        $review = Review::find($reviewId);

        if (!$review) {
            return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
        }

        $userId = Auth::id();

        if ($review->user_id === $userId) {
            return response()->json(['message' => 'Bạn không thể báo cáo đánh giá của chính mình'], 403);
        }

        if (Auth::user()->role == 'admin') {
            return response()->json(['message' => 'Admin không thể báo cáo đánh giá'], 403);
        }

        if ($review->reports()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Bạn đã báo cáo đánh giá này rồi'], 400);
        }

        ReviewInteraction::create([
            'review_id' => $reviewId,
            'user_id' => $userId,
            'type' => 2
        ]);

        return response()->json(['message' => 'Đã báo cáo đánh giá thành công']);
    }

    public function toggleAnonymous($reviewId)
    {
        try {
            $userId = Auth::id();

            $review = Review::find($reviewId);

            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            if ($review->user_id !== $userId) {
                return response()->json(['message' => 'Bạn không có quyền thay đổi trạng thái ẩn danh của đánh giá này'], 403);
            }

            $review->is_anonymous = !$review->is_anonymous;
            $review->save();

            return response()->json([
                'message' => $review->is_anonymous ? 'Đánh giá đã được chuyển thành ẩn danh' : 'Đánh giá đã được bỏ ẩn danh',
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể thay đổi trạng thái ẩn danh', 'message' => $e->getMessage()], 500);
        }
    }

    // ẩn đánh giá phía user
    public function toggleHidden($reviewId)
    {
        try {
            $userId = Auth::id();

            $review = Review::find($reviewId);

            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            if ($review->user_id !== $userId) {
                return response()->json(['message' => 'Bạn không có quyền thay đổi trạng thái ẩn/hiện của đánh giá này'], 403);
            }

            $review->is_hidden = !$review->is_hidden;
            $review->save();

            return response()->json([
                'message' => $review->is_hidden ? 'Đánh giá đã được ẩn' : 'Đánh giá đã được hiện lại',
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể thay đổi trạng thái ẩn/hiện', 'message' => $e->getMessage()], 500);
        }
    }
}
