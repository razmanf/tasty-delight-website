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

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Sanctum-protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes (plural)
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::get('/profile', [UserController::class, 'profile']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cart (singular)
    Route::apiResource('cart', CartController::class);

    // Category (singular)
    Route::apiResource('category', CategoryController::class);

    // Order (singular)
    Route::apiResource('order', OrderController::class);

    // Product (singular)
    Route::apiResource('product', ProductController::class);

    // Review (singular)
    Route::apiResource('review', ReviewController::class);
});
