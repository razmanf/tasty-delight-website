<?php

use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Route;
use App\Models\{Cart, CartItem, Category, Order, Product, Review, User};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/employees', function () {
    return view('employees');
});

Route::get('/appointments', function () {
    return view('appointments');
});

Route::get('/test', function () {
    // Test User relationships
    $user = User::first();
    $userResults = [
        'user_orders' => $user->orders,
        'user_cart' => $user->cart,
        'user_reviews' => $user->reviews
    ];

    // Test Cart relationships
    $cart = Cart::first();
    $cartResults = [
        'cart_user' => $cart->user,
        'cart_items' => $cart->items,
        'cart_total' => $cart->total
    ];

    // Test CartItem relationships
    $cartItem = CartItem::first();
    $cartItemResults = [
        'cart_item_cart' => $cartItem->cart,
        'cart_item_product' => $cartItem->product
    ];

    // Test Category relationships
    $category = Category::first();
    $categoryResults = [
        'category_products' => $category->products
    ];

    // Test Product relationships
    $product = Product::first();
    $productResults = [
        'product_category' => $product->category,
        'product_orders' => $product->orders,
        'product_cart_items' => $product->cartItems,
        'product_reviews' => $product->reviews
    ];

    // Test Order relationships
    $order = Order::first();
    $orderResults = [
        'order_user' => $order->user,
        'order_products' => $order->products
    ];

    // Test Review relationships
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

Route::get('/test-email', function() {
    $user = User::first();
    
    if (!$user) {
        return 'No users found in database! Create a user first.';
    }

    // Reset verification status for testing
    $user->email_verified_at = null;
    $user->save();

    Notification::send($user, new VerifyEmail());
    return 'Test verification email sent to: ' . $user->email;
});

Route::get('/test-verification', function() {
    // Get or create a test user
    $user = User::firstOrCreate(
        ['email' => 'test@tastydelight.test'],
        [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'email_verified_at' => null // Ensure not verified
        ]
    );

    // Send verification notification
    $user->notify(new VerifyEmail());
    
    return "Verification email sent to: " . $user->email;
});

Route::get('/test-email', function() {
    Mail::raw('Hello World', function($message) {
        $message->to('test@example.com')->subject('Test');
    });
    return 'Email sent (check laravel.log)';
});
