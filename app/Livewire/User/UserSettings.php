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
    public string $passwordForDeletion = '';

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
            $this->reset('photo');
        }

        $user->save();
        $this->dispatch('profile-updated');
        session()->flash('success', 'Profile updated successfully!');
    }

    public function deleteProfilePhoto(): void
    {
        $user = Auth::user();
        
        if ($user->profile_photo_path) {
            $user->deleteProfilePhoto();
            
            // Dispatch event to update the topbar avatars without page reload
            $this->dispatch('admin-avatar-updated', url: $user->getFilamentAvatarUrl());
            $this->dispatch('profile-updated'); 
            
            session()->flash('success', 'Profile photo removed successfully!');
        }
    }

    public function savePassword(): void
    {
        $this->validate([
            'currentPassword'         => 'required',
            'newPassword'             => ['required', 'different:currentPassword', 'same:newPasswordConfirmation', Password::defaults()],
        ]);

        if (!Hash::check($this->currentPassword, Auth::user()->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        $user = Auth::user();
        $user->update(['password' => Hash::make($this->newPassword)]);
        
        // Update the password hash in the session so AuthenticateSession doesn't log the user out
        if (request()->hasSession()) {
            request()->session()->put([
                'password_hash_'.Auth::getDefaultDriver() => $user->getAuthPassword(),
            ]);
        }

        $this->reset('currentPassword', 'newPassword', 'newPasswordConfirmation');
        session()->flash('success', 'Password changed successfully!');
    }

    public function deleteAccount(): void
    {
        $user = Auth::user();

        $this->validate([
            'passwordForDeletion' => 'required',
        ]);

        if (!Hash::check($this->passwordForDeletion, $user->password)) {
            $this->addError('passwordForDeletion', 'The password you entered is incorrect.');
            return;
        }

        Auth::guard('web')->logout();
        $user->delete();
        
        session()->invalidate();
        session()->regenerateToken();
        
        $this->redirect(route('register'));
    }

    public function render()
    {
        return view('livewire.user.user-settings', ['user' => Auth::user()])
            ->layout('layouts.user');
    }
}
