<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\LoginResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Fortify::authenticateUsing(function (Request $request) {
            // Call Sanctum API login endpoint
            $response = Http::post(config('app.url') . '/api/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check if response has user data
                if (isset($data['user']['id'])) {
                    // Retrieve user by ID
                    $user = User::find($data['user']['id']);

                    // Optionally double-check password here if needed
                    if ($user && Hash::check($request->password, $user->password)) {
                        return $user;
                    }
                }
            }

            // Return null if authentication fails
            return null;
        });

        // You can add other Fortify features here, e.g., registration, password reset...
    }
    
}
