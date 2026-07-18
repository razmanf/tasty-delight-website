<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Schema;

class AdminSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Profile & Settings';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.admin-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'name'           => $user->name,
            'profile_photo'  => null,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Profile Information')
                    ->description('Update your name and profile photo.')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        FileUpload::make('profile_photo')
                            ->label('Profile Photo')
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->directory('profile-photos')
                            ->disk('public')
                            ->maxSize(2048)
                            ->helperText('Max 2MB. Will be cropped to a circle.'),

                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        Placeholder::make('email')
                            ->label('Email Address')
                            ->content(fn () => Auth::user()->email)
                            ->helperText('🔒 Email address cannot be changed for security reasons.'),
                    ]),

                Section::make('Change Password')
                    ->description('Leave blank if you do not want to change your password.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Current Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(false),

                        TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->revealable()
                            ->rule(Password::defaults())
                            ->different('current_password')
                            ->dehydrated(false),

                        TextInput::make('new_password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable()
                            ->same('new_password')
                            ->dehydrated(false),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        // Update name
        $user->name = $data['name'];

        // Update profile photo if provided
        if (!empty($data['profile_photo'])) {
            $user->profile_photo_path = $data['profile_photo'];
        }

        // Update password if provided
        if (!empty($this->data['new_password'])) {
            if (!Hash::check($this->data['current_password'] ?? '', $user->password)) {
                Notification::make()
                    ->title('Incorrect current password')
                    ->danger()
                    ->send();
                return;
            }
            $user->password = Hash::make($this->data['new_password']);
        }

        $user->save();

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
