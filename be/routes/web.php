<?php

use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SizeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admins\ContactController as AdminsContactController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BannerController;


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});


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



Route::prefix('admin')->middleware(['role:admin|super admin'])->group(function () {
    Route::get('/', function () {
        return view('master');
    });

    Route::resource('products', ProductController::class);
    Route::resource('product-details', ProductDetailController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    // Route::get('products/{product}/details/create', [ProductController::class, 'createDetail'])->name('products.details.create');
    // Route::post('products/{product}/details', [ProductController::class, 'storeDetail'])->name('products.details.store');
    Route::get('products/{productId}/details/create', [ProductDetailController::class, 'create'])->name('product-details.create');
    Route::post('products/{productId}/details', [ProductDetailController::class, 'store'])->name('product-details.store');
    Route::get('product-details/{id}/edit', [ProductDetailController::class, 'edit'])->name('product-details.edit');
    Route::put('product-details/{id}', [ProductDetailController::class, 'update'])->name('product-details.update');
    Route::delete('product-details/{id}', [ProductDetailController::class, 'destroy'])->name('product-details.destroy');
    Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');

    //quan lý bài viết
    Route::resource('articles', ArticlesController::class);
    //quan ly comment
    Route::resource('comments', CommentController::class);
    Route::resource('contacts', ContactController::class);

    Route::resource('banners', BannerController::class);
});
//quan lý bài viết
Route::resource('/articles', ArticlesController::class);
//quan ly comment
Route::resource('/comments', CommentController::class);


// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
// Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
// Route::post('register', [AuthController::class, 'register']);

// Route đăng xuất
// Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('register', [AuthController::class, 'showRegisterForm']);
Route::middleware(['role:admin'])->get('/admin-dashboard', function () {
    return response()->json(['message' => 'Welcome Admin']);
});

Route::middleware(['role:super admin'])->group(function () {
    Route::get('/super-admin-dashboard', function () {
        return response()->json(['message' => 'Welcome Super Admin']);
    });
    // Tạo các route cho Users, Profiles và Roles
    Route::resource('users', UserController::class);
});

// Routes cho Profiles
Route::resource('profiles', ProfileController::class);

// Routes cho Roles
// Route::resource('roles', RoleController::class);
