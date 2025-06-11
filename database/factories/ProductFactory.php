<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $englishDescriptions = [
        'Burgers' => [
            'Juicy patty with fresh toppings on a toasted bun',
            'Premium quality burger with special sauce',
            '100% beef patty with melted cheese and crisp vegetables',
            'Handcrafted burger with artisan bread'
        ],
        'Pizzas' => [
            'Stone-baked pizza with fresh ingredients',
            'Classic pizza with homemade tomato sauce',
            'Thin crust pizza with premium toppings',
            'Wood-fired pizza with mozzarella cheese'
        ],
        'Desserts' => [
            'Homemade dessert with fresh ingredients',
            'Decadent sweet treat made daily',
            'Creamy dessert with a delicate texture',
            'Classic recipe passed down for generations'
        ],
        'Beverages' => [
            'Refreshing drink made with natural ingredients',
            'Ice-cold beverage perfect for any occasion',
            'Handcrafted drink with premium flavors',
            'Signature blend with a unique twist'
        ],
        'Salads' => [
            'Fresh greens with seasonal vegetables',
            'Crisp salad with house-made dressing',
            'Nutritious bowl packed with superfoods',
            'Garden-fresh ingredients with premium toppings'
        ],
        'Breakfast' => [
            'Hearty breakfast to start your day right',
            'Fluffy pancakes with maple syrup',
            'Classic breakfast made with care',
            'Morning favorite with premium ingredients'
        ]
    ];

    public function definition(): array
    {
        $category = Category::inRandomOrder()->first();

        // Map categories to sample images you downloaded
        $categoryImages = [
            'Burgers' => ['products/burger.jpg'],
            'Pizzas' => ['products/pizza.jpg'],
            'Desserts' => ['products/dessert.jpg'],  
            'Salads' => ['products/salad.jpg'],
            'Breakfast' => ['products/breakfast.jpg'],
        ];

        $images = $categoryImages[$category->name] ?? ['products/default.png'];
        
        return [
            'category_id' => $category->id,
            'name' => $this->generateProductName($category->name),
            'description' => $this->getCategoryDescription($category->name),
            'price' => $this->faker->randomFloat(2, 5, 50),
            'image' => $this->faker->randomElement($images),
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

    protected function getCategoryDescription(string $categoryName): string
    {
        $descriptions = $this->englishDescriptions[$categoryName] ?? [
            'Delicious menu item made with quality ingredients',
            'House specialty prepared with care',
            'Customer favorite with premium components'
        ];
        
        return $this->faker->randomElement($descriptions);
    }
}