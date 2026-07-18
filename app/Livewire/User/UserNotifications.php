<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserNotifications extends Component
{
    public function markAllRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->redirect(route('user.notifications'), navigate: true);
    }

    public function markRead(string $id): void
    {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        $this->redirect(route('user.notifications'), navigate: true);
    }

    public function deleteNotification(string $id): void
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        $this->redirect(route('user.notifications'), navigate: true);
    }

    public function render()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);

        return view('livewire.user.user-notifications', compact('notifications'))
            ->layout('layouts.user');
    }
}
