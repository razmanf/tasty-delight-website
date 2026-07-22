<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;

class ProfileDropdown extends Component
{
    #[On('profile-updated')]
    public function render()
    {
        return view('livewire.user.profile-dropdown');
    }
}
