<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have users and products first
        if (User::count() === 0 || Product::count() === 0) {
            $this->command->error('Please run UserSeeder and ProductSeeder first!');
            return;
        }

        DB::transaction(function () {
            Order::factory()
                ->count(50)
                ->create()
                ->each(function ($order) {
                    // Get 1-5 random products with quantities
                    $products = Product::query()
                        ->inRandomOrder()
                        ->take(rand(1, 5))
                        ->get()
                        ->mapWithKeys(fn ($product) => [
                            $product->id => [
                                'quantity' => rand(1, 3),
                                'price' => $product->price,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        ]);

                    // Attach with timestamps
                    $order->products()->attach($products);

                    // Calculate total
                    $total = $order->products->sum(
                        fn ($product) => $product->pivot->quantity * $product->pivot->price
                    );

                    $order->update(['total_amount' => $total]);
                });
        });

        $this->command->info('Seeded 50 orders with products!');
    }
}