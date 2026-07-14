<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_name')
                    ->label('Customer Name')
                    ->loadStateFromRelationshipsUsing(fn ($record, $state, $component) => $component->state($record?->user?->name))
                    ->saveRelationshipsUsing(fn ($record, $state) => $record?->user?->update(['name' => $state]))
                    ->dehydrated(false)
                    ->required()
                    ->live(onBlur: true),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->live(onBlur: true),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ])
                    ->required()
                    ->live(onBlur: true),
                Select::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'credit_card' => 'Credit Card',
                        'paypal' => 'Paypal',
                    ])
                    ->required()
                    ->live(onBlur: true),
            ]);
    }
}
