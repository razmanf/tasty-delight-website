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
            'email' => 'admin@tastydelight.com',
            'password' => 'password', // plain text, model will hash it
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Regular User
        User::create([
            'name' => 'Test User',
            'email' => 'user@tastydelight.com',
            'password' => 'password',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
