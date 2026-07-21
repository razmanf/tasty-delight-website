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
                    ->unique(ignoreRecord: true)
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
                    ->minValue(0)
                    ->default(0)
                    ->live(onBlur: true),
                FileUpload::make('image')
                    ->image()
                    ->maxSize(5120) // 5MB limit
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1') // Force square crop
                    ->imageResizeTargetWidth('600')
                    ->imageResizeTargetHeight('600')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->live(onBlur: true),
            ]);
    }
}
