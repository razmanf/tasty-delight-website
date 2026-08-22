<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tastydelight.shop',
            'password' => 'password', // plain text, model will hash it
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Regular user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
