<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create 30 products
        Product::factory()->count(30)->create();

        // Define image mappings by category name
        $categoryImageMap = [
            'Burgers'    => 'products/burger.jpg',
            'Pizzas'     => 'products/pizza.jpg',
            'Desserts'   => 'products/dessert.jpg',
            'Beverages'  => 'products/coffee.jpg',
            'Salads'     => 'products/salad.jpg',
            'Breakfast'  => 'products/breakfast.jpg',
        ];

        // Update image field based on category
        foreach (Product::with('category')->get() as $product) {
            $categoryName = $product->category->name ?? null;

            if ($categoryName && isset($categoryImageMap[$categoryName])) {
                $product->image = $categoryImageMap[$categoryName];
                $product->save();
            }
        }
    }
}