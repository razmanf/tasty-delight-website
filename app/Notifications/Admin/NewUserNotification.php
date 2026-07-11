<?php

namespace App\Notifications\Admin;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    public function __construct(public User $newUser) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title'   => '👤 New customer registered',
            'message' => "{$this->newUser->name} just joined TastyDelight",
            'icon'    => 'heroicon-o-user-plus',
            'color'   => 'info',
            'url'     => route('filament.admin.resources.useedit', $this->newUser),
        ];
    }
}
