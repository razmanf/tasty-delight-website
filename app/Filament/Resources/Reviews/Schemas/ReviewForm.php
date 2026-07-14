<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Review Details')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(3)
                            ->schema([
                                \Filament\Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->live(onBlur: true),
                                \Filament\Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable()
                                    ->live(onBlur: true),
                                \Filament\Forms\Components\TextInput::make('rating')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->suffix('Stars')
                                    ->live(onBlur: true),
                                \Filament\Forms\Components\Textarea::make('comment')
                                    ->default(null)
                                    ->columnSpanFull()
                                    ->rows(4)
                                    ->live(onBlur: true),
                            ])
                    ])
            ]);
    }
}
