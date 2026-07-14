<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->live(onBlur: true),
                TextInput::make('contact_number')
                    ->required()
                    ->length(10)
                    ->regex('/^0[0-9]{9}$/')
                    ->validationMessages([
                        'regex' => 'Enter a 10 digit contact number',
                        'size' => 'Enter a 10 digit contact number',
                        'length' => 'Enter a 10 digit contact number',
                    ])
                    ->live(onBlur: true),
                DateTimePicker::make('email_verified_at')
                    ->displayFormat('d/m/Y H:i')
                    ->native(false)
                    ->live(onBlur: true),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->live(onBlur: true),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'user' => 'User'])
                    ->default('user')
                    ->required()
                    ->live(onBlur: true),
                TextInput::make('current_team_id')
                    ->numeric()
                    ->default(null)
                    ->live(onBlur: true),
                TextInput::make('profile_photo_path')
                    ->default(null)
                    ->live(onBlur: true),
                Textarea::make('two_factor_secret')
                    ->default(null)
                    ->columnSpanFull()
                    ->live(onBlur: true),
                Textarea::make('two_factor_recovery_codes')
                    ->default(null)
                    ->columnSpanFull()
                    ->live(onBlur: true),
            ]);
    }
}
