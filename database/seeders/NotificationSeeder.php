<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admins = User::where('role', 'admin')->get();
        $users  = User::where('role', 'user')->get();
        $orders  = Order::with('user')->latest()->take(20)->get();
        $reviews = Review::with(['user', 'product'])->latest()->take(10)->get();

        // ── Admin Notifications ───────────────────────────────────────
        foreach ($admins as $admin) {
            // New order notifications
            foreach ($orders->take(10) as $order) {
                $admin->notifications()->create([
                    'id'              => Str::uuid(),
                    'type'            => 'App\Notifications\Admin\NewOrderNotification',
                    'notifiable_type' => User::class,
                    'notifiable_id'   => $admin->id,
                    'data'            => json_encode([
                        'title'   => '🛒 New order received',
                        'message' => "Order #{$order->id} from {$order->user?->name} — " . number_format($order->total_amount, 2),
                        'icon'    => 'heroicon-o-shopping-cart',
                        'color'   => 'success',
                    ]),
                    'read_at'    => $order->id % 3 === 0 ? now()->subHours(rand(1, 48)) : null,
                    'created_at' => $order->created_at ?? now()->subDays(rand(0, 30)),
                    'updated_at' => $order->created_at ?? now()->subDays(rand(0, 30)),
                ]);
            }

            // New customer notifications
            foreach ($users->take(5) as $user) {
                $admin->notifications()->create([
                    'id'              => Str::uuid(),
                    'type'            => 'App\Notifications\Admin\NewUserNotification',
                    'notifiable_type' => User::class,
                    'notifiable_id'   => $admin->id,
                    'data'            => json_encode([
                        'title'   => '👤 New customer registered',
                        'message' => "{$user->name} just joined TastyDelight",
                        'icon'    => 'heroicon-o-user-plus',
                        'color'   => 'info',
                    ]),
                    'read_at'    => rand(0, 1) ? now()->subHours(rand(1, 24)) : null,
                    'created_at' => $user->created_at ?? now()->subDays(rand(0, 20)),
                    'updated_at' => $user->created_at ?? now()->subDays(rand(0, 20)),
                ]);
            }

            // New review notifications
            foreach ($reviews->take(5) as $review) {
                $stars = str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating);
                $admin->notifications()->create([
                    'id'              => Str::uuid(),
                    'type'            => 'App\Notifications\Admin\NewReviewNotification',
                    'notifiable_type' => User::class,
                    'notifiable_id'   => $admin->id,
                    'data'            => json_encode([
                        'title'   => '⭐ New review submitted',
                        'message' => "{$review->user?->name} left a {$review->rating}-star review on {$review->product?->name} {$stars}",
                        'icon'    => 'heroicon-o-star',
                        'color'   => 'warning',
                    ]),
                    'read_at'    => rand(0, 1) ? now()->subHours(rand(1, 12)) : null,
                    'created_at' => $review->created_at ?? now()->subDays(rand(0, 14)),
                    'updated_at' => $review->created_at ?? now()->subDays(rand(0, 14)),
                ]);
            }
        }

        // ── User Notifications ────────────────────────────────────────
        $statusMessages = [
            'pending'          => ['title' => '⏳ Order received',          'message' => 'Your order has been received and is awaiting confirmation.'],
            'processing'       => ['title' => '👨‍🍳 Order being prepared',    'message' => 'Our kitchen is preparing your delicious order!'],
            'out_for_delivery' => ['title' => '🚴 On the way!',              'message' => 'Your order is out for delivery. Get ready!'],
            'delivered'        => ['title' => '🎉 Order delivered!',          'message' => 'Your order has been delivered. Enjoy your meal!'],
            'completed'        => ['title' => '✅ Order completed',           'message' => 'Thank you for choosing TastyDelight!'],
        ];

        foreach ($users as $user) {
            $userOrders = $orders->where('user_id', $user->id)->take(5);

            // Welcome notification
            $user->notifications()->create([
                'id'              => Str::uuid(),
                'type'            => 'App\Notifications\User\OrderStatusNotification',
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode([
                    'title'   => '👋 Welcome to TastyDelight!',
                    'message' => "Hi {$user->name}! We're thrilled to have you. Browse our menu and place your first order!",
                    'color'   => 'success',
                ]),
                'read_at'    => now()->subHours(rand(1, 72)),
                'created_at' => $user->created_at ?? now()->subDays(rand(10, 30)),
                'updated_at' => $user->created_at ?? now()->subDays(rand(10, 30)),
            ]);

            // Order status notifications
            foreach ($userOrders as $order) {
                $msg = $statusMessages[$order->status] ?? $statusMessages['completed'];
                $user->notifications()->create([
                    'id'              => Str::uuid(),
                    'type'            => 'App\Notifications\User\OrderStatusNotification',
                    'notifiable_type' => User::class,
                    'notifiable_id'   => $user->id,
                    'data'            => json_encode([
                        'title'    => $msg['title'],
                        'message'  => "Order #{$order->id}: " . $msg['message'],
                        'color'    => 'info',
                        'order_id' => $order->id,
                    ]),
                    'read_at'    => in_array($order->status, ['delivered', 'completed']) ? now()->subHours(rand(1, 48)) : null,
                    'created_at' => $order->created_at ?? now()->subDays(rand(0, 14)),
                    'updated_at' => $order->created_at ?? now()->subDays(rand(0, 14)),
                ]);
            }
        }
    }
}
