<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use App\Models\Review;
use App\Models\ReviewInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

Carbon::setLocale('vi');

class ReviewController extends Controller
{
    public function index($productId)
    {
        try {
            // Tối ưu truy vấn với eager loading và select chỉ các trường cần thiết
            $allReviews = Review::where('product_id', $productId)
                ->with(['user' => function ($query) {
                    $query->select('id', 'name', 'role'); // Chỉ lấy các trường cần thiết
                }])
                ->select('id', 'user_id', 'content', 'reply', 'helpful_count', 'created_at', 'is_anonymous', 'rating')
                ->get();

            $reviewsData = $allReviews->map(function ($review) {
                return [
                    'user_name' => $review->user->name,
                    'user_role' => $review->user->role,
                    'content' => $review->content,
                    'reply' => $review->reply,
                    'number_of_likes' => $review->helpful_count,
                    'created_at' => $review->created_at->diffForHumans(),
                    'is_anonymous' => $review->is_anonymous,
                    'rating' => $review->rating,
                ];
            });

            return response()->json([
                'reviews' => $reviewsData,
                'total_reviews' => $allReviews->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể truy vấn đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    // public function myReviews()
    // {
    //     try {
    //         $user = Auth::user();

    //         $reviews = Review::where('user_id', $user->id)
    //             ->with(['product' => function ($query) {
    //                 $query->select('id', 'name'); // Chỉ lấy id và name của sản phẩm
    //             }])
    //             ->select('id', 'product_id', 'rating', 'content', 'created_at', 'is_anonymous', 'is_edited', 'helpful_count', 'is_reported')
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //         $reviewsData = $reviews->map(function ($review) {
    //             return [
    //                 'product_name' => $review->product->name,
    //                 'product_id' => $review->product_id,
    //                 'rating' => $review->rating,
    //                 'content' => $review->content,
    //                 'created_at' => $review->created_at->diffForHumans(),
    //                 'is_anonymous' => $review->is_anonymous,
    //                 'is_edited' => $review->is_edited,
    //                 'helpful_count' => $review->helpful_count,
    //                 'is_reported' => $review->is_reported,
    //             ];
    //         });

    //         return response()->json([
    //             'my_reviews' => $reviewsData,
    //             'total_reviews' => $reviews->count(),
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Không thể lấy danh sách đánh giá của bạn', 'message' => $e->getMessage()], 500);
    //     }
    // }
    public function myReviews()
    {
        try {
            $user = Auth::user();
    
            $reviews = Review::where('user_id', $user->id)
                ->with(['product' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->select('id', 'product_id', 'order_id', 'rating', 'content', 'created_at', 'is_anonymous', 'is_edited', 'helpful_count', 'is_reported') // Thêm order_id vào select
                ->orderBy('created_at', 'desc')
                ->get();
    
            $reviewsData = $reviews->map(function ($review) {
                return [
                    'product_name' => $review->product->name,
                    'product_id' => $review->product_id,
                    'order_id' => $review->order_id, // Thêm order_id
                    'rating' => $review->rating,
                    'content' => $review->content,
                    'created_at' => $review->created_at->diffForHumans(),
                    'is_anonymous' => $review->is_anonymous,
                    'is_edited' => $review->is_edited,
                    'helpful_count' => $review->helpful_count,
                    'is_reported' => $review->is_reported,
                ];
            });
    
            return response()->json([
                'my_reviews' => $reviewsData,
                'total_reviews' => $reviews->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể lấy danh sách đánh giá của bạn', 'message' => $e->getMessage()], 500);
        }
    }
    public function show($reviewId)
    {
        try {
            $user = Auth::user();

            $review = Review::with(['product' => function ($query) {
                $query->select('id', 'name');
            }])
            ->select('id', 'product_id', 'rating', 'content', 'reply', 'service', 'packaging', 'shipping', 'customer_service', 'created_at', 'helpful_count', 'is_anonymous', 'is_edited', 'is_reported')
            ->find($reviewId);

            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            if ($user->role == 'user' && $review->user_id !== $user->id) {
                return response()->json(['message' => 'Bạn không có quyền xem đánh giá này'], 403);
            }

            $reviewData = [
                'product_name' => $review->product->name,
                'product_id' => $review->product_id,
                'rating' => $review->rating,
                'content' => $review->content,
                'reply' => $review->reply,
                'service' => $review->service,
                'packaging' => $review->packaging,
                'shipping' => $review->shipping,
                'customer_service' => $review->customer_service,
                'created_at' => $review->created_at->diffForHumans(),
                'helpful_count' => $review->helpful_count,
                'is_anonymous' => $review->is_anonymous,
                'is_edited' => $review->is_edited,
                'is_reported' => $review->is_reported,
            ];

            return response()->json(['review' => $reviewData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể lấy chi tiết đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function storeReviewAfterDelivery(Request $request, $productId, $orderId)
    {
        try {
            $user = Auth::user();
            $userId = $user->id;

            // Tối ưu truy vấn bằng cách dùng exists() thay vì first()
            $orderExists = Order::where('id', $orderId)
                ->where('user_id', $userId)
                ->where('status', 'delivered')
                ->exists();

            if (!$orderExists) {
                return response()->json(['message' => 'Bạn chỉ có thể đánh giá sau khi đơn hàng đã được giao'], 403);
            }

            // Tối ưu truy vấn với pluck và exists trong một lần
            $productDetailIds = ProductDetail::where('product_id', $productId)->pluck('id');
            $productInOrder = OrderDetail::where('order_id', $orderId)
                ->whereIn('product_detail_id', $productDetailIds)
                ->exists();

            if (!$productInOrder) {
                return response()->json(['message' => 'Sản phẩm này không có trong đơn hàng'], 403);
            }

            // Kiểm tra đánh giá đã tồn tại
            $existingReview = Review::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('order_id', $orderId)
                ->exists();

            if ($existingReview) {
                return response()->json(['message' => 'Bạn đã đánh giá sản phẩm này rồi'], 403);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'content' => 'nullable|string|max:500',
                'service' => 'nullable|integer|min:1|max:5',
                'packaging' => 'nullable|integer|min:1|max:5',
                'shipping' => 'nullable|integer|min:1|max:5',
                'customer_service' => 'nullable|integer|min:1|max:5',
                'is_anonymous' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => 'Dữ liệu đầu vào không hợp lệ', 'details' => $validator->errors()], 400);
            }

            // Tạo đánh giá mới
            $review = Review::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'order_id' => $orderId,
                'rating' => $request->rating,
                'content' => $request->content ?? '',
                'service' => $request->service ?? 1,
                'packaging' => $request->packaging ?? 1,
                'shipping' => $request->shipping ?? 1,
                'customer_service' => $request->customer_service ?? 1,
                'is_anonymous' => $request->is_anonymous ?? false,
            ]);

            // Load thông tin user để trả về dữ liệu đầy đủ
            $review->load(['user' => function ($query) {
                $query->select('id', 'name', 'role');
            }]);

            // Chuẩn bị dữ liệu đánh giá mới để frontend thêm vào danh sách
            $reviewData = [
                'user_name' => $review->user->name,
                'user_role' => $review->user->role,
                'content' => $review->content,
                'reply' => $review->reply,
                'number_of_likes' => $review->helpful_count,
                'created_at' => $review->created_at->diffForHumans(),
                'is_anonymous' => $review->is_anonymous,
                'rating' => $review->rating,
            ];

            return response()->json([
                'message' => 'Đánh giá đã được gửi thành công!',
                'review' => $reviewData, // Trả về dữ liệu đánh giá mới
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
}