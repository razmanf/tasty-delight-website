<?php

namespace App\Livewire\User;

use Livewire\Component;

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class NotificationBell extends Component
{
    #[On('notifications-updated')]
    public function refreshNotifications(): void
    {
        // Simply listening to the event triggers a re-render
    }

    #[Computed]
    public function unreadCount()
    {
        return auth()->user()->unreadNotifications->count();
    }

    #[Computed]
    public function recentNotifs()
    {
        return auth()->user()->notifications()->take(3)->get();
    }

    public function render()
    {
        return view('livewire.user.notification-bell');
    }
}
