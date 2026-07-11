<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Filament\Resources\Reviews\Schemas\ReviewForm;
use App\Filament\Resources\Reviews\Tables\ReviewsTable;
use App\Models\Review;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-star';

    protected static string | UnitEnum | null $navigationGroup = 'People';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'comment';

    public static function getGlobalSearchResultTitle($record): string
    {
        return "Review by {$record->user?->name}";
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Product' => $record->product?->name ?? 'Unknown',
            'Rating'  => str_repeat('★', $record->rating) . str_repeat('☆', 5 - $record->rating),
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }

    public static function form(Schema $schema): Schema
    {
        return ReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit'   => EditReview::route('/{record}/edit'),
        ];
    }
}
