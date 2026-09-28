<?php

namespace App\Services;

use App\Models\PasswordResetOtp;
use Illuminate\Support\Facades\Hash;

/**
 * Terbitkan + verifikasi kode OTP 6 digit untuk reset password.
 *
 * - Kode acak kriptografis, disimpan sebagai hash (bukan plaintext).
 * - Berlaku 10 menit, max 5x salah tebak per kode, max 3 kode aktif per kontak.
 */
class PasswordResetOtpService
{
    public const CODE_TTL_MINUTES = 10;

    public const MAX_ATTEMPTS = 5;

    public const MAX_ACTIVE_PER_CONTACT = 3;

    /**
     * @return array{otp: PasswordResetOtp, code: string}
     */
    public function issue(?string $email, ?string $phone): array
    {
        $code = (string) random_int(100000, 999999);

        $query = PasswordResetOtp::query()->where('expires_at', '>', now());
        if ($email) {
            $query->where('email', $email);
        } else {
            $query->where('phone', $phone);
        }

        // Batasi kode aktif agar tidak menumpuk (anti-spam).
        $active = $query->oldest()->get();
        while ($active->count() >= self::MAX_ACTIVE_PER_CONTACT) {
            $active->shift()?->delete();
        }

        $otp = PasswordResetOtp::create([
            'email' => $email,
            'phone' => $phone,
            'code_hash' => Hash::make($code),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
        ]);

        return ['otp' => $otp, 'code' => $code];
    }

    /**
     * Verifikasi kode. Return OTP bila valid; null bila salah/kedaluwarsa.
     * Kode yang kedaluwarsa / habis percobaan langsung dihapus.
     */
    public function verify(PasswordResetOtp $otp, string $code): ?PasswordResetOtp
    {
        $otp->refresh();

        if ($otp->isExpired() || $otp->attemptsExceeded()) {
            $otp->delete();
            return null;
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');
            if ($otp->attemptsExceeded()) {
                $otp->delete();
            }
            return null;
        }

        return $otp;
    }
}
