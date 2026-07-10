<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role === 'user') {
            return $next($request);
        }

        // Logged-in non-user (admin) → send to their dashboard
        return redirect()->route('filament.admin.pages.dashboard');
    }
}
