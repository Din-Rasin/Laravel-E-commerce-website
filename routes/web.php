<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

// Language Switcher Route
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Home Page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Social Login Routes
Route::get('/auth/{provider}', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToProvider'])->name('social.login');
Route::get('/auth/{provider}/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleProviderCallback']);

// Test route for OAuth configuration
Route::get('/auth/test-config', function() {
    return response()->json([
        'google' => [
            'client_id' => config('services.google.client_id'),
            'redirect' => config('services.google.redirect'),
        ],
        'facebook' => [
            'client_id' => config('services.facebook.client_id'),
            'redirect' => config('services.facebook.redirect'),
        ],
        'app_url' => config('app.url'),
    ]);
});

// Admin Auth Routes
Route::prefix('admin')->group(function () {
    // Guest routes (only accessible when not logged in)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'login']);
    });

    // Logout route (requires authentication)
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::get('/products/restore/{id}', [App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/force-delete/{id}', [App\Http\Controllers\Admin\ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
});

// API Routes for Postman Testing
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'apiIndex'])->name('products.index');
    Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'apiIndex'])->name('categories.index');
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'apiIndex'])->name('orders.index');
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'apiIndex'])->name('users.index');
});

// User Routes
Route::middleware(['auth', 'user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'index'])->name('user.profile');
    Route::put('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/orders', [App\Http\Controllers\User\OrderController::class, 'index'])->name('user.orders');
    Route::get('/orders/{token}', [App\Http\Controllers\User\OrderController::class, 'show'])->name('user.orders.show');
});

// Product Routes
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

// Cart Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product:slug}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartItem}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

    // Checkout Routes
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{token}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
});
