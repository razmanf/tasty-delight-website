<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_register_with_9_digit_contact_number(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'contact_number' => '012345678', // 9 digits
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'user',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('contact_number');
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
    }

    public function test_user_cannot_register_with_contact_number_not_starting_with_0(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'contact_number' => '1234567890', // 10 digits but starts with 1
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'user',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('contact_number');
        $this->assertDatabaseMissing('users', ['email' => 'test2@example.com']);
    }

    public function test_user_can_register_with_valid_10_digit_contact_number(): void
    {
        $response = $this->post('/register', [
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'contact_number' => '0987654321', // 10 digits, starts with 0
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'user',
            'terms' => true,
        ]);

        $this->assertAuthenticated();
        
        $this->assertDatabaseHas('users', [
            'email' => 'valid@example.com',
            'contact_number' => '0987654321',
        ]);
    }

    public function test_admin_can_register_with_valid_10_digit_contact_number(): void
    {
        $response = $this->post('/register', [
            'name' => 'Valid Admin',
            'email' => 'admin_test@example.com',
            'contact_number' => '0777123456',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'terms' => true,
        ]);

        $this->assertAuthenticated();
        
        $this->assertDatabaseHas('users', [
            'email' => 'admin_test@example.com',
            'contact_number' => '0777123456',
            'role' => 'admin',
        ]);
    }

    public function test_user_can_login_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }
}
