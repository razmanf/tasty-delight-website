<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentOrdersTable extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['user'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order #')
                    ->prefix('#')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->icon('heroicon-m-user'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Amount')
                    ->prefix('')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'info'    => 'out_for_delivery',
                        'success' => fn ($state) => in_array($state, ['completed', 'delivered']),
                        'danger'  => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-m-clock'            => 'pending',
                        'heroicon-m-arrow-path'       => 'processing',
                        'heroicon-m-truck'            => 'out_for_delivery',
                        'heroicon-m-check-circle'     => fn ($state) => in_array($state, ['completed', 'delivered']),
                        'heroicon-m-x-circle'         => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('view')
                    ->url(fn (\App\Models\Order $record) => route('filament.admin.resources.orders.edit', $record))
                    ->icon('heroicon-m-eye')
                    ->color('primary'),
            ])
            ->paginated(false);
    }
}
