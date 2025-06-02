<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $category = Category::inRandomOrder()->first();
        
        return [
            'category_id' => $category->id,
            'name' => $this->generateProductName($category->name),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 50),
            'image' => 'products/default.jpg'
        ];
    }

    protected function generateProductName(string $categoryName): string
    {
        $types = [
            'Burgers' => ['Cheese', 'Bacon', 'Chicken', 'Veggie'],
            'Pizzas' => ['Margherita', 'Pepperoni', 'Hawaiian', 'Vegetarian'],
            'Desserts' => ['Chocolate', 'Vanilla', 'Strawberry', 'Caramel'],
            'Beverages' => ['Iced', 'Hot', 'Sparkling', 'Frozen'],
            'Salads' => ['Caesar', 'Greek', 'Garden', 'Cobb'],
            'Breakfast' => ['Pancake', 'Waffle', 'Omelette', 'French Toast']
        ];

        $prefixes = $types[$categoryName] ?? ['Deluxe', 'Special', 'Gourmet'];
        
        return $this->faker->randomElement($prefixes).' '.$categoryName;
    }
}
