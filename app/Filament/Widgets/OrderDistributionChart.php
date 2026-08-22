<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrderDistributionChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Orders by Status';

    protected ?string $description = 'Distribution of orders across all statuses';

    protected ?string $maxHeight = '270px';

    protected string $view = 'filament.widgets.order-distribution-chart-widget';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            '7'  => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
            'all' => 'All time',
        ];
    }

    protected function getData(): array
    {
        $query = Order::query();

        if ($this->filter === 'today') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($this->filter !== 'all') {
            $query->where('created_at', '>=', now()->subDays((int) $this->filter));
        }

        // Aggregate counts by status
        $counts = [
            'pending' => 0,
            'processing' => 0,
            'completed' => 0,
            'delivered' => 0,
            'cancelled' => 0,
        ];

        // Fetch counts from DB
        $results = $query->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        foreach ($results as $status => $total) {
            $counts[$status] = $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => array_values($counts),
                    'backgroundColor' => [
                        '#f59e0b', // pending (amber)
                        '#3b82f6', // processing (blue)
                        '#10b981', // completed (emerald)
                        '#8b5cf6', // delivered (purple)
                        '#ef4444', // cancelled (red)
                    ],
                    'hoverOffset' => 4,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => [
                'Pending', 'Processing', 'Completed', 'Delivered', 'Cancelled'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'right',
                    'labels'   => [
                        'usePointStyle' => true,
                        'boxWidth'      => 8,
                        'padding'       => 14,   // was 20 — tighter so labels don't crowd the edge
                        'font'          => [
                            'family' => 'inherit',
                            'size'   => 11,       // explicit size prevents Chart.js inheriting a larger computed value
                        ],
                    ],
                ],
                'tooltip' => [
                    'mode'      => 'index',
                    'intersect' => false,
                ],
            ],
            'layout' => [
                'padding' => [
                    'top'    => 8,
                    'right'  => 20,  // extra right buffer — prevents legend text touching the canvas edge
                    'bottom' => 8,
                    'left'   => 8,
                ],
            ],
            'cutout'              => '75%',
            'maintainAspectRatio' => false,
        ];
    }
}
