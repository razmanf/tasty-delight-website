<?php

namespace Tests\Feature;

use App\Livewire\User\UserSettings;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_change_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct_password'),
        ]);

        Livewire::actingAs($user)
            ->test(UserSettings::class)
            ->set('currentPassword', 'wrong_password')
            ->set('newPassword', 'new_password_123')
            ->set('newPasswordConfirmation', 'new_password_123')
            ->call('savePassword')
            ->assertHasErrors(['currentPassword']);

        $this->assertTrue(Hash::check('correct_password', $user->fresh()->password));
    }

    public function test_user_cannot_change_password_if_new_passwords_do_not_match(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct_password'),
        ]);

        Livewire::actingAs($user)
            ->test(UserSettings::class)
            ->set('currentPassword', 'correct_password')
            ->set('newPassword', 'new_password_123')
            ->set('newPasswordConfirmation', 'mismatched_password')
            ->call('savePassword')
            ->assertHasErrors(['newPassword']);

        $this->assertTrue(Hash::check('correct_password', $user->fresh()->password));
    }

    public function test_user_cannot_reuse_current_password_as_new_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct_password'),
        ]);

        Livewire::actingAs($user)
            ->test(UserSettings::class)
            ->set('currentPassword', 'correct_password')
            ->set('newPassword', 'correct_password')
            ->set('newPasswordConfirmation', 'correct_password')
            ->call('savePassword')
            ->assertHasErrors(['newPassword']);

        $this->assertTrue(Hash::check('correct_password', $user->fresh()->password));
    }

    public function test_user_can_successfully_change_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct_password'),
        ]);

        Livewire::actingAs($user)
            ->test(UserSettings::class)
            ->set('currentPassword', 'correct_password')
            ->set('newPassword', 'new_password_123')
            ->set('newPasswordConfirmation', 'new_password_123')
            ->call('savePassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('new_password_123', $user->fresh()->password));
    }
}
