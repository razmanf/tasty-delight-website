<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\User;
use App\Notifications\Admin\NewReviewNotification;
use Illuminate\Support\Facades\Notification;

class ReviewObserver
{
    public function created(Review $review): void
    {
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewReviewNotification($review));
    }
}
