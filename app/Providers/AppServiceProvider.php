<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use App\Http\Responses\LogoutResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useTailwind();

        
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $forceUrl = 'https://' . $_SERVER['HTTP_X_FORWARDED_HOST'];
            \Illuminate\Support\Facades\URL::forceRootUrl($forceUrl);
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
