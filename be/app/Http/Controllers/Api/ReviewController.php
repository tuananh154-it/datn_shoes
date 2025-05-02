<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
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
        if (is_null($orderDetailId)) {
            return null;
        }

        return OrderDetail::where('order_id', $orderId)
            ->where('id', $orderDetailId)
            ->with('productDetail.color', 'productDetail.size', 'productDetail.product')
            ->first();
    }

    private function formDataBack($review)
    {
        $orderDetail = $review->orderDetail ?? $this->getOrderDetail($review->order_id, $review->order_detail_id);

        return [
            'user_name' => isset($review->user) && $review->user ? ($review->user->name ?? 'N/A') : 'N/A',
            'user_role' => isset($review->user) && $review->user ? ($review->user->role ?? 'N/A') : 'N/A',
            'product_name' => $orderDetail && isset($orderDetail->productDetail) && $orderDetail->productDetail && isset($orderDetail->productDetail->product) && $orderDetail->productDetail->product ? ($orderDetail->productDetail->product->name ?? 'N/A') : 'N/A',
            'content' => $review->content ?? 'N/A',
            'reply' => $review->reply ?? null,
            'number_of_likes' => $review->helpful_count ?? 0,
            'created_at' => $review->created_at ? $review->created_at->format('Y-m-d H:i:s') : 'N/A',
            'is_anonymous' => $review->is_anonymous ?? false,
            'rating' => $review->rating ?? 0,
            'image' => $review->image ? Storage::url($review->image) : null, // Thêm trường image
            'size' => $orderDetail && isset($orderDetail->productDetail) && $orderDetail->productDetail && isset($orderDetail->productDetail->size) && $orderDetail->productDetail->size ? ($orderDetail->productDetail->size->name ?? 'N/A') : 'N/A',
            'color' => $orderDetail && isset($orderDetail->productDetail) && $orderDetail->productDetail && isset($orderDetail->productDetail->color) && $orderDetail->productDetail->color ? ($orderDetail->productDetail->color->name ?? 'N/A') : 'N/A',
        ];
    }

    public function index($productId)
    {
        try {
            $allReviews = Review::whereHas('orderDetail.productDetail', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
                ->orWhere(function ($query) use ($productId) {
                    $query->where('product_id', $productId)
                          ->whereNull('order_detail_id');
                })
                ->with([
                    'user:id,name,role',
                    'orderDetail.productDetail.product',
                    'orderDetail.productDetail.color',
                    'orderDetail.productDetail.size'
                ])
                ->select('id', 'user_id', 'order_detail_id', 'content', 'reply', 'helpful_count', 'created_at', 'is_anonymous', 'rating', 'order_id', 'product_id', 'image') // Thêm cột image
                ->get();

            return response()->json([
                'reviews' => $allReviews->map(fn($r) => $this->formDataBack($r)),
                'total_reviews' => $allReviews->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Error when fetching reviews for product_id {$productId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể truy vấn đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function myReviews()
    {
        try {
            $user = Auth::user();

            $reviews = Review::where('user_id', $user->id)
                ->with('orderDetail.productDetail.product', 'orderDetail.productDetail.color', 'orderDetail.productDetail.size')
                ->select('id', 'order_detail_id', 'order_id', 'rating', 'content', 'created_at', 'is_anonymous', 'is_edited', 'helpful_count', 'is_reported', 'image') // Thêm cột image
                ->orderByDesc('created_at')
                ->get();

            $data = $reviews->map(function ($r) {
                $od = $r->orderDetail;
                return [
                    'product_name' => $od && isset($od->productDetail) && $od->productDetail && isset($od->productDetail->product) ? ($od->productDetail->product->name ?? 'N/A') : 'N/A',
                    'product_id' => $od && isset($od->productDetail) && $od->productDetail && isset($od->productDetail->product) ? ($od->productDetail->product->id ?? 0) : 0,
                    'order_id' => $r->order_id ?? 0,
                    'order_detail_id' => $r->order_detail_id ?? 0,
                    'rating' => $r->rating ?? 0,
                    'content' => $r->content ?? 'N/A',
                    'image' => $r->image ? Storage::url($r->image) : null, // Thêm trường image
                    'created_at' => $r->created_at ? $r->created_at->format('Y-m-d H:i:s') : 'N/A',
                    'is_anonymous' => $r->is_anonymous ?? false,
                    'is_edited' => $r->is_edited ?? false,
                    'helpful_count' => $r->helpful_count ?? 0,
                    'is_reported' => $r->is_reported ?? false,
                    'size' => $od && isset($od->productDetail) && $od->productDetail && isset($od->productDetail->size) ? ($od->productDetail->size->name ?? 'N/A') : 'N/A',
                    'color' => $od && isset($od->productDetail) && $od->productDetail && isset($od->productDetail->color) ? ($od->productDetail->color->name ?? 'N/A') : 'N/A',
                ];
            });

            return response()->json(['my_reviews' => $data, 'total_reviews' => $reviews->count()]);
        } catch (\Exception $e) {
            \Log::error("Error when fetching my reviews: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
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

            $productDetail = isset($review->orderDetail) && $review->orderDetail ? $review->orderDetail->productDetail : null;
            $product = $productDetail && isset($productDetail->product) ? $productDetail->product : null;

            return response()->json([
                'product' => [
                    'product_id' => $product ? ($product->id ?? 0) : 0,
                    'product_name' => $product ? ($product->name ?? 'N/A') : 'N/A',
                    'product_image' => $product ? ($product->image ?? null) : null,
                    'size' => $productDetail && isset($productDetail->size) ? ($productDetail->size->name ?? 'N/A') : 'N/A',
                    'color' => $productDetail && isset($productDetail->color) ? ($productDetail->color->name ?? 'N/A') : 'N/A',
                    'product_price' => isset($review->orderDetail) && $review->orderDetail ? ($review->orderDetail->price ?? 0) : 0,
                    'quantity' => isset($review->orderDetail) && $review->orderDetail ? ($review->orderDetail->quantity ?? 0) : 0,
                    'total_price' => isset($review->orderDetail) && $review->orderDetail ? ($review->orderDetail->total_price ?? 0) : 0,
                ],
                'review' => $this->formDataBack($review),
            ]);
        } catch (\Exception $e) {
            \Log::error("Error when fetching review {$reviewId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể lấy chi tiết đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    // public function getOrderReview($orderId)
    public function getOrderReview($orderId)
    {
        try {
            $reviews = Review::with([
                'order',
                'orderDetail.productDetail.product',
                'orderDetail.productDetail.color',
                'orderDetail.productDetail.size',
                'likes',
                'reports'
            ])->where('order_id', $orderId)->get();
    
            if ($reviews->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đánh giá nào cho đơn hàng này.'
                ], 404);
            }
    
            $summary = $reviews->map(function ($review) {
                $detail = $review->orderDetail;
                $productDetail = $detail ? $detail->productDetail : null;
    
                // Chuyển ảnh thành base64 nếu có
                $imageBase64 = null;
                if ($review->image) {
                    try {
                        $imagePath = $review->image;
                        $imageData = Storage::disk('public')->get($imagePath);
                        $imageBase64 = 'data:image/jpeg;base64,' . base64_encode($imageData); // Giả sử ảnh là JPEG, điều chỉnh nếu cần
                    } catch (\Exception $e) {
                        \Log::error("Error converting image to base64 for review {$review->id}: " . $e->getMessage());
                    }
                }
    
                return [
                    'product' => [
                        'product_name' => $productDetail && isset($productDetail->product) ? ($productDetail->product->name ?? 'N/A') : 'N/A',
                        'variant' => [
                            'size' => $productDetail && isset($productDetail->size) ? ($productDetail->size->name ?? 'N/A') : 'N/A',
                            'color' => $productDetail && isset($productDetail->color) ? ($productDetail->color->name ?? 'N/A') : 'N/A',
                        ],
                        'quantity' => $detail ? ($detail->quantity ?? 0) : 0,
                        'product_price' => $detail ? ($detail->price ?? 0) : 0,
                        'total_price' => $detail ? ($detail->total_price ?? 0) : 0,
                    ],
                    'is_reviewed' => true,
                    'review' => [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'service' => $review->service,
                        'packaging' => $review->packaging,
                        'customer_service' => $review->customer_service,
                        'content' => $review->content,
                        'image' => $imageBase64, // Trả về ảnh dưới dạng base64
                        'is_anonymous' => $review->is_anonymous,
                        'liked_count' => $review->likes->count(),
                        'reported_count' => $review->reports->count(),
                        'reply' => $review->reply,
                    ]
                ];
            });
    
            $totalReviews = $reviews->count();
            $orderTotalPrice = $reviews->first()->order ? ($reviews->first()->order->total_price ?? 0) : 0;
    
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
            \Log::error("Error when fetching order reviews for order_id {$orderId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
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
    
            \Log::info("Starting storeReviewAfterDelivery for order_id: {$orderId}, user_id: {$userId}");
    
            $order = Order::where('id', $orderId)
                ->where('user_id', $userId)
                ->first();
            if (!$order) {
                \Log::warning("Order not found or does not belong to user. Order_id: {$orderId}, user_id: {$userId}");
                return response()->json(['message' => 'Đơn hàng không tồn tại hoặc không phải của bạn'], 404);
            }
    
            if ($order->status !== 'completed') {
                \Log::warning("Order is not completed. Order_id: {$orderId}, status: {$order->status}");
                return response()->json(['message' => 'Bạn chỉ có thể đánh giá sau khi đơn hàng hoàn tất'], 403);
            }
    
            $validator = Validator::make($request->all(), [
                'reviews' => 'required|array',
                'reviews.*.order_detail_id' => 'required|integer|exists:order_details,id',
                'reviews.*.rating' => 'required|integer|min:1|max:5',
                'reviews.*.content' => 'nullable|string|max:500',
                'reviews.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Validate ảnh
                'reviews.*.service' => 'nullable|integer|min:1|max:5',
                'reviews.*.packaging' => 'nullable|integer|min:1|max:5',
                'reviews.*.shipping' => 'nullable|integer|min:1|max:5',
                'reviews.*.customer_service' => 'nullable|integer|min:1|max:5',
                'reviews.*.is_anonymous' => 'nullable|boolean',
            ]);
    
            if ($validator->fails()) {
                \Log::error("Validation failed for order_id: {$orderId}", ['errors' => $validator->errors()]);
                return response()->json(['errors' => 'Dữ liệu đầu vào không hợp lệ', 'details' => $validator->errors()], 400);
            }
    
            $orderDetailIds = collect($request->reviews)->pluck('order_detail_id')->unique()->toArray();
            \Log::info("Order detail IDs to process: " . json_encode($orderDetailIds));
    
            $orderDetails = OrderDetail::where('order_id', $orderId)
                ->whereIn('id', $orderDetailIds)
                ->with('productDetail.color', 'productDetail.size', 'productDetail.product')
                ->get()
                ->keyBy('id');
    
            $existingReviews = Review::where('user_id', $userId)
                ->where('order_id', $orderId)
                ->whereIn('order_detail_id', $orderDetailIds)
                ->pluck('order_detail_id')
                ->toArray();
            \Log::info("Existing reviews for order_id: {$orderId}, user_id: {$userId}", ['existing_reviews' => $existingReviews]);
    
            $responseReviews = [];
    
            foreach ($request->reviews as $index => $reviewData) {
                $orderDetailId = $reviewData['order_detail_id'];
    
                $orderDetail = $orderDetails[$orderDetailId] ?? null;
                if (!$orderDetail) {
                    \Log::warning("Order detail not found for order_detail_id: {$orderDetailId}");
                    continue;
                }
    
                if (in_array($orderDetailId, $existingReviews)) {
                    \Log::info("Review already exists for order_detail_id: {$orderDetailId}, skipping.");
                    continue;
                }
    
                // Lấy product_id từ orderDetail
                $productId = $orderDetail->productDetail->product->id ?? null;
                if (!$productId) {
                    \Log::error("Product not found for order_detail_id: {$orderDetailId}");
                    return response()->json(['message' => 'Không tìm thấy sản phẩm cho chi tiết đơn hàng này'], 400);
                }
    
                // Xử lý upload ảnh
                $imagePath = null;
                if (isset($reviewData['image']) && $request->hasFile("reviews.{$index}.image")) {
                    \Log::info("Processing image upload for review index: {$index}");
                    $imagePath = $request->file("reviews.{$index}.image")->store('reviews', 'public');
                    \Log::info("Image uploaded successfully. Path: {$imagePath}");
                } else {
                    \Log::info("No image provided for review index: {$index}");
                }
    
                $reviewDataToSave = [
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'order_detail_id' => $orderDetailId,
                    'product_id' => $productId,
                    'rating' => $reviewData['rating'],
                    'content' => $reviewData['content'] ?? '',
                    'image' => $imagePath, // Lưu đường dẫn ảnh
                    'service' => $reviewData['service'] ?? 1,
                    'packaging' => $reviewData['packaging'] ?? 1,
                    'shipping' => $reviewData['shipping'] ?? 1,
                    'customer_service' => $reviewData['customer_service'] ?? 1,
                    'is_anonymous' => $reviewData['is_anonymous'] ?? false,
                ];
    
                \Log::info("Creating review for order_detail_id: {$orderDetailId}", $reviewDataToSave);
    
                $review = Review::create($reviewDataToSave);
    
                \Log::info("Review created successfully for order_detail_id: {$orderDetailId}", ['review_id' => $review->id, 'image' => $review->image]);
    
                $review->load('user:id,name,role');
                $productDetail = $orderDetail->productDetail;
    
                $responseReviews[] = [
                    'user_name' => $review->user ? ($review->user->name ?? 'N/A') : 'N/A',
                    'user_role' => $review->user ? ($review->user->role ?? 'N/A') : 'N/A',
                    'content' => $review->content,
                    'rating' => $review->rating,
                    'image' => $review->image ? Storage::url($review->image) : null, // Thêm trường image
                    'number_of_likes' => $review->helpful_count,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                    'is_anonymous' => $review->is_anonymous,
                    'size' => $productDetail && isset($productDetail->size) ? ($productDetail->size->name ?? 'N/A') : 'N/A',
                    'color' => $productDetail && isset($productDetail->color) ? ($productDetail->color->name ?? 'N/A') : 'N/A',
                ];
            }
    
            \Log::info("Completed processing reviews for order_id: {$orderId}", ['total_reviews' => count($responseReviews)]);
    
            return response()->json([
                'message' => 'Các đánh giá đã được gửi thành công!',
                'reviews' => $responseReviews,
            ], 201);
        } catch (\Exception $e) {
            \Log::error("Error when storing reviews for order_id {$orderId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể gửi đánh giá', 'message' => $e->getMessage()], 500);
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
                'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Validate ảnh
                'service' => 'sometimes|integer|min:1|max:5',
                'packaging' => 'sometimes|integer|min:1|max:5',
                'shipping' => 'sometimes|integer|min:1|max:5',
                'customer_service' => 'sometimes|integer|min:1|max:5',
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

            // Xử lý upload ảnh mới nếu có
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu tồn tại
                if ($review->image) {
                    Storage::disk('public')->delete($review->image);
                }
                $updatedData['image'] = $request->file('image')->store('reviews', 'public');
            }

            $review->update($updatedData);

            return response()->json(['message' => 'Đánh giá đã được cập nhật thành công', 'review' => $this->formDataBack($review)]);
        } catch (\Exception $e) {
            \Log::error("Error when updating review {$reviewId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể cập nhật đánh giá', 'message' => $e->getMessage()], 500);
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

            return response()->json(['message' => 'Phản hồi đánh giá thành công', 'review' => $this->formDataBack($review)]);
        } catch (\Exception $e) {
            \Log::error("Error when replying to review {$reviewId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể phản hồi đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function like($reviewId)
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error("Error when liking review {$reviewId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể thích đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function report($reviewId)
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error("Error when reporting review {$reviewId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể báo cáo đánh giá', 'message' => $e->getMessage()], 500);
        }
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
                'review' => $this->formDataBack($review)
            ]);
        } catch (\Exception $e) {
            \Log::error("Error when toggling anonymous status for review {$reviewId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể thay đổi trạng thái ẩn danh', 'message' => $e->getMessage()], 500);
        }
    }

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
                'review' => $this->formDataBack($review)
            ]);
        } catch (\Exception $e) {
            \Log::error("Error when toggling hidden status for review {$reviewId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Không thể thay đổi trạng thái ẩn/hiện', 'message' => $e->getMessage()], 500);
        }
    }
}