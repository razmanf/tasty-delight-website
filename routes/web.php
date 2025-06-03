<?php

use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Route;
use App\Models\{Cart, CartItem, Category, Order, Product, Review, User};
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\LogoutController;
use App\Livewire\AdminDashboard;
use App\Livewire\UserDashboard;
use App\Http\Controllers\DashboardController;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Custom Login Routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);

Route::get('/user/login', [UserLoginController::class, 'showLoginForm'])->name('user.login');
Route::post('/user/login', [UserLoginController::class, 'login']);

// Demo views
Route::get('/employees', function () {
    return view('employees');
});

Route::get('/appointments', function () {
    return view('appointments');
});

// Relationship test route
Route::get('/test-relationships', function () {
    $user = User::first();
    $userResults = [
        'user_orders' => $user->orders,
        'user_cart' => $user->cart,
        'user_reviews' => $user->reviews
    ];

    $cart = Cart::first();
    $cartResults = [
        'cart_user' => $cart->user,
        'cart_items' => $cart->items,
        'cart_total' => $cart->total
    ];

    $cartItem = CartItem::first();
    $cartItemResults = [
        'cart_item_cart' => $cartItem->cart,
        'cart_item_product' => $cartItem->product
    ];

    $category = Category::first();
    $categoryResults = [
        'category_products' => $category->products
    ];

    $product = Product::first();
    $productResults = [
        'product_category' => $product->category,
        'product_orders' => $product->orders,
        'product_cart_items' => $product->cartItems,
        'product_reviews' => $product->reviews
    ];

    $order = Order::first();
    $orderResults = [
        'order_user' => $order->user,
        'order_products' => $order->products
    ];

    $review = Review::first();
    $reviewResults = [
        'review_user' => $review->user,
        'review_product' => $review->product
    ];

    return [
        'user_relationships' => $userResults,
        'cart_relationships' => $cartResults,
        'cart_item_relationships' => $cartItemResults,
        'category_relationships' => $categoryResults,
        'product_relationships' => $productResults,
        'order_relationships' => $orderResults,
        'review_relationships' => $reviewResults
    ];
});

// Email testing routes
Route::get('/test-email', function () {
    $user = User::first();
    
    if (!$user) {
        return 'No users found in database! Create a user first.';
    }

    $user->email_verified_at = null;
    $user->save();

    Notification::send($user, new VerifyEmail());
    return 'Test verification email sent to: ' . $user->email;
});

Route::get('/test-verification', function () {
    $user = User::firstOrCreate(
        ['email' => 'test@tastydelight.test'],
        [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'email_verified_at' => null
        ]
    );

    $user->notify(new VerifyEmail());
    
    return "Verification email sent to: " . $user->email;
});

// Jetstream-protected routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Logout route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Dashboards (middleware included earlier)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
});

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/user/dashboard', UserDashboard::class)->name('user.dashboard');
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // User dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard.user-dashboard');  // points to resources/views/dashboard/user-dashboard.blade.php
    })->name('dashboard')->middleware('user');

    // Admin dashboard route
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin-dashboard'); // points to resources/views/dashboard/admin-dashboard.blade.php
    })->name('admin.dashboard')->middleware('admin');
});
