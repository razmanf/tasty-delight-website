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
    }
}