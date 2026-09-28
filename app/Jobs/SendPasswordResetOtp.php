<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

/**
 * Kirim KODE OTP reset password via 2 kanal (WA + email).
 *
 * Prioritas WhatsApp (Fonnte HTTP API) — kode pendek selalu terbaca,
 * tidak seperti tautan panjang yang tak bisa diklik / masuk spam.
 * Email (Resend HTTP API) sebagai cadangan; kegagalannya tidak
 * menggagalkan job selama WA terkirim.
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
    ) {}

    public function handle(WhatsAppService $wa): void
    {
        $waOk = false;
        $emailOk = false;

        // ── Kanal 1 (utama): WHATSAPP via Fonnte ────────────────
        if ($this->phone) {
            try {
                // ASCII saja — Fonnte menolak karakter non-UTF8.
                $message = 'Kode reset password HOLIC Barbershop Anda: ' . $this->code . "\n\n"
                    . "Masukkan kode ini di website (berlaku 10 menit).\n"
                    . 'Jika Anda tidak meminta, abaikan pesan ini.';

                $waOk = $wa->send($this->phone, $message);
            } catch (\Throwable $e) {
                Log::error('OTP: WA gagal', ['phone' => $this->phone, 'error' => $e->getMessage()]);
            }
        }

        // ── Kanal 2 (cadangan): EMAIL via Resend ────────────────
        if ($this->email) {
            try {
                $user = \App\Models\User::where('email', $this->email)->first();
                if ($user) {
                    // Kirim kode via notifikasi mail langsung (tanpa antre 2 lapis).
                    $user->notify(new \App\Notifications\ResetPasswordOtpNotification($this->code));
                    $emailOk = true;
                }
            } catch (\Throwable $e) {
                Log::error('OTP: email gagal', ['email' => $this->email, 'error' => $e->getMessage()]);
            }
        }

        Log::info('OTP: hasil kirim', ['email' => $this->email, 'wa_ok' => $waOk, 'email_ok' => $emailOk]);

        if (! $waOk && ! $emailOk) {
            throw new \RuntimeException('Semua kanal OTP gagal untuk ' . ($this->email ?? $this->phone));
        }
    }
}
