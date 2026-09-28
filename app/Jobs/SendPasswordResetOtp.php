<?php

namespace App\Jobs;

use App\Services\BrevoMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Kirim KODE OTP via EMAIL (Brevo HTTP API, gratis 300/hari, semua tujuan).
 */
class SendPasswordResetOtp implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        private readonly string $code,
        private readonly ?string $email = null,
        private readonly ?string $phone = null,
        private readonly ?string $name = null,
        private readonly string $purpose = 'reset',
    ) {}

    public function handle(BrevoMailService $brevo): void
    {
        $emailOk = false;

        if ($this->email) {
            try {
                $emailOk = $brevo->sendOtp($this->email, $this->name ?? 'Pelanggan', $this->code, $this->purpose);
            } catch (\Throwable $e) {
                Log::error('OTP: email gagal', ['email' => $this->email, 'error' => $e->getMessage()]);
            }
        }

        Log::info('OTP: hasil kirim', ['email' => $this->email, 'email_ok' => $emailOk]);

        if (! $emailOk) {
            throw new \RuntimeException('OTP email gagal untuk ' . ($this->email ?? $this->phone));
        }
    }
}
