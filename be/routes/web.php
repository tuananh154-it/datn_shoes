<?php

use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SizeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ArticlesController;



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
    Route::get('products/{productId}/details/create', [ProductDetailController::class, 'create'])->name('product-details.create');
    Route::post('products/{productId}/details', [ProductDetailController::class, 'store'])->name('product-details.store');
    Route::get('product-details/{id}/edit', [ProductDetailController::class, 'edit'])->name('product-details.edit');
    Route::put('product-details/{id}', [ProductDetailController::class, 'update'])->name('product-details.update');
    Route::delete('product-details/{id}', [ProductDetailController::class, 'destroy'])->name('product-details.destroy');

});
//quan lý bài viết
Route::resource('/articles', ArticlesController::class);
//quan ly comment
Route::resource('/comments', CommentController::class);

