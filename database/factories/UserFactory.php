<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
        public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // Default password for all test users
            'role' => $this->faker->randomElement(['admin', 'customer']),
        ];
    }

    // Admin state
    public function admin()
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    // Customer state
    public function customer()
    {
        return $this->state([
            'role' => 'customer',
        ]);
    }
}
