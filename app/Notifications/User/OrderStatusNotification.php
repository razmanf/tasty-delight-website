<?php

namespace App\Notifications\User;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $messages = [
            'pending'          => ['title' => '⏳ Order received', 'message' => "Your order #{$this->order->id} has been received and is awaiting confirmation.", 'color' => 'warning'],
            'processing'       => ['title' => '👨‍🍳 Order being prepared', 'message' => "Your order #{$this->order->id} is being prepared by our kitchen!", 'color' => 'primary'],
            'out_for_delivery' => ['title' => '🚴 On the way!', 'message' => "Your order #{$this->order->id} is out for delivery. Get ready!", 'color' => 'info'],
            'delivered'        => ['title' => '🎉 Order delivered!', 'message' => "Your order #{$this->order->id} has been delivered. Enjoy your meal!", 'color' => 'success'],
            'completed'        => ['title' => '✅ Order completed', 'message' => "Your order #{$this->order->id} is complete. Thank you for choosing TastyDelight!", 'color' => 'success'],
            'cancelled'        => ['title' => '❌ Order cancelled', 'message' => "Your order #{$this->order->id} has been cancelled. Contact support if you need help.", 'color' => 'danger'],
        ];

        $info = $messages[$this->order->status] ?? [
            'title'   => '📦 Order update',
            'message' => "Your order #{$this->order->id} status has been updated to: " . ucfirst($this->order->status),
            'color'   => 'gray',
        ];

        return [
            'title'    => $info['title'],
            'message'  => $info['message'],
            'color'    => $info['color'],
            'order_id' => $this->order->id,
            'url'      => route('user.orders'),
        ];
    }
}
