<?php

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

Route::get('/', function () {
    return view('master');
});
//quan lý bài viết
Route::resource('/articles', ArticlesController::class);
//quan ly comment
Route::resource('/comments', CommentController::class);

