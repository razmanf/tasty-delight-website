<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LogoutButton extends Component
{
    public function logout()
    {
        $userType = Auth::user()?->user_type;

        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        if ($userType === 'admin') {
            return redirect('/admin/login');
        }

        return redirect('/user/login');
    }

    public function render()
    {
        return view('livewire.logout-button');
    }
}
