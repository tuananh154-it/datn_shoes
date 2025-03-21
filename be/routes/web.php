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

// Route::get('/addresses', [AddressController::class, 'showAddresses']);

// Route::get('/', function () {
//     return view('master');
// });
// Route::resource('products', ProductController::class);
// Route::resource('product-details', ProductDetailController::class);
// Route::resource('colors', ColorController::class);
// Route::resource('sizes', SizeController::class);

// ->middleware(['role:admin|super admin'])

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
    // Route::get('products/{product}/details/create', [ProductController::class, 'createDetail'])->name('products.details.create');
    // Route::post('products/{product}/details', [ProductController::class, 'storeDetail'])->name('products.details.store');
    Route::get('products/{productId}/details/create', [ProductDetailController::class, 'create'])->name('product-details.create');
    Route::post('products/{productId}/details', [ProductDetailController::class, 'store'])->name('product-details.store');
    Route::get('product-details/{id}/edit', [ProductDetailController::class, 'edit'])->name('product-details.edit');
    Route::put('product-details/{id}', [ProductDetailController::class, 'update'])->name('product-details.update');
    Route::delete('product-details/{id}', [ProductDetailController::class, 'destroy'])->name('product-details.destroy');
    Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');
    // đơn hàng
    Route::resource('orders', OrderController::class);
    // Đảm bảo rằng route này đã được khai báo trong `routes/web.php`
    Route::put('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update_status');

    //quan lý bài viết
    Route::resource('articles', ArticlesController::class);
    //quan ly comment
    Route::resource('comments', CommentController::class);
    //lien he
    Route::resource('contacts', ContactController::class);

    //banner
    Route::resource('banners', BannerController::class);
     //quan ly voucher
     Route::resource('vouchers', VoucherController::class);
     //quan ly danh muc
     Route::resource('categories', CategoryController::class);
     //quan ly thuong hieu
     Route::resource('brands', BrandController::class);
     //Dashboard 
     Route::get('dashboards', [AdminController::class, 'index'])->name('dashboards.index');
     Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

});




// Auth::routes();
// vào thẳng trang login luôn
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'dangnhap']);
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'dangky']);

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

