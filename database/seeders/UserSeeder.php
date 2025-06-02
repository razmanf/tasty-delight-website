<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1 admin
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@tastydelight.test',
        ]);

        // Create 20 customers
        User::factory()->count(20)->customer()->create();
    }
}
