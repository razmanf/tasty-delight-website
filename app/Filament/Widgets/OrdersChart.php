<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Orders Over Time';

    protected ?string $description = 'Daily order count for the last 30 days';

    protected ?string $maxHeight = '300px';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            '7'  => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 30);

        $labels = [];
        $data   = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date     = now()->subDays($i);
            $labels[] = $date->format($days <= 7 ? 'D d M' : 'd M');
            $data[]   = Order::whereDate('created_at', $date->toDateString())->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Orders',
                    'data'            => $data,
                    'borderColor'     => '#f59e0b',
                    'backgroundColor' => 'rgba(245,158,11,0.15)',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'pointRadius'     => 3,
                    'pointHoverRadius'=> 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                ],
            ],
        ];
    }
}
