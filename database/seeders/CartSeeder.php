<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create carts for all customers
        User::where('role', 'customer')->each(function ($user) {
            Cart::factory()->create(['user_id' => $user->id]);
        });
    }
}
