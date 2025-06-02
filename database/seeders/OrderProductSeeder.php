<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing pivot data
        DB::table('order_product')->truncate();

        // Get all orders and products
        $orders = Order::all();
        $products = Product::all();

        // Ensure we have data to work with
        if ($orders->isEmpty() || $products->isEmpty()) {
            $this->command->error('No orders or products found! Run OrderSeeder and ProductSeeder first.');
            return;
        }

        DB::transaction(function () use ($orders, $products) {
            $orders->each(function ($order) use ($products) {
                // Get 1-5 unique random products
                $selectedProducts = $products->random(rand(1, 5))->unique();

                $orderProducts = $selectedProducts->mapWithKeys(function ($product) {
                    return [
                        $product->id => [
                            'quantity' => rand(1, 3),
                            'price' => $product->price,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    ];
                });

                // Attach with timestamps
                $order->products()->attach($orderProducts);

                // Update order total
                $order->update([
                    'total_amount' => $order->products->sum(
                        fn($product) => $product->pivot->quantity * $product->pivot->price
                    )
                ]);
            });
        });

        $this->command->info('Seeded order_product relationships successfully!');
    }
}