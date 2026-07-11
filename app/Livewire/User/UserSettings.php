<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserSettings extends Component
{
    use WithFileUploads;

    public string $activeTab = 'profile';
    public string $name = '';
    public $photo;

    // Password
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
    }

    public function saveProfile(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $this->name;

        if ($this->photo) {
            $user->updateProfilePhoto($this->photo);
        }

        $user->save();
        session()->flash('success', 'Profile updated successfully!');
    }

    public function savePassword(): void
    {
        $this->validate([
            'currentPassword'         => 'required',
            'newPassword'             => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($this->currentPassword, Auth::user()->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        Auth::user()->update(['password' => Hash::make($this->newPassword)]);
        $this->reset('currentPassword', 'newPassword', 'newPasswordConfirmation');
        session()->flash('success', 'Password changed successfully!');
    }

    public function render()
    {
        return view('livewire.user.user-settings', ['user' => Auth::user()])
            ->layout('layouts.user');
    }
}
