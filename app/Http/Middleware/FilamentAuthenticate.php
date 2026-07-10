<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as FilamentBaseAuthenticate;

class FilamentAuthenticate extends FilamentBaseAuthenticate
{
    protected function redirectTo($request): ?string
    {
        return url('/login');
    }
}
