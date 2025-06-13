<?php

use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/product/{product}', [ProductApiController::class, 'show']);

// Social Login API Routes
Route::post('/auth/social', [App\Http\Controllers\Api\SocialAuthApiController::class, 'socialLogin']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    // Cart routes
    Route::get('/cart', [CartApiController::class, 'index']);
    Route::post('/cart', [CartApiController::class, 'store']);
    Route::put('/cart/{id}', [CartApiController::class, 'update']);
    Route::delete('/cart/{id}', [CartApiController::class, 'destroy']);

    // Order routes
    Route::get('/user/orders', [OrderApiController::class, 'index']);
    Route::post('/checkout', [OrderApiController::class, 'store']);
    Route::get('/user/orders/{order}', [OrderApiController::class, 'show']);
});
