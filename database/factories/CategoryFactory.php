<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected static $categories = [
        'Burgers', 'Pizzas', 'Desserts',
        'Beverages', 'Salads', 'Breakfast'
    ];

    protected static $currentIndex = 0;

    public function definition(): array
    {
        // Get next category in sequence
        $category = self::$categories[self::$currentIndex % count(self::$categories)];
        self::$currentIndex++;

        return [
            'name' => $category,
            'description' => $this->faker->sentence(),
        ];
    }
}
