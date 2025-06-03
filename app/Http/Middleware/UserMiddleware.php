<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'customer') {
            // Redirect or abort with 403 Forbidden
            abort(403, 'Access denied - Users only.');
        }

        return $next($request);
    }
}
