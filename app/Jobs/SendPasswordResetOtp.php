<?php

namespace App\Jobs;

use App\Services\BrevoMailService;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Kirim KODE OTP reset password via 2 kanal (email + WA).
 *
 * Email utama via Brevo HTTP API (gratis 300/hari, semua tujuan).
 * WhatsApp via Fonnte HTTP API sebagai cadangan (Free: nomor sendiri).
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

    public function handle(WhatsAppService $wa, BrevoMailService $brevo): void
    {
        $waOk = false;
        $emailOk = false;

        // ── Kanal 1 (utama): EMAIL via Brevo HTTP API ────────────
        // Gratis 300/hari ke email mana pun — tanpa dinding trial Resend.
        if ($this->email) {
            try {
                $emailOk = $brevo->sendOtp($this->email, $this->name ?? 'Pelanggan', $this->code, $this->purpose);
            } catch (\Throwable $e) {
                Log::error('OTP: email gagal', ['email' => $this->email, 'error' => $e->getMessage()]);
            }
        }

        // ── Kanal 2 (cadangan): WHATSAPP via Fonnte ──────────────
        // Paket Free hanya sampai ke nomor device sendiri.
        if ($this->phone) {
            try {
                $kind = $this->purpose === 'verify' ? 'verifikasi email' : 'reset password';
                // ASCII saja — Fonnte menolak karakter non-UTF8.
                $message = 'Kode ' . $kind . ' HOLIC Barbershop Anda: ' . $this->code . "\n\n"
                    . "Masukkan kode ini di website (berlaku 10 menit).\n"
                    . 'Jika Anda tidak meminta, abaikan pesan ini.';

                $waOk = $wa->send($this->phone, $message);
            } catch (\Throwable $e) {
                Log::error('OTP: WA gagal', ['phone' => $this->phone, 'error' => $e->getMessage()]);
            }
        }

        Log::info('OTP: hasil kirim', ['email' => $this->email, 'wa_ok' => $waOk, 'email_ok' => $emailOk]);

        if (! $waOk && ! $emailOk) {
            throw new \RuntimeException('Semua kanal OTP gagal untuk ' . ($this->email ?? $this->phone));
        }
    }
}
