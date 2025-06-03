<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.user-login'); // Your user login view
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['user_type'] = 'user'; // restrict login to users only

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/user/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not authorized as user.',
        ]);
    }
}
