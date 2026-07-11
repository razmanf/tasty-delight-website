<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\Admin\NewUserNotification;
use Illuminate\Support\Facades\Notification;

class UserObserver
{
    public function created(User $user): void
    {
        // Only notify admins when a new non-admin registers
        if ($user->role !== 'admin') {
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new NewUserNotification($user));
        }
    }
}
