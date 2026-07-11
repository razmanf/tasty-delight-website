<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Admin\NewOrderNotification;
use App\Notifications\User\OrderStatusNotification;

class OrderObserver
{
    public function created(Order $order): void
    {
        // Notify all admins of new order
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewOrderNotification($order));
    }

    public function updated(Order $order): void
    {
        // Notify the order's customer when status changes
        if ($order->isDirty('status') && $order->user) {
            $order->user->notify(new OrderStatusNotification($order));
        }
    }
}
