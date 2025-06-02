<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Create exactly 6 categories using the factory
        Category::factory()
            ->count(6) // Matches number of categories in factory
            ->create();
    }
}
