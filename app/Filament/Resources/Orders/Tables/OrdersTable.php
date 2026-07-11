<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'processing']);
                        // In a real app, you might trigger notifications here
                    }),
                \Filament\Tables\Actions\Action::make('mark_delivery')
                    ->label('Mark Delivery')
                    ->icon('heroicon-m-truck')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'processing')
                    ->action(fn ($record) => $record->update(['status' => 'out_for_delivery'])),
                \Filament\Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-m-check-badge')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === 'out_for_delivery')
                    ->action(fn ($record) => $record->update(['status' => 'completed'])),
                \Filament\Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'processing']))
                    ->action(fn ($record) => $record->update(['status' => 'cancelled'])),
                \Filament\Tables\Actions\EditAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
