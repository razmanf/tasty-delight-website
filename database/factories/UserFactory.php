<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $commonPasswords = [
        'letmein', 'password1', 'qwerty', '123456', 'tasty123',
        'delight', 'foodie', 'yummy', 'burger', 'pizza123',
        'chicken', 'sweet', 'treat', 'yumyum', 'nomnom'
    ];

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt($this->generateRealisticPassword()),
            'role' => $this->faker->randomElement(['admin', 'customer']),
        ];
    }

    protected function generateRealisticPassword(): string
    {
        // 70% chance for a simple password, 30% for slightly more complex
        if ($this->faker->boolean(70)) {
            return $this->faker->randomElement($this->commonPasswords);
        }

        // Generate slightly more complex password (still readable)
        return Str::lower(Str::random(6)) . $this->faker->randomDigit();
    }

    public function admin()
    {
        return $this->state([
            'role' => 'admin',
            'password' => bcrypt('admin123'), // Fixed admin password for testing
        ]);
    }

    public function customer()
    {
        return $this->state([
            'role' => 'customer',
        ]);
    }
}