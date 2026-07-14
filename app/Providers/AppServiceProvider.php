<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\ReviewObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind custom LoginResponse for role-based post-login redirects
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for notifications
        Order::observe(OrderObserver::class);
        User::observe(UserObserver::class);
        Review::observe(ReviewObserver::class);

        // Force Filament to use modern interactive dropdowns instead of native browser select menus globally
        \Filament\Forms\Components\Select::configureUsing(function (\Filament\Forms\Components\Select $select): void {
            $select->native(false);
        });

        // Globally quote the item being deleted in all Delete Action modals
        \Filament\Actions\DeleteAction::configureUsing(function (\Filament\Actions\DeleteAction $action): void {
            $action->modalHeading(function () use ($action): string {
                return "Delete '{$action->getRecordTitle()}'";
            });
        });
    }
}