<?php

use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SizeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::apiResource('articles', ArticleController::class);
Route::apiResource('comments',CommentController::class);
Route::apiResource('contacts',ContactController::class);
Route::apiResource('banners',BannerController::class);
Route::apiResource('products', ProductController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Middleware JWT bảo vệ API
Route::middleware(['jwt.auth', 'auth:api'])->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::middleware(['admin'])->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    });
});
// Route::apiResource('products', ProductController::class);
// Route::apiResource('colors', ColorController::class);
// Route::apiResource('sizes', SizeController::class);
// Route::apiResource('product-details', ProductDetailController::class);
