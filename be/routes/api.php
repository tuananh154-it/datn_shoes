<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\VoucherController;


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
Route::apiResource('carts', CartController::class);


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
    Route::apiResource('products', ProductController::class);
    Route::apiResource('colors', ColorController::class);
    Route::apiResource('sizes', SizeController::class);
    Route::apiResource('product-details', ProductDetailController::class);
});

