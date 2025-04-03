<?php

use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\Top10SPController;
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
    Route::get('/cart', [CartController::class, 'index']);
    Route::put('/cart/update/{id_cart_item}', [CartController::class, 'updateCart']);
    Route::delete('/cart/remove/{id_cart_item}', [CartController::class, 'removeCartItem']);
    // Route::apiResource('order', OrderController::class);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/sync', [CartController::class, 'syncCart']);
    Route::get('/checkout/init', [OrderController::class, 'getCart']);
    Route::post('/checkout/preview', [OrderController::class, 'previewCheckout']);
    Route::post('/orders', [OrderController::class, 'placeOrder']);
    Route::get('/orders', [OrderController::class, 'listOrders']);
    Route::get('/orders/{id}', [OrderController::class, 'orderDetail']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);
    Route::post('/momo-payment', [OnlineCheckOutController::class, 'momo_payment']);
    Route::post('/momo-payment-code', [OnlineCheckOutController::class, 'momo_payment_code']);
});
Route::apiResource('products', ProductController::class);
Route::get('/latest-products', [ProductController::class, 'latestProducts']);



// Trang Home
Route::get('/home', [HomeController::class, 'index']);
// top 10 sp

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
Route::put('/users/{user}', [UserController::class, 'updateApi'])->middleware('auth:api');
// Route để xóa người dùng (API)
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('api.users.destroy');
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// top 19 sp 
Route::get('/top10', [Top10SPController::class, 'top10']);

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