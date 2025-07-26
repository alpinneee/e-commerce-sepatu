<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\ProfileController as LaravelProfileController;
use Illuminate\Support\Facades\Route;

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

// Customer Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// Product Routes
Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [CustomerProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{slug}', [CustomerProductController::class, 'category'])->name('products.category');
Route::get('/products/{slug}', [CustomerProductController::class, 'show'])->name('products.show');

// Category Routes
Route::get('/categories', [CustomerProductController::class, 'categories'])->name('categories.index');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Customer Profile Routes
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [CustomerProfileController::class, 'index'])->name('index');
    Route::put('/', [CustomerProfileController::class, 'update'])->name('update');
    Route::get('/edit', [CustomerProfileController::class, 'index'])->name('edit');
    Route::get('/change-password', [CustomerProfileController::class, 'showChangePasswordForm'])->name('change-password');
    Route::put('/change-password', [CustomerProfileController::class, 'changePassword'])->name('update-password');
    Route::get('/orders', [CustomerProfileController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [CustomerProfileController::class, 'showOrder'])->name('orders.show');
    Route::get('/addresses', [CustomerProfileController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [CustomerProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [CustomerProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [CustomerProfileController::class, 'deleteAddress'])->name('addresses.delete');
});

// Orders Index Route (for compatibility with menu/profile links)
Route::middleware(['auth'])->get('/orders', function () {
    return redirect()->route('profile.orders');
})->name('orders.index');

// Wishlist Routes
Route::middleware(['auth'])->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/add', [WishlistController::class, 'add'])->name('add');
    Route::post('/remove', [WishlistController::class, 'remove'])->name('remove');
    Route::post('/clear', [WishlistController::class, 'clear'])->name('clear');
    Route::post('/move-to-cart', [WishlistController::class, 'moveToCart'])->name('move-to-cart');
    Route::get('/check', [WishlistController::class, 'check'])->name('check');
});

// Wishlist Index Route (for compatibility with menu/profile links)
Route::middleware(['auth'])->get('/wishlist', function () {
    return redirect()->route('wishlist.index');
})->name('wishlist.index');

// Review Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/products/{product}/review', [CustomerProductController::class, 'storeReview'])->name('products.review');
});

// Dashboard Route (for auth redirect compatibility)
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Product Routes
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    
    // Admin Category Routes
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Admin Order Routes
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store', 'destroy']);
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    
    // Admin User Routes (placeholder)
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');
    
    // Admin Reports Routes (placeholder)
    Route::get('/reports', function () {
        return view('admin.reports.index');
    })->name('reports');
    
    // Admin Settings Routes (placeholder)
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings');
});

// Laravel Breeze Routes
require __DIR__.'/auth.php';
