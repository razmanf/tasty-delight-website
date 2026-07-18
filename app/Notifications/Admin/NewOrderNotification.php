<?php

namespace App\Notifications\Admin;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title'   => '🛒 New order received',
            'message' => "Order #{$this->order->id} from {$this->order->user?->name} — " . number_format($this->order->total_amount, 2),
            'icon'    => 'heroicon-o-shopping-cart',
            'color'   => 'success',
            'url'     => route('filament.admin.resources.orders.edit', $this->order),
        ];
    }
}
