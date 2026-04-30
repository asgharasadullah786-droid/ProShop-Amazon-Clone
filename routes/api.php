<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ApiDocsController;

// Test Route
Route::get('/test', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'ProShop API is working!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// API Home
Route::get('/', function() {
    return response()->json([
        'app' => 'ProShop API',
        'version' => '1.0.0',
        'documentation' => url('/api/docs'),
        'status' => 'online',
    ]);
});

// API Documentation
Route::get('/docs', [ApiDocsController::class, 'index']);

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/flash-sales', [ProductController::class, 'flashSales']);

// Protected Routes (Require Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'remove']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    Route::get('/addresses', [ProfileController::class, 'addresses']);
    Route::post('/addresses', [ProfileController::class, 'addAddress']);
    Route::delete('/addresses/{id}', [ProfileController::class, 'deleteAddress']);
    Route::get('/wishlist', [ProfileController::class, 'wishlist']);
    Route::post('/wishlist/add/{id}', [ProfileController::class, 'addToWishlist']);
    Route::delete('/wishlist/{id}', [ProfileController::class, 'removeFromWishlist']);
    Route::get('/wallet', [ProfileController::class, 'wallet']);
    Route::post('/wallet/add-balance', [ProfileController::class, 'addWalletBalance']);
});