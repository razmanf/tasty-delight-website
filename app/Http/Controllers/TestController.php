<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Cart, CartItem, Category, Order, Product, Review, User};
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmail;

class TestController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Test works']);
    }

    public function testRelationships()
    {
        $user = User::first();
        $userResults = [
            'user_orders' => $user ? $user->orders : null,
            'user_cart' => $user ? $user->cart : null,
            'user_reviews' => $user ? $user->reviews : null
        ];

        $cart = Cart::first();
        $cartResults = [
            'cart_user' => $cart ? $cart->user : null,
            'cart_items' => $cart ? $cart->items : null,
            'cart_total' => $cart ? $cart->total : null
        ];

        $cartItem = CartItem::first();
        $cartItemResults = [
            'cart_item_cart' => $cartItem ? $cartItem->cart : null,
            'cart_item_product' => $cartItem ? $cartItem->product : null
        ];

        $category = Category::first();
        $categoryResults = [
            'category_products' => $category ? $category->products : null
        ];

        $product = Product::first();
        $productResults = [
            'product_category' => $product ? $product->category : null,
            'product_orders' => $product ? $product->orders : null,
            'product_cart_items' => $product ? $product->cartItems : null,
            'product_reviews' => $product ? $product->reviews : null
        ];

        $order = Order::first();
        $orderResults = [
            'order_user' => $order ? $order->user : null,
            'order_products' => $order ? $order->products : null
        ];

        $review = Review::first();
        $reviewResults = [
            'review_user' => $review ? $review->user : null,
            'review_product' => $review ? $review->product : null
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
    }

    public function testEmail()
    {
        $user = User::first();

        if (!$user) {
            return 'No users found in database! Create a user first.';
        }

        $user->email_verified_at = null;
        $user->save();

        Notification::send($user, new VerifyEmail());
        return 'Test verification email sent to: ' . $user->email;
    }

    public function testVerification()
    {
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
    }
}
