<?php

namespace App\Http\Middleware;

use Filament\Models\Contracts\FilamentUser;

class FilamentAuthenticate extends \Filament\Http\Middleware\Authenticate
{
    protected function redirectTo($request): ?string
    {
        return route('login');
    }
}
