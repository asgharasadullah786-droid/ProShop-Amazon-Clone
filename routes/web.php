<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (only once)
Auth::routes();

// Home route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Product Routes
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::resource('products', ProductController::class);

// Cart Routes - Only Customers
Route::prefix('cart')->middleware(['auth', 'customer'])->group(function () {
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Wishlist Routes - Only Customers
Route::prefix('wishlist')->middleware(['auth', 'customer'])->group(function () {
    Route::post('/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Review Routes (Requires login)
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// Coupon Routes (Requires login)
Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply')->middleware('auth');
Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove')->middleware('auth');

// Checkout Routes (Requires login)
Route::prefix('checkout')->middleware('auth')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/place', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
});

// Order Routes (Requires login)
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth');


// Profile Routes
Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::get('/orders', [App\Http\Controllers\ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/orders/{id}', [App\Http\Controllers\ProfileController::class, 'orderDetails'])->name('profile.order-details');
});


// Address Routes
Route::resource('addresses', App\Http\Controllers\AddressController::class)->middleware('auth');
Route::post('/addresses/{address}/set-default', [App\Http\Controllers\AddressController::class, 'setDefault'])->name('addresses.set-default')->middleware('auth');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/orders', [App\Http\Controllers\Admin\DashboardController::class, 'orders'])->name('admin.orders');
    Route::post('/orders/{order}/update-status', [App\Http\Controllers\Admin\DashboardController::class, 'updateOrderStatus'])->name('admin.orders.update-status');
    Route::get('/users', [App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/update-role', [App\Http\Controllers\Admin\DashboardController::class, 'updateUserRole'])->name('admin.users.update-role');
    Route::get('/export-orders', [App\Http\Controllers\Admin\DashboardController::class, 'exportOrders'])->name('admin.export-orders');
});

Route::get('/invoice/{order}/download', [App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download')->middleware('auth');

// Flash Sales Routes
Route::get('/flash-sales', [App\Http\Controllers\FlashSaleController::class, 'index'])->name('flash-sales.index');
Route::get('/admin/flash-sales', [App\Http\Controllers\FlashSaleController::class, 'adminIndex'])->name('admin.flash-sales')->middleware(['auth', 'admin']);
Route::post('/admin/flash-sales', [App\Http\Controllers\FlashSaleController::class, 'store'])->name('admin.flash-sales.store')->middleware(['auth', 'admin']);
Route::delete('/admin/flash-sales/{flashSale}', [App\Http\Controllers\FlashSaleController::class, 'destroy'])->name('admin.flash-sales.destroy')->middleware(['auth', 'admin']);
Route::post('/flash-sale/add', [App\Http\Controllers\FlashSaleController::class, 'addToCart'])->name('flash-sale.add')->middleware('auth');

// Compare Routes
Route::prefix('compare')->middleware('auth')->group(function () {
    Route::post('/add', [App\Http\Controllers\CompareController::class, 'add'])->name('compare.add');
    Route::get('/', [App\Http\Controllers\CompareController::class, 'index'])->name('compare.index');
    Route::post('/remove', [App\Http\Controllers\CompareController::class, 'remove'])->name('compare.remove');
    Route::get('/clear', [App\Http\Controllers\CompareController::class, 'clear'])->name('compare.clear');
});

// Slider Routes (Admin only)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('sliders', App\Http\Controllers\SliderController::class);
    Route::post('/sliders/{slider}/toggle', [App\Http\Controllers\SliderController::class, 'toggle'])->name('sliders.toggle');
});