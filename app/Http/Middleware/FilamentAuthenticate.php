<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as FilamentBaseAuthenticate;
use Illuminate\Http\Request;

class FilamentAuthenticate extends FilamentBaseAuthenticate
{
    protected function redirectTo($request): ?string
    {
        return url('/login');
    }

    protected function authenticate($request, array $guards): void
    {
        parent::authenticate($request, $guards);

        // If authenticated but not an admin, silently redirect to user dashboard
        if (auth()->check() && !auth()->user()->isAdmin()) {
            redirect()->route('user.dashboard')->send();
            exit;
        }
    }
}

