<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(['default' => 1, 'xl' => 3])->schema([
                    // Left Column (2/3 width)
                    Grid::make(1)->columnSpan(['default' => 1, 'xl' => 2])->schema([
                        Section::make('Customer & Status')->schema([
                            Grid::make(2)->schema([
                                TextInput::make('customer_name')
                                    ->label('Customer Name')
                                    ->loadStateFromRelationshipsUsing(fn ($record, $state, $component) => $component->state($record?->user?->name))
                                    ->saveRelationshipsUsing(fn ($record, $state) => $record?->user?->update(['name' => $state]))
                                    ->dehydrated(false)
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required(),
                                Select::make('payment_method')
                                    ->options([
                                        'cash' => 'Cash',
                                        'counter' => 'Pay at Counter',
                                        'card' => 'Card',
                                    ])
                                    ->required(),
                                Select::make('order_type')
                                    ->options([
                                        'delivery' => 'Delivery',
                                        'pickup' => 'Pickup',
                                    ])
                                    ->required(),
                            ]),
                        ]),

                        Section::make('Order Items')->schema([
                            Repeater::make('products')
                                ->relationship()
                                ->disabled()
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Product')
                                        ->options(\App\Models\Product::pluck('name', 'id'))
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->required()
                                        ->columnSpan(['default' => 1, 'md' => 1, 'xl' => 5]),
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->default(1)
                                        ->required()
                                        ->columnSpan(['default' => 1, 'md' => 1, 'xl' => 3]),
                                      \Filament\Forms\Components\Placeholder::make('price_display')
                                          ->label('Unit Price & Total')
                                          ->content(function ($get, $livewire) {
                                              $order = $livewire->getRecord();
                                              if (!$order || !$get('product_id')) return 'N/A';
                                              $pivot = \Illuminate\Support\Facades\DB::table('order_product')
                                                  ->where('order_id', $order->id)
                                                  ->where('product_id', $get('product_id'))
                                                  ->first();
                                              
                                              if (!$pivot) return 'N/A';
                                              
                                              $unitPrice = $pivot->price;
                                              $qty = (int) $get('quantity') ?: 1;
                                              $total = $unitPrice * $qty;
                                              
                                              return '$' . number_format($unitPrice, 2) . ' (Total: $' . number_format($total, 2) . ')';
                                          })
                                          ->columnSpan(['default' => 1, 'md' => 1, 'xl' => 4]),
                                ])
                                ->columns(['default' => 1, 'md' => 1, 'xl' => 12])
                                ->defaultItems(0)
                                ->disableItemCreation()
                                ->disableItemDeletion()
                                ->disableItemMovement(),
                        ]),

                        Section::make('Fulfillment Details')->schema([
                            Textarea::make('delivery_address')
                                ->label('Delivery Address')
                                ->columnSpanFull(),
                            TextInput::make('delivery_date')->label('Delivery Date'),
                            TextInput::make('delivery_time')->label('Delivery Time'),
                            TextInput::make('pickup_date')->label('Pickup Date'),
                            TextInput::make('pickup_time')->label('Pickup Time'),
                            Textarea::make('preparation_note')->label('Preparation Note')->columnSpanFull(),
                            Textarea::make('delivery_note')->label('Delivery Note')->columnSpanFull(),
                        ])->columns(2),
                    ]),

                    // Right Column (1/3 width)
                    Grid::make(1)->columnSpan(['default' => 1, 'xl' => 1])->schema([
                        Section::make('Financials')->schema([
                            TextInput::make('tax_amount')
                                ->label('Tax Amount (5%)')
                                ->numeric()
                                ->prefix('$')
                                ->default(0),
                            TextInput::make('delivery_fee')
                                ->label('Delivery Fee')
                                ->numeric()
                                ->prefix('$')
                                ->default(0),
                            TextInput::make('total_amount')
                                ->label('Total Amount')
                                ->required()
                                ->numeric()
                                ->prefix('$'),
                        ]),
                    ]),
                ]),
            ]);
    }
}
