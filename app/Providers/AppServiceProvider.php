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

        // Fallback: proses max 1 job reset-password per request web agar email
        // tetap terkirim walau queue worker belum/tidak jalan.
        // Dilewati untuk: console, queue worker itu sendiri, dan request non-GET
        // (POST dsb. — agar tidak menambah latensi request utama).
        try {
            if ($this->app->runningInConsole() || $this->app->runningUnitTests()) {
                return;
            }
            $request = request();
            if (!$request || !$request->isMethod('get')) {
                return;
            }
            $connection = app('queue')->connection();
            $job = $connection->pop('default');
            if (!$job) {
                return;
            }
            if (str_contains($job->resolveName() ?? '', 'SendPasswordResetLink')) {
                app('queue.worker')->process(
                    $connection->getConnectionName(),
                    $job,
                    new \Illuminate\Queue\WorkerOptions
                );
            } else {
                $job->release();
            }
        } catch (\Throwable $e) {
            // diam — jangan ganggu request utama
        }
    }
}
