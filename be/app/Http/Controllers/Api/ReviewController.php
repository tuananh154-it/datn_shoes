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

Carbon::setLocale('vi');

class ReviewController extends Controller
{
    public function index($productId)
    {
        try {
            $allReviews = Review::where('product_id', $productId)
                ->with('user') // Lấy thông tin user
                ->get();

            $reviewsData = $allReviews->map(function ($review) {
                return [
                    'user_name' => $review->user->name,
                    'user_role' => $review->user->role,
                    'content' => $review->content,
                    'reply' => $review->reply, // Phản hồi từ admin
                    'number_of_likes' => $review->helpful_count,
                    'created_at' => $review->created_at->diffForHumans(),
                    'is_anonymous' => $review->is_anonymous,
                ];
            });

            return response()->json([
                'reviews' => $reviewsData,
                'total_reviews' => $allReviews->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể truy vấn reviews', 'message' => $e->getMessage()], 500);
        }
    }

    public function myReviews()
    {
        try {
            $user = Auth::user();

            $reviews = Review::where('user_id', $user->id)
                ->with(['product']) // Load thông tin sản phẩm đã đánh giá
                ->orderBy('created_at', 'desc')
                ->get();

            $reviewsData = $reviews->map(function ($review) {
                return [
                    'product_name' => $review->product->name,
                    'product_id' => $review->product_id,
                    'rating' => $review->rating,
                    'content' => $review->content,
                    'created_at' => $review->created_at->diffForHumans(),
                    'is_anonymous' => $review->is_anonymous,
                    'is_edited' => $review->is_edited,
                    'helpful_count' => $review->helpful_count,
                    'is_reported' => $review->is_reported
                ];
            });

            return response()->json([
                'my_reviews' => $reviewsData,
                'total_reviews' => $reviews->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể lấy danh sách đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($reviewId)
    {
        try {
            $user = Auth::user();

            // Lấy đánh giá theo ID, kèm sản phẩm liên quan
            $review = Review::with('product')->find($reviewId);

            // Kiểm tra nếu đánh giá không tồn tại
            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            // Kiểm tra quyền truy cập: Admin có thể xem tất cả, user chỉ xem review của mình
            if ($user->role == 'user' && $review->user_id !== $user->id) {
                return response()->json(['message' => 'Bạn không có quyền xem đánh giá này'], 403);
            }

            // Chuẩn bị dữ liệu trả về
            $reviewData = [
                'product_name' => $review->product->name,
                'product_id' => $review->product_id,
                'rating' => $review->rating,
                'content' => $review->content,
                'reply' => $review->reply, // Phản hồi từ Admin (nếu có)
                'service' => $review->service,
                'packaging' => $review->packaging,
                'shipping' => $review->shipping,
                'customer_service' => $review->customer_service,
                'created_at' => $review->created_at->diffForHumans(),
                'helpful_count' => $review->helpful_count,
                'is_anonymous' => $review->is_anonymous,
                'is_edited' => $review->is_edited,
                'is_reported' => $review->is_reported
            ];

            return response()->json(['review' => $reviewData]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể lấy chi tiết đánh giá', 'message' => $e->getMessage()], 500);
        }
    }

    public function storeReviewAfterDelivery(Request $request, $productId, $orderId)
    {
        try {
            $userId = Auth::id();

            // Kiểm tra xem đơn hàng có tồn tại và đã giao hàng chưa
            $order = Order::where('id', $orderId)
                ->where('user_id', $userId)
                ->where('status', 'delivered')
                ->first();

            if (!$order) {
                return response()->json(['message' => 'Bạn chỉ có thể đánh giá sau khi đơn hàng đã được giao.'], 403);
            }

            // Lấy tất cả biến thể của sản phẩm gốc
            $productDetailIds = ProductDetail::where('product_id', $productId)->pluck('id');

            // Kiểm tra xem đơn hàng có chứa bất kỳ biến thể nào của sản phẩm hay không
            $productInOrder = OrderDetail::where('order_id', $orderId)
                ->whereIn('product_detail_id', $productDetailIds)
                ->exists();

            if (!$productInOrder) {
                return response()->json(['message' => 'Sản phẩm hoặc biến thể không có trong đơn hàng này.'], 403);
            }

            // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
            $existingReview = Review::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('order_id', $orderId)
                ->first();

            if ($existingReview) {
                return response()->json(['message' => 'Bạn đã đánh giá sản phẩm này rồi.'], 403);
            }

            // Xác thực dữ liệu đánh giá
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
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // Lưu đánh giá vào database
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

            return response()->json([
                'message' => 'Đánh giá thành công!',
                'review' => $review
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể đánh giá sản phẩm', 'message' => $e->getMessage()], 500);
        }
    }

    public function reply(Request $request, $reviewId)
    {
        try {
            // Kiểm tra quyền Admin
            if (Auth::user()->role == 'user') {
                return response()->json(['message' => 'Bạn không có quyền phản hồi'], 403);
            }

            // Kiểm tra review tồn tại
            $review = Review::find($reviewId);
            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            // Kiểm tra nếu đã phản hồi trước đó
            if ($review->is_replied) {
                return response()->json(['message' => 'Bạn chỉ có thể phản hồi một lần'], 400);
            }

            // Xác thực input
            $validator = Validator::make($request->all(), [
                'reply' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // Cập nhật phản hồi và đánh dấu đã trả lời
            $review->update([
                'reply' => $request->reply,
                'is_replied' => true, // Đánh dấu là đã phản hồi
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

            // Tìm đánh giá
            $review = Review::find($reviewId);

            // Kiểm tra nếu đánh giá không tồn tại
            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            // Chỉ cho phép user sở hữu đánh giá chỉnh sửa
            if ($review->user_id !== $user->id) {
                return response()->json(['message' => 'Bạn không có quyền chỉnh sửa đánh giá này'], 403);
            }

            // Kiểm tra nếu đánh giá đã bị chỉnh sửa trước đó
            if ($review->is_edited) {
                return response()->json(['message' => 'Bạn chỉ có thể sửa đánh giá 1 lần'], 400);
            }

            // Validate dữ liệu đầu vào
            $validator = Validator::make($request->all(), [
                'rating' => 'sometimes|integer|min:1|max:5', // Rating chỉ từ 1 đến 5
                'content' => 'required|string|max:1000',
                'service' => 'sometimes|integer|min:1|max:5',
                'packaging' => 'sometimes|integer|min:1|max:5',
                'shipping' => 'sometimes|integer|min:1|max:5',
                'customer_service' => 'sometimes|integer|min:1|max:5',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // Lấy giá trị mới từ request hoặc giữ giá trị mặc định (nếu không có giá trị)
            $updatedData = [
                'rating' => $request->input('rating', $review->rating), // Dùng rating cũ nếu không có mới
                'content' => $request->input('content'),
                'service' => $request->input('service', $review->service), // Giữ service cũ nếu không có mới
                'packaging' => $request->input('packaging', $review->packaging),
                'shipping' => $request->input('shipping', $review->shipping),
                'customer_service' => $request->input('customer_service', $review->customer_service),
                'is_edited' => true, // Đánh dấu là đã chỉnh sửa
            ];

            // Cập nhật thông tin đánh giá
            $review->update($updatedData);

            return response()->json(['message' => 'Cập nhật đánh giá thành công', 'review' => $review]);
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

        // Kiểm tra xem đã like chưa
        if ($review->likes()->where('user_id', $userId)->exists()) {
            $review->likes()->where('user_id', $userId)->delete(); // Xóa like
            $review->decrement('helpful_count'); // Giảm số lượng likes của đánh giá
            return response()->json(['message' => 'Đã hủy like thành công'], 200);
        }

        $review->increment('helpful_count'); // Tăng số lượng likes của đánh giá
        $review->save();

        // Thêm like
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

        // Kiểm tra xem người dùng có quyền báo cáo đánh giá này không
        if ($review->user_id === $userId) {
            return response()->json(['message' => 'Bạn không thể báo cáo đánh giá của chính mình'], 403);
        }
        // Kiểm tra xem người dùng có quyền báo cáo hay không
        if (Auth::user()->role == 'admin') {
            return response()->json(['message' => 'Admin không thể báo cáo đánh giá'], 403);
        }

        // Kiểm tra xem đã báo cáo chưa
        if ($review->reports()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Bạn đã báo cáo đánh giá này rồi'], 400);
        }

        // Thêm report
        ReviewInteraction::create([
            'review_id' => $reviewId,
            'user_id' => $userId,
            'type' => 2
        ]);

        return response()->json(['message' => 'Đánh giá đã được báo cáo']);
    }

    public function toggleAnonymous($reviewId)
    {
        try {
            $userId = Auth::id();

            // Tìm đánh giá
            $review = Review::find($reviewId);

            if (!$review) {
                return response()->json(['message' => 'Đánh giá không tồn tại'], 404);
            }

            // Thay đổi trạng thái ẩn danh (ngược lại)
            $review->is_anonymous = !$review->is_anonymous;
            $review->save();

            return response()->json([
                'message' => $review->is_anonymous ? 'Đánh giá đã được chuyển thành ẩn danh' : 'Đánh giá đã không còn ẩn danh',
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Không thể thay đổi trạng thái ẩn danh', 'message' => $e->getMessage()], 500);
        }
    }
}
