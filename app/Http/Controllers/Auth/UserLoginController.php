<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class UserLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('livewire.user-login'); // Your user login view
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['user_type'] = 'user'; // restrict login to users only

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Important for security

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not authorized as user.',
        ]);
    }
}
