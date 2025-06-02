<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create 1 admin with fixed credentials
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@tastydelight.test',
            'password' => bcrypt('admin123'), // Easy to remember for testing
        ]);

        // Create 20 customers with realistic passwords
        User::factory()->count(20)->customer()->create();
    }
}