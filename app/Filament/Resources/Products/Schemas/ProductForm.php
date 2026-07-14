<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category_id')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true),
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->live(onBlur: true),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->live(onBlur: true),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true),
                FileUpload::make('image')
                    ->image()
                    ->required()
                    ->live(onBlur: true),
            ]);
    }
}
