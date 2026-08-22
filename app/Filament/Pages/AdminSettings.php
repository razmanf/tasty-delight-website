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
            'profile_photo'  => $user->profile_photo_path,
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
                            ->avatar()
                            ->image()
                            ->directory('profile-photos')
                            ->disk('public')
                            ->maxSize(2048)
                            ->helperText('Max 2MB. Will be scaled to max 400x400.')
                            ->saveUploadedFileUsing(function (\Illuminate\Http\UploadedFile $file) {
                                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                                $img = $manager->read($file->getRealPath());
                                
                                $img->scaleDown(400, 400);
                                
                                $userFolder = 'profile-photos/' . \Illuminate\Support\Facades\Auth::id();
                                $filename = $userFolder . '/' . uniqid() . '.webp';
                                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, (string) $img->toWebp(80));
                                
                                return $filename;
                            }),

                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->autocomplete('name')
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
                            ->autocomplete('current-password')
                            ->revealable()
                            ->dehydrated(false),

                        TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->autocomplete('new-password')
                            ->revealable()
                            ->rule(Password::defaults())
                            ->different('current_password')
                            ->dehydrated(false),

                        TextInput::make('new_password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->autocomplete('new-password')
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

        // Update profile photo (allows setting to null if deleted)
        if (array_key_exists('profile_photo', $data)) {
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
        
        // Update the password hash in the session so AuthenticateSession doesn't log the user out
        if (request()->hasSession()) {
            request()->session()->put([
                'password_hash_'.Auth::getDefaultDriver() => $user->getAuthPassword(),
            ]);
        }

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
            
        // Dispatch event for live topbar avatar update (avoids page reload glitches)
        $this->dispatch('admin-avatar-updated', url: $user->profile_photo_url);
    }
}
