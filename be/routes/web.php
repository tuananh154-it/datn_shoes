<?php

use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SizeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VoucherController;

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

Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('/', function () {
        return view('dashboards.index');
    });

    Route::resource('products', ProductController::class);
    Route::resource('product-details', ProductDetailController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    // Product Details
    Route::get('products/{productId}/details/create', [ProductDetailController::class, 'create'])->name('product-details.create');
    Route::post('products/{productId}/details', [ProductDetailController::class, 'store'])->name('product-details.store');
    Route::get('product-details/{id}/edit', [ProductDetailController::class, 'edit'])->name('product-details.edit');
    Route::put('product-details/{id}', [ProductDetailController::class, 'update'])->name('product-details.update');
    Route::delete('product-details/{id}', [ProductDetailController::class, 'destroy'])->name('product-details.destroy');

    // CKEditor upload
    Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');

    // Orders
    Route::resource('orders', OrderController::class);
    Route::put('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update_status');

    // Articles
    Route::resource('articles', ArticlesController::class);

    // Comments
    Route::resource('comments', CommentController::class);

    // Contacts
    Route::resource('contacts', ContactController::class);

    // Banners
    Route::resource('banners', BannerController::class);

    // Vouchers
    Route::resource('vouchers', VoucherController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Brands
    Route::resource('brands', BrandController::class);

    // Dashboard

    Route::get('dashboards', [AdminController::class, 'index'])->name('dashboards.index');
    //tim kiem (lọc )
    Route::get('/admin/dashboard/filter', [AdminController::class, 'filterRevenue']);

    Route::get('admin/orders', [AdminController::class, 'orders'])->name('dashboards.orders');
    Route::get('admin/reviews', [AdminController::class, 'reviews'])->name('dashboards.reviews');
    Route::get('admin/products', [AdminController::class, 'products'])->name('dashboards.products');
    //thong ke tai khoan 
    Route::get('admin/users', [AdminController::class, 'users'])->name('dashboards.users');
    Route::get('/admin/account-stats-data', [AdminController::class, 'getAccountStatsData'])->name('admin.accountStatsData');
    

    
});


// Auth Routes
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'dangnhap']);
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'dangky']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Role-based Routes
Route::middleware(['role:admin'])->get('/admin-dashboard', function () {
    return response()->json(['message' => 'Welcome Admin']);
});

Route::middleware(['role:super admin'])->group(function () {
    Route::get('/super-admin-dashboard', function () {
        return response()->json(['message' => 'Welcome Super Admin']);
    });

    // Routes for Users
    Route::resource('users', UserController::class);
});

// Routes for Profiles
Route::resource('profiles', ProfileController::class);

// Routes for Roles
Route::resource('roles', RoleController::class);
// Route cho trang danh sách người dùng
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Route cho form tạo người dùng mới
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

// Route để tạo người dùng mới
Route::post('/users', [UserController::class, 'store'])->name('users.store');

// Route cho form chỉnh sửa người dùng
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

// Route để cập nhật thông tin người dùng
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

// Route để xóa người dùng
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
