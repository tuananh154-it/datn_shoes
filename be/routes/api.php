<?php

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
use App\Http\Controllers\Api\VoucherController;
use App\Http\Controllers\UserController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


// Public routes
Route::apiResource('articles', ArticleController::class);
Route::apiResource('comments', CommentController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('contacts', ContactController::class);
Route::apiResource('banners', BannerController::class);
Route::middleware('auth:api')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::put('/cart/update/{id_cart_item}', [CartController::class, 'updateCart']);
    Route::delete('/cart/remove/{id_cart_item}', [CartController::class, 'removeCartItem']);

    // Route::apiResource('order', OrderController::class);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/sync', [CartController::class, 'syncCart']);
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