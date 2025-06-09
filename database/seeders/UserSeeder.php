<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tastydelight.com',
            'password' => Hash::make('password'), // Change to secure password in prod
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Regular User
        User::create([
            'name' => 'Test User',
            'email' => 'user@tastydelight.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}