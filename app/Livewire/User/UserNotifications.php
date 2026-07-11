<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserNotifications extends Component
{
    public function markAllRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function markRead(string $id): void
    {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
    }

    public function deleteNotification(string $id): void
    {
        Auth::user()->notifications()->where('id', $id)->delete();
    }

    public function render()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);

        return view('livewire.user.user-notifications', compact('notifications'))
            ->layout('layouts.user');
    }
}
