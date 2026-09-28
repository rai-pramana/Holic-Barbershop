<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BrevoMailService;
use App\Services\PasswordResetOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Verifikasi email saat pendaftaran via kode OTP 6 digit (Brevo).
 *
 * Alur: daftar → kode dikirim ke email → user ketik kode di halaman
 * verifikasi → email_verified_at diisi → bebas antre.
 * OTP disimpan di tabel password_reset_otps (kolom email), reuse penuh
 * dari PasswordResetOtpService (hash, 10 menit, 5x percobaan).
 */
class EmailVerificationController extends Controller
{
    /**
     * Halaman "cek email + masukkan kode".
     */
    public function notice(Request $request): View|RedirectResponse
    {
        $user = $this->targetUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('customer.dashboard');
        }

        return view('auth.verify-email', ['email' => $user->email]);
    }

    /**
     * Kirim (ulang) kode OTP ke email user.
     */
    public function send(Request $request, PasswordResetOtpService $otps, BrevoMailService $brevo): RedirectResponse
    {
        $user = $this->targetUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('customer.dashboard');
        }

        $wait = $otps->resendCooldownRemaining($user->email, null);
        if ($wait > 0) {
            return back()->withErrors([
                'code' => 'Tunggu ' . $wait . ' detik sebelum meminta kode baru.',
            ]);
        }

        $issued = $otps->issue($user->email, null);
        $request->session()->put('verify_otp_id', $issued['otp']->id);

        $sent = $brevo->sendOtp($user->email, $user->name, $issued['code']);

        return back()->with(
            'status',
            $sent
                ? 'Kode verifikasi 6 digit dikirim ke ' . $user->email . '. Berlaku 10 menit.'
                : 'Gagal mengirim kode. Coba lagi dalam beberapa saat.'
        );
    }

    /**
     * Verifikasi kode → tandai email terverifikasi → login penuh.
     */
    public function verify(Request $request, PasswordResetOtpService $otps): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ], [
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.size' => 'Kode harus 6 digit.',
            'code.regex' => 'Kode hanya berisi angka.',
        ]);

        $user = $this->targetUser($request);

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('customer.dashboard');
        }

        $otpId = $request->session()->get('verify_otp_id');
        $otp = $otpId ? \App\Models\PasswordResetOtp::find($otpId) : null;

        if (! $otp || $otp->email !== $user->email) {
            return back()->withErrors(['code' => 'Sesi verifikasi kedaluwarsa. Minta kode baru.']);
        }

        if (! $otps->verify($otp, $request->code)) {
            if (! \App\Models\PasswordResetOtp::whereKey($otp->id)->exists()) {
                $request->session()->forget('verify_otp_id');
                return back()->withErrors(['code' => 'Kode salah 5x / kedaluwarsa. Minta kode baru.']);
            }
            return back()->withErrors(['code' => 'Kode salah. Sisa percobaan: ' . (PasswordResetOtpService::MAX_ATTEMPTS - $otp->attempts) . 'x.']);
        }

        $user->markEmailAsVerified();
        $otp->delete();
        $request->session()->forget(['verify_otp_id', 'verify_user_id']);

        return redirect()->route('customer.dashboard')->with('status', 'Email terverifikasi. Selamat datang di HOLIC Barbershop!');
    }

    /**
     * User target: yang sedang login, atau yang baru daftar (via session).
     */
    private function targetUser(Request $request): ?User
    {
        if ($request->user()) {
            return $request->user();
        }

        $id = $request->session()->get('verify_user_id');
        return $id ? User::find($id) : null;
    }
}
