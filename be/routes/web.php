<?php

use App\Models\Order;
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
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


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

Route::get('/order/confirm/{id}', function ($id) {
    $order = Order::find($id);

    if (!$order) {
        return view('order.notfound');
    }

    // Chỉ cho phép xác nhận khi đang ở trạng thái "chờ xác nhận"
    if ($order->status !== 'waiting_for_confirmation') {
        return view('order.already_confirmed');
    }

    // Cập nhật trạng thái
    $order->status = 'waiting_for_pickup';
    $order->save();

    return view('order.confirm_success', ['order' => $order]);
})->name('orders.confirm');

Route::get('/order/cancel/{id}', function ($id) {
    $order =Order::find($id);

    if (!$order) {
        return view('order.notfound');
    }

    if (in_array($order->status, ['cancelled', 'delivered'])) {
        return view('order.already_cancelled');
    }

    // Chỉ được hủy khi chưa giao
    $order->status = 'cancelled';
    $order->save();

    return view('order.cancel_success', ['order' => $order]);
})->name('orders.cancel');


Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboards.index');
    });

    // Route::resource('products', ProductController::class)->middleware('permission:show-products');
    // ----------------------------------------
    Route::resource('products', ProductController::class)
        ->middleware('permission:show-products');

    // Hoặc tách riêng create/store nếu bạn muốn gắn middleware khác
    Route::get('products/create', [ProductController::class, 'create'])
        ->name('products.create')
        ->middleware('permission:create-product');
    Route::post('products', [ProductController::class, 'store'])
        ->name('products.store')
        ->middleware('permission:create-product');
    // -----------------------------------------------------
    // ------------------------------------------------
    Route::resource('product-details', ProductDetailController::class);
    // Route::resource('colors', ColorController::class)->middleware('permission:show-colors');
    // color-done-----------------------
    // Colors
    Route::get('colors', [ColorController::class, 'index'])
        ->name('colors.index')
        ->middleware('permission:show-colors');

    Route::get('colors/create', [ColorController::class, 'create'])
        ->name('colors.create')
        ->middleware('permission:create-colors');

    Route::post('colors', [ColorController::class, 'store'])
        ->name('colors.store')
        ->middleware('permission:create-colors');

    Route::get('colors/{color}', [ColorController::class, 'show'])
        ->name('colors.show')
        ->middleware('permission:show-colors');

    Route::get('colors/{color}/edit', [ColorController::class, 'edit'])
        ->name('colors.edit')
        ->middleware('permission:edit-colors');

    Route::put('colors/{color}', [ColorController::class, 'update'])
        ->name('colors.update')
        ->middleware('permission:edit-colors');

    Route::delete('colors/{color}', [ColorController::class, 'destroy'])
        ->name('colors.destroy')
        ->middleware('permission:delete-colors');

    // -----------done--------------
    // Route::resource('sizes', SizeController::class)->middleware('permission:show-sizes');
    // ------------size----------------/
    // Sizes
    Route::get('sizes', [SizeController::class, 'index'])
        ->name('sizes.index')
        ->middleware('permission:show-sizes');

    Route::get('sizes/create', [SizeController::class, 'create'])
        ->name('sizes.create')
        ->middleware('permission:create-sizes');

    Route::post('sizes', [SizeController::class, 'store'])
        ->name('sizes.store')
        ->middleware('permission:create-sizes');

    Route::get('sizes/{size}', [SizeController::class, 'show'])
        ->name('sizes.show')
        ->middleware('permission:show-sizes');

    Route::get('sizes/{size}/edit', [SizeController::class, 'edit'])
        ->name('sizes.edit')
        ->middleware('permission:edit-sizes');

    Route::put('sizes/{size}', [SizeController::class, 'update'])
        ->name('sizes.update')
        ->middleware('permission:edit-sizes');

    Route::delete('sizes/{size}', [SizeController::class, 'destroy'])
        ->name('sizes.destroy')
        ->middleware('permission:delete-sizes');
    // --------------------done-size-------------

    // Route::resource('users', UserController::class)->middleware('permission:show-users');
    // -usersssssssssssssssss
    // Quản lý người dùng
    Route::get('users', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('permission:show-users');

    Route::get('users/create', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('permission:create-users');

    Route::post('users', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('permission:create-users');

    Route::get('users/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('permission:show-users');

    Route::get('users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('permission:edit-users');

    Route::put('users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('permission:edit-users');

    Route::delete('users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('permission:delete-users');
    // ---------------------done--------------
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
    // Route::resource('articles', ArticlesController::class)->middleware('permission:show-articles');
    // ------------articles----------------
    // Articles
    Route::get('articles', [ArticlesController::class, 'index'])
        ->name('articles.index')
        ->middleware('permission:show-articles');

    Route::get('articles/create', [ArticlesController::class, 'create'])
        ->name('articles.create')
        ->middleware('permission:create-articles');

    Route::post('articles', [ArticlesController::class, 'store'])
        ->name('articles.store')
        ->middleware('permission:create-articles');

    Route::get('articles/{article}', [ArticlesController::class, 'show'])
        ->name('articles.show')
        ->middleware('permission:show-articles');

    Route::get('articles/{article}/edit', [ArticlesController::class, 'edit'])
        ->name('articles.edit')
        ->middleware('permission:edit-articles');

    Route::put('articles/{article}', [ArticlesController::class, 'update'])
        ->name('articles.update')
        ->middleware('permission:edit-articles');

    Route::delete('articles/{article}', [ArticlesController::class, 'destroy'])
        ->name('articles.destroy')
        ->middleware('permission:delete-articles');
    // ----------------done-----------------

    // Comments
    Route::resource('comments', CommentController::class)->middleware('permission:show-comments');
    // Contacts
    Route::resource('contacts', ContactController::class)->middleware('permission:show-contacts');
    // Banners
    Route::resource('banners', BannerController::class)->middleware('permission:show-banners');
    // Vouchers
    // Route::resource('vouchers', VoucherController::class)->middleware('permission:show-vouchers');
    // vouchers------------
    // Vouchers
    Route::get('vouchers', [VoucherController::class, 'index'])
        ->name('vouchers.index')
        ->middleware('permission:show-vouchers');

    Route::get('vouchers/create', [VoucherController::class, 'create'])
        ->name('vouchers.create')
        ->middleware('permission:create-vouchers');

    Route::post('vouchers', [VoucherController::class, 'store'])
        ->name('vouchers.store')
        ->middleware('permission:create-vouchers');

    Route::get('vouchers/{voucher}', [VoucherController::class, 'show'])
        ->name('vouchers.show')
        ->middleware('permission:show-vouchers');

    Route::get('vouchers/{voucher}/edit', [VoucherController::class, 'edit'])
        ->name('vouchers.edit')
        ->middleware('permission:edit-vouchers');

    Route::put('vouchers/{voucher}', [VoucherController::class, 'update'])
        ->name('vouchers.update')
        ->middleware('permission:edit-vouchers');

    Route::delete('vouchers/{voucher}', [VoucherController::class, 'destroy'])
        ->name('vouchers.destroy')
        ->middleware('permission:delete-vouchers');

    // -------------done------------------
    // Categories
    // Route::resource('categories', CategoryController::class)->middleware('permission:show-categories');
    // -----------------categories--------------------
    // Categories
    Route::get('categories', [CategoryController::class, 'index'])
        ->name('categories.index')
        ->middleware('permission:show-categories');

    Route::get('categories/create', [CategoryController::class, 'create'])
        ->name('categories.create')
        ->middleware('permission:create-categories');

    Route::post('categories', [CategoryController::class, 'store'])
        ->name('categories.store')
        ->middleware('permission:create-categories');

    Route::get('categories/{category}', [CategoryController::class, 'show'])
        ->name('categories.show')
        ->middleware('permission:show-categories');

    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])
        ->name('categories.edit')
        ->middleware('permission:edit-categories');

    Route::put('categories/{category}', [CategoryController::class, 'update'])
        ->name('categories.update')
        ->middleware('permission:edit-categories');

    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy')
        ->middleware('permission:delete-categories');
    // --------------------done--------------------
    // Brands
    Route::resource('brands', BrandController::class)->middleware('permission:show-brands');
    // Dashboard

    Route::get('dashboards', [AdminController::class, 'index'])->name('dashboards.index');
    //tim kiem (lọc )
    Route::get('/admin/dashboard/filter', [AdminController::class, 'filterRevenue']);


    //thong ke tai khoan
    Route::get('admin/users', [AdminController::class, 'users'])->name('dashboards.users');
    Route::get('/admin/account-stats-data', [AdminController::class, 'getAccountStatsData'])->name('admin.accountStatsData');
    //thong ke sp
    Route::get('admin/product', [AdminController::class, 'products'])->name('dashboards.product');
    //top 10 sp
    Route::get('admin/top10', [AdminController::class, 'top10'])->name('dashboards.top10');



    // =======
    Route::get('dashboards', [AdminController::class, 'index'])->name('dashboards.index')->middleware('permission:show-dashboards');
    // >>>>>>> tuan-anh2

    
    
    
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
// Route::middleware(['role:admin'])->get('/articles', function () {
//     return response()->json(['message' => 'Welcome to Articles']);
// });


// Route::middleware(['role:superadmin'])->group(function () {
//     Route::get('/super-admin-dashboard', function () {
//         return response()->json(['message' => 'Welcome Super Admin']);
//     });

//     // Routes for Users
//     Route::resource('users', UserController::class);
// });

// Routes for Profiles
Route::resource('profiles', ProfileController::class);
// Route::middleware(['auth'])->group(function () {
//     Route::get('profiles/{user_id}', [ProfileController::class, 'show'])->name('profiles.show');
//     Route::get('/profile/create', [ProfileController::class, 'create']);
//     Route::post('/profile', [ProfileController::class, 'store']);
//     Route::get('/profile/{user_id}', [ProfileController::class, 'show']);
// });
// Route::middleware(['auth'])->get('/profile/{user}', [ProfileController::class, 'show'])->name('profiles.show');
// Route::middleware(['auth'])->get('/profile/{user_id}/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
// Route::middleware(['auth'])->put('/profile/{user}', [ProfileController::class, 'update'])->name('profiles.update');
Route::middleware(['auth'])->group(function () {
    // Hiển thị thông tin người dùng hiện tại
    Route::get('/profile', [ProfileController::class, 'show'])->name('profiles.show');

    // Chỉnh sửa thông tin người dùng hiện tại
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profiles.edit');

    // Cập nhật thông tin người dùng hiện tại
    Route::put('/profile', [ProfileController::class, 'update'])->name('profiles.update');

    // Tạo profile cho người dùng hiện tại
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profiles.create');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profiles.store');
});



// Routes for Roles
Route::resource('roles', RoleController::class);
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
// Route cho trang danh sách người dùng
