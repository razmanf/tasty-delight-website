<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    protected $foodReviews = [
        'positive' => [
            'Absolutely delicious! The flavors were amazing.',
            'Best [PRODUCT] I\'ve ever had - will order again!',
            'Fresh ingredients and perfect seasoning. 10/10!',
            'Mouthwatering! Tasted just like homemade.',
            'Delivery was fast and food arrived piping hot!'
        ],
        'neutral' => [
            'Good, but could use more seasoning.',
            'Decent [PRODUCT], though a bit overpriced.',
            'Tasty but the portion size was smaller than expected.',
            'It was okay - nothing special but not bad either.',
            'Average meal, delivery took longer than estimated.'
        ],
        'negative' => [
            'Overcooked and bland - very disappointed.',
            '[PRODUCT] arrived cold and soggy.',
            'Tasted nothing like the description, would not recommend.',
            'Found a hair in my food - unacceptable!',
            'Order was wrong and customer service was unhelpful.'
        ]
    ];

    public function run(): void
    {
        if (User::count() === 0 || Product::count() === 0) {
            $this->command->error('Please seed users and products first!');
            return;
        }

        $products = Product::all();

        // Create 80 random reviews (1-5 stars)
        Review::factory()
            ->count(80)
            ->create([
                'rating' => fake()->numberBetween(Review::MIN_RATING, Review::MAX_RATING),
                'comment' => function() use ($products) {
                    $product = $products->random();
                    return $this->getFoodReview($product->name);
                }
            ]);

        // Create 10 perfect reviews (5 stars)
        Review::factory()
            ->count(10)
            ->create([
                'rating' => Review::MAX_RATING,
                'comment' => function() use ($products) {
                    $product = $products->random();
                    return str_replace(
                        '[PRODUCT]', 
                        $product->name, 
                        fake()->randomElement($this->foodReviews['positive'])
                    );
                }
            ]);

        // Create 10 bad reviews (1 star)
        Review::factory()
            ->count(10)
            ->create([
                'rating' => Review::MIN_RATING,
                'comment' => function() use ($products) {
                    $product = $products->random();
                    return str_replace(
                        '[PRODUCT]', 
                        $product->name, 
                        fake()->randomElement($this->foodReviews['negative'])
                    );
                }
            ]);

        $this->command->info('Successfully seeded TastyDelight food reviews!');
    }

    protected function getFoodReview(string $productName): string
    {
        $rating = fake()->numberBetween(Review::MIN_RATING, Review::MAX_RATING);
        $reviewPool = match(true) {
            $rating >= 4 => $this->foodReviews['positive'],
            $rating <= 2 => $this->foodReviews['negative'],
            default => $this->foodReviews['neutral']
        };

        return str_replace(
            '[PRODUCT]', 
            $productName, 
            fake()->randomElement($reviewPool)
        );
    }
}