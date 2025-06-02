<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing cart items
        CartItem::truncate();

        // Get all carts and products
        $carts = Cart::all();
        $products = Product::all();

        // Add items to each cart
        $carts->each(function ($cart) use ($products) {
            // Create 1-5 random cart items per cart
            $itemsCount = rand(1, 5);
            $selectedProducts = $products->random($itemsCount);

            $selectedProducts->each(function ($product) use ($cart) {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3) // Random quantity between 1-3
                ]);
            });

            // Calculate and update cart total
            $cart->update([
                'total' => $cart->items->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                })
            ]);
        });
    }
}