<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have users and products
        if (User::count() === 0 || Product::count() === 0) {
            $this->command->error('Please seed users and products first!');
            return;
        }
    
        // Create realistic reviews with proper rating distribution
        Review::factory()
            ->count(80) // 80% normal distribution
            ->create([
                'rating' => fake()->numberBetween(Review::MIN_RATING, Review::MAX_RATING)
            ]);
            
        // Create some 5-star reviews (10%)
        Review::factory()
            ->count(10)
            ->create([
                'rating' => Review::MAX_RATING
            ]);
            
        // Create some 1-star reviews (10%)
        Review::factory()
            ->count(10)
            ->create([
                'rating' => Review::MIN_RATING
            ]);
            
        $this->command->info('Successfully seeded reviews with realistic rating distribution!');
    }
}