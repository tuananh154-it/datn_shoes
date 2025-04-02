<?php

use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\BannerController;

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\OnlineCheckOutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\VoucherController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


// Public routes
Route::apiResource('articles', ArticleController::class);
Route::apiResource('comments', CommentController::class);

Route::apiResource('contacts', ContactController::class);
Route::apiResource('banners', BannerController::class);


Route::middleware('auth:api')->group(function () {
    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'addToCart']);
        Route::put('/update/{id_cart_item}', [CartController::class, 'updateCart']);
        Route::delete('/remove/{id_cart_item}', [CartController::class, 'removeCartItem']);
        Route::post('/sync', [CartController::class, 'syncCart']);
    });

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'listOrders']);
        Route::post('/place', [OrderController::class, 'placeOrder']);
        Route::get('/{id}', [OrderController::class, 'orderDetail'])->whereNumber('id');
        Route::post('/{id}/cancel', [OrderController::class, 'cancelOrder'])->whereNumber('id');
    });

    // Checkout
    Route::get('/checkout/init', [OrderController::class, 'getCart']);


    // Payment
    Route::post('/orders', [OrderController::class, 'placeOrder']);
    Route::get('/orders', [OrderController::class, 'listOrders']);
    Route::get('/orders/{id}', [OrderController::class, 'orderDetail']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);
    // Route gửi yêu cầu thanh toán MoMo
    Route::post('/momo-payment', [OnlineCheckOutController::class, 'momo_payment'])->name('momo.payment');

    // Route nhận phản hồi từ MoMo (IPN)
    Route::post('/momo/ipn', [OnlineCheckOutController::class, 'momo_ipn'])->name('momo.ipn');

    // Route kiểm tra trạng thái thanh toán (nếu cần)
    Route::get('/momo/status', function () {
        return response()->json(['message' => 'MoMo IPN hoạt động!']);
    })->name('momo.status');
});

Route::apiResource('products', ProductController::class);
Route::get('/latest-products', [ProductController::class, 'latestProducts']);



// Trang Home
Route::get('/home', [HomeController::class, 'index']);

// Trang Danh Mục
Route::get('/categories', [CategoryController::class, 'index']);

// Trang Thương Hiệu
Route::get('/brands', [BrandController::class, 'index']);

// Trang Voucher
Route::get('/vouchers', [VoucherController::class, 'index']);
Route::get('/vouchers/{id}', [VoucherController::class, 'show']);
// Auth routes
// Routes protected by JWT Middleware
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    // Admin routes (only accessible by admins)
    Route::middleware(['admin'])->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    });

    // Product-related routes
    Route::apiResource('colors', ColorController::class);
    Route::apiResource('sizes', SizeController::class);
    Route::apiResource('product-details', ProductDetailController::class);
});


Route::get('/users', [UserController::class, 'index'])->name('api.users.index');

Route::post('/users', [UserController::class, 'store'])->name('api.users.store');

// Route để chỉnh sửa thông tin người dùng (API)
Route::put('/users/{user}', [UserController::class, 'update'])->name('api.users.update');

// Route để xóa người dùng (API)
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('api.users.destroy');
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// =======

Route::post('login', [AuthController::class, 'login']);
// >>>>>>> tuan-anh2

// quên mật khẩu
Route::post('password/forgot', [ForgotPasswordController::class, 'sendResetToken']);

// đặt lại mật khẩu
Route::post('password/reset', [ResetPasswordController::class, 'submitResetPasswordForm']);

// Bình luận
Route::get('product/{productId}/comments', [CommentController::class, 'index']); // Danh sách bình luận theo sản phẩm
Route::get('comment/{commentId}', [CommentController::class, 'show']); // Chi tiết bình luận
Route::post('product/{productId}/post', [CommentController::class, 'store'])->middleware('auth:api'); // Đăng bình luận
Route::post('comment/{parentId}/reply', [CommentController::class, 'reply'])->middleware('auth:api'); // Phản hồi bình luận
Route::put('comment/{commentId}/edit', [CommentController::class, 'update'])->middleware('auth:api'); // Sửa bình luận
Route::delete('comment/{commentId}', [CommentController::class, 'destroy'])->middleware('auth:api'); // Xóa mềm bình luận
Route::delete('comment/{commentId}/force', [CommentController::class, 'forceDelete'])->middleware('auth:api'); // Xóa vĩnh viễn (Admin)
Route::put('comment/{commentId}/like', action: [CommentController::class, 'like'])->middleware('auth:api'); // Like bình luận
Route::put('comment/{commentId}/report', [CommentController::class, 'report'])->middleware('auth:api'); // Báo cáo bình luận

// Đánh giá
Route::get('product/{productId}/reviews', [ReviewController::class, 'index']); // Danh sách đánh giá theo sản phẩm
Route::get('reviews', [ReviewController::class, 'myReviews'])->middleware('auth:api'); // Đánh giá của bản thân
Route::get('review/{reviewId}', [ReviewController::class, 'show'])->middleware('auth:api'); // Chi tiết đánh giá
Route::post('product/{productId}/order/{orderId}/review', [ReviewController::class, 'storeReviewAfterDelivery'])->middleware('auth:api'); // Đánh giá sau khi nhận hàng
Route::post('review/{reviewId}/reply', [ReviewController::class, 'reply'])->middleware('auth:api'); // Phản hồi đánh giá (Admin)
Route::put('review/{reviewId}/edit', [ReviewController::class, 'update'])->middleware('auth:api'); // Chỉnh sửa đánh giá (chỉ 1 lần)
Route::put('review/{reviewId}/like', [ReviewController::class, 'like'])->middleware('auth:api'); // Like đánh giá
Route::put('review/{reviewId}/report', [ReviewController::class, 'report'])->middleware('auth:api'); // Báo cáo đánh giá
Route::put('review/{reviewId}/anonymous', [ReviewController::class, 'toggleAnonymous'])->middleware('auth:api'); // ẩn danh đánh giá
