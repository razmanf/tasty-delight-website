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
            ->poll('15s')
            ->columns([
                TextColumn::make('id')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('order_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'delivery' => 'info',
                        'pickup' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total_amount')
                    ->label('Total amount ($)')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('payment_method')
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'processing']);
                        if ($record->user && $record->user->email) {
                            try {
                                \Illuminate\Support\Facades\Mail::to($record->user->email)->send(new \App\Mail\OrderProcessingNotification($record));
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
                            }
                        }
                    }),
                \Filament\Actions\Action::make('mark_delivery')
                    ->label('Mark Delivery')
                    ->icon('heroicon-m-truck')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'processing')
                    ->action(fn ($record) => $record->update(['status' => 'out_for_delivery'])),
                \Filament\Actions\Action::make('mark_delivered')
                    ->label('Mark Delivered')
                    ->icon('heroicon-m-home')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'out_for_delivery')
                    ->action(fn ($record) => $record->update(['status' => 'delivered'])),
                \Filament\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-m-check-badge')
                    ->color('primary')
                    ->visible(fn ($record) => in_array($record->status, ['out_for_delivery', 'delivered']))
                    ->action(fn ($record) => $record->update(['status' => 'completed'])),
                \Filament\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'processing']))
                    ->action(fn ($record) => $record->update(['status' => 'cancelled'])),
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
