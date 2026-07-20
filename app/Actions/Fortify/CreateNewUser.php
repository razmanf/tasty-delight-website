<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact_number' => ['required', 'string', 'size:10', 'regex:/^0[0-9]{9}$/', 'unique:users'],
            'otp_code' => ['required', 'string', 'size:6'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'email.unique' => 'This email address has already been taken',
            'contact_number.size' => 'Enter a 10 digit contact number',
            'contact_number.regex' => 'Enter a 10 digit contact number',
            'contact_number.unique' => 'This contact number is already registered',
            'password.min' => 'The password must be 8 characters or more',
            'password.confirmed' => 'The passwords do not match',
            'otp_code.required' => 'Please verify your phone number with the OTP code first',
        ])->validate();

        $rateLimitKey = 'otp_attempts_' . request()->ip() . '_' . $input['contact_number'];

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            throw ValidationException::withMessages([
                'otp_code' => 'Too many attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.',
            ]);
        }

        $cachedOtp = Cache::get('registration_otp_' . $input['contact_number']);

        if (!$cachedOtp || $cachedOtp != $input['otp_code']) {
            RateLimiter::hit($rateLimitKey, 600); // 10 minute decay
            throw ValidationException::withMessages([
                'otp_code' => 'The verification code is invalid or has expired.',
            ]);
        }

        RateLimiter::clear($rateLimitKey);
        // Clear the OTP from cache
        Cache::forget('registration_otp_' . $input['contact_number']);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'contact_number' => $input['contact_number'],
            'phone_verified_at' => now(),
            'email_verified_at' => now(), // Automatically verify since OTP was sent via email
            'password' => Hash::make($input['password']),
            'role' => 'user',
        ]);

        // Dispatch Welcome Email to the Queue Worker
        \Illuminate\Support\Facades\Mail::to($user)->queue(new \App\Mail\WelcomeEmail($user));

        // 1. Send Welcome Notification to the New User
        \Filament\Notifications\Notification::make()
            ->title('Welcome to TastyDelight!')
            ->body('We are absolutely thrilled to have you here. Explore our menu and place your first order today!')
            ->success()
            ->icon('heroicon-o-sparkles')
            ->sendToDatabase($user);

        // 2. Send Alert Notification to all Admins
        $admins = User::where('role', 'admin')->get();
        if ($admins->count() > 0) {
            \Filament\Notifications\Notification::make()
                ->title('New User Registered')
                ->body("{$user->name} ({$user->email}) has just joined the platform.")
                ->info()
                ->icon('heroicon-o-user-plus')
                ->sendToDatabase($admins);
        }

        return $user;
    }
}
