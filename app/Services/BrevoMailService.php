<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Kirim email transaksional via Brevo HTTP API.
 *
 * Kenapa Brevo (bukan SMTP / Resend):
 * - Railway memblokir SMTP keluar (QDISC_DROP) — semua via port 587 mati.
 * - Resend trial hanya kirim ke email pemilik akun.
 * - Brevo gratis 300 email/hari ke email mana pun; cukup verifikasi
 *   1 alamat pengirim (klik link), tanpa verifikasi domain untuk mulai.
 *
 * API: POST https://api.brevo.com/v3/smtp/email, header "api-key: <key>".
 */
class BrevoMailService
{
    public function send(string $toEmail, string $toName, string $subject, string $html, ?string $text = null): bool
    {
        $apiKey = config('services.brevo.api_key');
        $fromEmail = config('services.brevo.from_email');
        $fromName = config('services.brevo.from_name', 'HOLIC Barbershop');

        if (empty($apiKey) || empty($fromEmail)) {
            Log::warning('Brevo: API key / pengirim belum dikonfigurasi');
            return false;
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders(['api-key' => $apiKey, 'Content-Type' => 'application/json'])
                ->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => ['name' => $fromName, 'email' => $fromEmail],
                    'to' => [['email' => $toEmail, 'name' => $toName]],
                    'subject' => $subject,
                    'htmlContent' => $html,
                    'textContent' => $text ?? strip_tags($html),
                    'tags' => ['otp-reset-password'],
                ]);

            if (! $response->successful()) {
                Log::warning('Brevo: API menolak kirim', [
                    'to' => $toEmail,
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 300),
                ]);
                return false;
            }

            Log::info('Brevo: email terkirim', [
                'to' => $toEmail,
                'messageId' => $response->json('messageId'),
            ]);
            return true;

        } catch (\Throwable $e) {
            Log::error('Brevo: exception saat kirim', ['to' => $toEmail, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Kirim kode OTP reset password. Return true bila terkirim.
     */
    public function sendOtp(string $toEmail, string $toName, string $code): bool
    {
        $subject = 'Kode Reset Password — HOLIC Barbershop';
        $html = '<html><body style="font-family:sans-serif;max-width:480px;margin:0 auto;padding:24px;">'
            . '<h2 style="margin-bottom:8px;">Halo, ' . e($toName) . '!</h2>'
            . '<p>Kode reset password Anda:</p>'
            . '<p style="font-size:32px;font-weight:bold;letter-spacing:8px;text-align:center;background:#f1f5f9;border-radius:12px;padding:16px;">' . e($code) . '</p>'
            . '<p>Masukkan kode ini di website. Berlaku 10 menit.</p>'
            . '<p style="color:#64748b;font-size:13px;">Jika Anda tidak meminta reset password, abaikan email ini.<br>Salam, Tim HOLIC Barbershop</p>'
            . '</body></html>';

        return $this->send($toEmail, $toName, $subject, $html);
    }
}
