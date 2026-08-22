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
                                \Filament\Forms\Components\Select::make('order_id')
                                    ->relationship('order', 'id')
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
                                    ->rows(3)
                                    ->live(onBlur: true),
                                \Filament\Forms\Components\TextInput::make('rider_rating')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->suffix('Stars')
                                    ->live(onBlur: true),
                                \Filament\Forms\Components\Textarea::make('rider_comment')
                                    ->default(null)
                                    ->columnSpanFull()
                                    ->rows(3)
                                    ->live(onBlur: true),
                                \Filament\Forms\Components\FileUpload::make('media')
                                    ->multiple()
                                    ->disk('public')
                                    ->directory('reviews')
                                    ->maxSize(20480)
                                    ->saveUploadedFileUsing(function (\Illuminate\Http\UploadedFile $file, $get) {
                                        $userId = $get('user_id') ?? 'unassigned';
                                        $userFolder = 'reviews/' . $userId;
                                        
                                        $mimeType = $file->getMimeType();
                                        
                                        // If it is an image, optimize and convert to WebP
                                        if (str_starts_with($mimeType, 'image/')) {
                                            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                                            $img = $manager->read($file->getRealPath());
                                            
                                            // Scale down to max 1200x1200 but keep aspect ratio
                                            $img->scaleDown(1200, 1200);
                                            
                                            $filename = $userFolder . '/' . uniqid() . '.webp';
                                            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, (string) $img->toWebp(80));
                                            
                                            return $filename;
                                        }
                                        
                                        // For videos (mp4, mov, avi), just save them normally
                                        return $file->store($userFolder, 'public');
                                    })
                                    ->columnSpanFull(),
                            ])
                    ])
            ]);
    }
}
