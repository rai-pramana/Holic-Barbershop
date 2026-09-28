<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class SendPasswordResetEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 25;

    public function __construct(
        private readonly array $credentials,
    ) {}

    public function handle(): void
    {
        // Jangan pernah menggantung: Resend API normal <5 detik.
        // Timeout eksplisit agar worker tidak tersangkut bila jaringan diblokir.
        try {
            \Illuminate\Support\Facades\Http::timeout(20)->get('https://api.resend.com');
        } catch (\Throwable $e) {
            Log::error('Reset email: api.resend.com tidak terjangkau', ['error' => $e->getMessage()]);
            return; // gagal anggun — tidak retry buta
        }

        try {
            Password::sendResetLink($this->credentials);
        } catch (\Throwable $e) {
            Log::error('Reset email gagal', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
