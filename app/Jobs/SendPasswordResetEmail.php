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
        try {
            Password::sendResetLink($this->credentials);
        } catch (\Throwable $e) {
            Log::error('Reset email gagal', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
