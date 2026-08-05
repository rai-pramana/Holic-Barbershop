<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS when behind a reverse proxy (Railway, etc.)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
