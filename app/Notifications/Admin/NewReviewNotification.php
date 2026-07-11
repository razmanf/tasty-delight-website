<?php

namespace App\Notifications\Admin;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(public Review $review) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $stars = str_repeat('★', $this->review->rating) . str_repeat('☆', 5 - $this->review->rating);

        return [
            'title'   => '⭐ New review submitted',
            'message' => "{$this->review->user?->name} left a {$this->review->rating}-star review on {$this->review->product?->name} {$stars}",
            'icon'    => 'heroicon-o-star',
            'color'   => 'warning',
            'url'     => route('filament.admin.resources.reviews.edit', $this->review),
        ];
    }
}
