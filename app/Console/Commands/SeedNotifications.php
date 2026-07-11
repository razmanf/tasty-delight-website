<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Filament\Notifications\Notification;

class SeedNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed realistic promotional notifications for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        
        $promotions = [
            [
                'title' => 'Weekend Special! 🍕',
                'message' => 'Get 20% off all Pizzas this weekend. Use code PIZZA20 at checkout.',
                'icon' => 'heroicon-o-sparkles',
                'color' => 'success',
            ],
            [
                'title' => 'Free Delivery 🚚',
                'message' => 'Enjoy free delivery on all orders over $50 for the next 48 hours.',
                'icon' => 'heroicon-o-truck',
                'color' => 'primary',
            ],
            [
                'title' => 'Welcome to TastyDelight! 🎉',
                'message' => 'Thank you for joining us. Claim your first order discount.',
                'icon' => 'heroicon-o-gift',
                'color' => 'warning',
            ],
        ];

        foreach ($users as $user) {
            foreach ($promotions as $promo) {
                Notification::make()
                    ->title($promo['title'])
                    ->body($promo['message'])
                    ->icon($promo['icon'])
                    ->color($promo['color'])
                    ->sendToDatabase($user);
            }
        }

        $this->info('Successfully seeded promotional notifications for all users.');
    }
}
