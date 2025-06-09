<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Optional: Redirect if not logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Check if role is 'user'
        if (Auth::user()->role !== 'user') {
            abort(403, 'Unauthorized - Users only.');
        }

        return $next($request);
    }
}
