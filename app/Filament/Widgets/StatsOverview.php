<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Order::whereIn('status', ['completed', 'delivered'])
            ->sum('total_amount');

        $ordersToday = Order::whereDate('created_at', today())->count();
        $ordersLastWeek = Order::whereDate('created_at', today()->subDays(7))->count();
        $ordersTrend = $ordersLastWeek > 0
            ? round((($ordersToday - $ordersLastWeek) / $ordersLastWeek) * 100, 1)
            : 0;

        $pendingOrders = Order::where('status', 'pending')->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // 7-day sparkline for orders
        $orderSparkline = collect(range(6, 0))
            ->map(fn ($day) => Order::whereDate('created_at', today()->subDays($day))->count())
            ->toArray();

        // 7-day sparkline for revenue
        $revenueSparkline = collect(range(6, 0))
            ->map(fn ($day) => (float) Order::whereDate('created_at', today()->subDays($day))
                ->whereIn('status', ['completed', 'delivered'])
                ->sum('total_amount'))
            ->toArray();

        return [
            Stat::make('Total Revenue', 'Rs. ' . number_format($totalRevenue, 2))
                ->description('From completed & delivered orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($revenueSparkline)
                ->color('success'),

            Stat::make('Total Orders', Order::count())
                ->description($ordersTrend >= 0
                    ? "{$ordersTrend}% increase this week"
                    : abs($ordersTrend) . "% decrease this week")
                ->descriptionIcon($ordersTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($orderSparkline)
                ->color($ordersTrend >= 0 ? 'success' : 'danger'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 10 ? 'warning' : 'info'),

            Stat::make('Total Customers', User::where('role', '!=', 'admin')->count())
                ->description("{$newUsersThisMonth} new this month")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Active Products', Product::count())
                ->description('Listed in store')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),
        ];
    }
}
