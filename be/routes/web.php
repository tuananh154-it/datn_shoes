<?php

use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SizeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admins\ContactController as AdminsContactController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BannerController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/addresses', [AddressController::class, 'showAddresses']);

// Route::get('/', function () {
//     return view('master');
// });
// Route::resource('products', ProductController::class);
// Route::resource('product-details', ProductDetailController::class);
// Route::resource('colors', ColorController::class);
// Route::resource('sizes', SizeController::class);



Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('master');
    });

    Route::resource('products', ProductController::class);
    Route::resource('product-details', ProductDetailController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);
    // Route::get('products/{product}/details/create', [ProductController::class, 'createDetail'])->name('products.details.create');
    // Route::post('products/{product}/details', [ProductController::class, 'storeDetail'])->name('products.details.store');
    //quan lý bài viết
    Route::resource('articles', ArticlesController::class);
    //quan ly comment
    Route::resource('comments', CommentController::class);
    Route::resource('contacts', ContactController::class);

    Route::resource('banners', BannerController::class);
});
