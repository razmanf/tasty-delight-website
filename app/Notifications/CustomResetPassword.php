<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class CustomResetPassword extends ResetPasswordNotification implements ShouldQueue
{
    use Queueable;
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password Notification - '.config('app.name'))
            ->view('emails.reset-password', [
                'url' => $this->resetUrl($notifiable),
            ]);
    }
}
