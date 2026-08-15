<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message via Fonnte API.
     * Silently fails if token is not configured or if Fonnte returns an error.
     */
    public function send(string $phone, string $message): bool
    {
        $token = config('services.fonnte.token');

        if (empty($token)) {
            // Not configured — skip silently
            return false;
        }

        $normalized = $this->normalizePhone($phone);

        if (! $normalized) {
            Log::warning('WhatsApp: invalid or empty phone number', ['phone' => $phone]);
            return false;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => $token])
                ->post('https://api.fonnte.com/send', [
                    'target'  => $normalized,
                    'message' => $message,
                ]);

            if (! $response->successful()) {
                Log::warning('WhatsApp: Fonnte returned non-2xx', [
                    'phone'  => $normalized,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            $json = $response->json();
            if (isset($json['status']) && $json['status'] === false) {
                Log::warning('WhatsApp: Fonnte rejected message', [
                    'phone'  => $normalized,
                    'reason' => $json['reason'] ?? 'unknown',
                ]);
                return false;
            }

            return true;

        } catch (\Throwable $e) {
            Log::error('WhatsApp: exception when calling Fonnte', [
                'phone' => $normalized,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Normalize Indonesian phone numbers to international format.
     * Examples:
     *   08123456789  → 628123456789
     *   +62812345678 → 62812345678
     *   62812345678  → 62812345678 (no change)
     */
    public function normalizePhone(string $phone): ?string
    {
        // Strip spaces, dashes, parentheses
        $cleaned = preg_replace('/[\s\-\(\)]+/', '', $phone);

        if (empty($cleaned)) {
            return null;
        }

        // Remove leading +
        $cleaned = ltrim($cleaned, '+');

        // 0xxx → 62xxx
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        }

        // Must start with 62 and be 10–15 digits
        if (! preg_match('/^62\d{8,13}$/', $cleaned)) {
            return null;
        }

        return $cleaned;
    }
}