<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Top Selling Products';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->withCount('orders')
                    ->orderByDesc('orders_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->sortable()
                    ->toggleable()
                    ->defaultImageUrl(asset('images/placeholder-food.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->toggleable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->toggleable()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price ($)')
                    ->toggleable()
                    ->money('USD'),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Orders')
                    ->badge()
                    ->toggleable()
                    ->color('success')
                    ->suffix(' orders'),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->toggleable()
                    ->color(fn ($state) => match(true) {
                        $state <= 5  => 'danger',
                        $state <= 20 => 'warning',
                        default      => 'success',
                    }),
            ])
            ->paginated(false);
    }
}
