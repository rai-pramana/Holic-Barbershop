<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

/**
 * Kirim tautan reset password via 2 kanal (email + WhatsApp).
 *
 * Desain andal:
 * - Email via Resend HTTP API (SMTP diblokir Railway — QDISC_DROP).
 * - WhatsApp via Fonnte HTTP API (HTTPS 443, tidak diblokir).
 * - Pre-check konektivitas 20s: gagal anggun, tidak menggantung.
 * - Hasil per kanal di-log agar diagnosa jelas (email_ok, wa_ok).
 */
class SendPasswordResetLink implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        private readonly string $email,
        private readonly ?string $phone = null,
        private readonly ?string $name = null,
    ) {}

    public function handle(WhatsAppService $wa): void
    {
        $emailOk = false;
        $waOk = false;

        // ── Kanal 1: EMAIL via Resend HTTP API ──────────────────
        try {
            Http::timeout(20)->get('https://api.resend.com');
            $status = Password::sendResetLink(['email' => $this->email]);
            $emailOk = $status === Password::RESET_LINK_SENT;
            if (! $emailOk) {
                Log::warning('Reset: email tidak terkirim', ['email' => $this->email, 'status' => $status]);
            }
        } catch (\Throwable $e) {
            Log::error('Reset: email gagal', ['email' => $this->email, 'error' => $e->getMessage()]);
        }

        // ── Kanal 2: WHATSAPP via Fonnte (fallback + penguat) ───
        if ($this->phone) {
            try {
                $user = \App\Models\User::where('email', $this->email)->first();
                if ($user) {
                    $token = Password::createToken($user);
                    $url = url(route('password.reset', [
                        'token' => $token,
                        'email' => $user->getEmailForPasswordReset(),
                    ], false));

                    // ASCII saja — Fonnte menolak karakter non-UTF8.
                    $message = 'Halo ' . ($this->name ?? 'Pelanggan') . "!\n\n"
                        . "Anda meminta reset password akun HOLIC Barbershop.\n"
                        . "Klik tautan berikut (berlaku 60 menit):\n"
                        . $url . "\n\n"
                        . "Jika Anda tidak meminta, abaikan pesan ini.\n\n"
                        . '_HOLIC Barbershop_';

                    $waOk = $wa->send($this->phone, $message);
                }
            } catch (\Throwable $e) {
                Log::error('Reset: WA gagal', ['phone' => $this->phone, 'error' => $e->getMessage()]);
            }
        }

        Log::info('Reset: hasil kirim', ['email' => $this->email, 'email_ok' => $emailOk, 'wa_ok' => $waOk]);

        // Retry bila SEMUA kanal gagal dan user punya WA (mungkin transient).
        if (! $emailOk && ($this->phone ? ! $waOk : true)) {
            throw new \RuntimeException('Semua kanal reset gagal untuk ' . $this->email);
        }
    }
}
