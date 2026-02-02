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
        // Fix untuk Ngrok/Tunneling:
        // Saat pakai ngrok, Laravel menerima header Host: portal_kerja.test (karena --host-header=rewrite)
        // Tapi browser user mengakses via ...ngrok-free.app
        // Akibatnya aset (logo) dan CSRF (login) error.
        // Script ini memaksa Laravel menggunakan domain asli Ngrok jika terdeteksi.
        
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $forceUrl = 'https://' . $_SERVER['HTTP_X_FORWARDED_HOST'];
            \Illuminate\Support\Facades\URL::forceRootUrl($forceUrl);
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
