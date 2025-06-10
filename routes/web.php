<?php

use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Route;
use App\Models\{Cart, CartItem, Category, Order, Product, Review, User};
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\LogoutController;
use App\Livewire\UserDashboard;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Livewire\Admin\AdminDashboard;



// Welcome page
Route::get('/', function () {
    return view('auth.register');
});

// Custom Login Routes
Route::get('/user/login', [UserLoginController::class, 'showLoginForm'])->name('user.login');
Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');


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

// Logout route
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Privacy policy
Route::get('/privacy', function () {
    $policy = File::exists(storage_path('app/policy.html'))
        ? File::get(storage_path('app/policy.html'))
        : 'Privacy Policy not found.';

    return view('policy', compact('policy'));
})->name('privacy');

//Terms and conditions

Route::get('/terms', function () {
    $terms = File::exists(storage_path('app/terms.html'))
        ? File::get(storage_path('app/terms.html'))
        : 'Terms of Service not found.';

    return view('terms', compact('terms'));
})->name('terms');

// Default Jetstream login GET route
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest'])
    ->name('login');

// Default Jetstream login POST route
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest']);

    
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'), // in practice this is `web`
    'verified',
    'admin', // or 'user'
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::resource('reviews', ReviewController::class);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'user'
])->group(function () {
    Route::get('/user/dashboard', UserDashboard::class)->name('user.dashboard');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->name('dashboard');
    