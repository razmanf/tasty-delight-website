<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPassword;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class User extends Authenticatable implements MustVerifyEmail, HasAvatar
{
    use HasApiTokens;
    use HasProfilePhoto;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'password',
        'role',
        'email_verified_at',
        'phone_verified_at',
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function favorites()
    {
        return $this->hasMany(\App\Models\Favorite::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    /**
     * Update the user's profile photo to WebP using Intervention.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @param  string  $storagePath
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo, $storagePath = 'profile-photos')
    {
        tap($this->profile_photo_path, function ($previous) use ($photo, $storagePath) {
            $manager = new ImageManager(new Driver());
            $img = $manager->read($photo->getRealPath());
            
            // Scale down to max 400x400 but preserve original aspect ratio (no cropping)
            $img->scaleDown(400, 400);
            
            // Save inside a subfolder based on user ID for perfect organization
            $userFolder = $storagePath . '/' . $this->id;
            $filename = $userFolder . '/' . uniqid() . '.webp';
            Storage::disk($this->profilePhotoDisk())->put($filename, (string) $img->toWebp(80));

            $this->forceFill([
                'profile_photo_path' => $filename,
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Get the avatar URL for Filament Admin.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_photo_url;
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     * Overrides Jetstream's default blue theme with Tasty Delight's orange theme.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        $bgColor = '#FCE5DA';
        $textColor = '#DD6625';

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="none" shape-rendering="auto">
            <rect width="100" height="100" fill="' . $bgColor . '"/>
            <text x="50" y="52" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="40" font-weight="bold" fill="' . $textColor . '">' . htmlspecialchars($name) . '</text>
        </svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
