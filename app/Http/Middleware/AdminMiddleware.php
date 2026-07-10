<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if (auth()->user()->role === 'admin') {
                return $next($request);
            }

            // Logged-in non-admin (regular user) → send to their dashboard
            return redirect()->route('user.dashboard');
        }

        // Not logged in → send to login
        return redirect()->route('login');
    }
}
