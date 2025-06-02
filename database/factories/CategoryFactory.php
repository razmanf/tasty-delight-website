<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected static $categories = [
        'Burgers', 'Pizzas', 'Desserts',
        'Beverages', 'Salads', 'Breakfast'
    ];

    protected static $englishDescriptions = [
        'Burgers' => 'Juicy, flavorful burgers with premium ingredients and fresh toppings',
        'Pizzas' => 'Authentic pizzas with hand-tossed dough and quality toppings',
        'Desserts' => 'Delicious sweet treats and baked goods made fresh daily',
        'Beverages' => 'Refreshing drinks including sodas, juices, and specialty coffees',
        'Salads' => 'Fresh, crisp salads with healthy ingredients and homemade dressings',
        'Breakfast' => 'Hearty morning meals to start your day right'
    ];

    protected static $currentIndex = 0;

    public function definition(): array
    {
        $category = self::$categories[self::$currentIndex % count(self::$categories)];
        self::$currentIndex++;

        return [
            'name' => $category,
            'description' => self::$englishDescriptions[$category] ?? 'Delicious menu items made with quality ingredients',
        ];
    }
}