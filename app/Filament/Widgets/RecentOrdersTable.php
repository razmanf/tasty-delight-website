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
                    ->toggleable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-m-user'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Amount ($)')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),

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
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->badge()
                    ->toggleable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->url(fn (\App\Models\Order $record) => route('filament.admin.resources.orders.edit', $record))
                    ->icon('heroicon-m-eye')
                    ->color('primary'),
            ])
            ->paginated(false);
    }
}
