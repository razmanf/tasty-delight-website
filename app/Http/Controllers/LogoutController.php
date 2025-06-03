<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the logout request with role-based redirection.
     */
    public function logout(Request $request)
    {
        $userType = Auth::user()?->user_type;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($userType === 'admin') {
            return redirect('/admin/login')->with('status', 'Logged out as Admin.');
        }

        return redirect('/user/login')->with('status', 'Logged out as User.');
    }
}
