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
                    ->disk('public')
                    ->maxSize(5120) // 5MB limit
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->live(onBlur: true)
                    ->saveUploadedFileUsing(function (\Illuminate\Http\UploadedFile $file, $get) {
                        $categoryId = $get('category_id') ?? 'uncategorized';
                        $productFolder = 'product-images/' . $categoryId;
                        
                        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                        $img = $manager->read($file->getRealPath());
                        
                        // Scale down to max 600x600 but preserve original aspect ratio (no cropping)
                        $img->scaleDown(600, 600);
                        
                        $filename = $productFolder . '/' . uniqid() . '.webp';
                        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, (string) $img->toWebp(80));
                        
                        return $filename;
                    }),
            ]);
    }
}
