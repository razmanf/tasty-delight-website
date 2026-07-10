<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\FavoriteController;
use Illuminate\Http\Request;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Sanctum-protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes (plural)
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::get('/profile', [UserController::class, 'profile']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Carts (plural)
    Route::apiResource('carts', CartController::class);

    // Categories (plural)
    Route::apiResource('categories', CategoryController::class);

    // Orders (plural)
    Route::apiResource('orders', OrderController::class);

    // Products (plural)
    Route::apiResource('products', ProductController::class);

    // Reviews (plural)
    Route::apiResource('reviews', ReviewController::class);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{productId}', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{productId}', [FavoriteController::class, 'destroy']);
});
