<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class UpdateProductPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $products = Product::with('category')->get();

        foreach ($products as $product) {
            $categoryName = $product->category->name ?? '';
            $price = 0;

            switch ($categoryName) {
                case 'Burgers':
                    $price = $faker->randomFloat(2, 8, 16);
                    break;
                case 'Pizzas':
                    $price = $faker->randomFloat(2, 12, 26);
                    break;
                case 'Desserts':
                    $price = $faker->randomFloat(2, 5, 12);
                    break;
                case 'Salads':
                    $price = $faker->randomFloat(2, 7, 14);
                    break;
                case 'Breakfast':
                    $price = $faker->randomFloat(2, 6, 15);
                    break;
                case 'Beverages':
                    $price = $faker->randomFloat(2, 3, 8);
                    break;
                default:
                    $price = $faker->randomFloat(2, 5, 20);
                    break;
            }

            $product->price = $price;
            $product->save();
        }
    }
}
