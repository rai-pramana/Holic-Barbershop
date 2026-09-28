<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendPasswordResetOtp;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Services\PasswordResetOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request, PasswordResetOtpService $otps): RedirectResponse
    {
        $request->validate([
            'contact' => ['required', 'string', 'email', 'max:255'],
        ], [
            'contact.required' => 'Email wajib diisi.',
            'contact.email' => 'Format email tidak valid.',
        ]);

        $email = strtolower(trim($request->contact));
        $user = User::where('email', $email)->first();

        $issued = $otps->issue($email, null);

        if ($user) {
            SendPasswordResetOtp::dispatch($issued['code'], $email, null, $user->name, 'reset');
        }

        $request->session()->put('reset_otp_id', $issued['otp']->id);
        $request->session()->put('reset_contact', $email);

        return redirect()->route('password.otp')->with(
            'status',
            'Jika email terdaftar, kode 6 digit telah dikirim ke email Anda. Berlaku 10 menit.'
        );
    }

    /**
     * Kirim ulang kode OTP reset untuk kontak yang sama (dari session).
     * Dibatasi cooldown 60 detik per kontak + throttle route 3/menit.
     */
    public function resendOtp(Request $request, PasswordResetOtpService $otps): RedirectResponse
    {
        $email = $request->session()->get('reset_contact');

        if (! $email) {
            return redirect()->route('password.request');
        }

        $wait = $otps->resendCooldownRemaining($email, null);
        if ($wait > 0) {
            return back()->withErrors([
                'code' => 'Tunggu ' . $wait . ' detik sebelum meminta kode baru.',
            ]);
        }

        $issued = $otps->issue($email, null);

        $user = User::where('email', $email)->first();
        if ($user) {
            SendPasswordResetOtp::dispatch($issued['code'], $email, null, $user->name, 'reset');
        }

        $request->session()->put('reset_otp_id', $issued['otp']->id);

        return back()->with(
            'status',
            'Jika email terdaftar, kode 6 digit baru telah dikirim ke email Anda. Berlaku 10 menit.'
        );
    }

    public function showOtpForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('reset_otp_id')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', [
            'contact' => $request->session()->get('reset_contact'),
        ]);
    }

    public function verifyOtp(Request $request, PasswordResetOtpService $otps): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ], [
            'code.required' => 'Kode OTP wajib diisi.',
            'code.size' => 'Kode OTP harus 6 digit.',
            'code.regex' => 'Kode OTP hanya berisi angka.',
        ]);

        $otpId = $request->session()->get('reset_otp_id');
        $otp = $otpId ? PasswordResetOtp::find($otpId) : null;

        if (! $otp) {
            return redirect()->route('password.request')->withErrors(['contact' => 'Sesi reset kedaluwarsa. Minta kode baru.']);
        }

        if (! $otps->verify($otp, $request->code)) {
            if (! PasswordResetOtp::whereKey($otp->id)->exists()) {
                $request->session()->forget(['reset_otp_id', 'reset_contact', 'reset_verified']);
                return redirect()->route('password.request')->withErrors(['contact' => 'Kode salah 5x / kedaluwarsa. Minta kode baru.']);
            }
            return back()->withErrors(['code' => 'Kode salah. Sisa percobaan: ' . (PasswordResetOtpService::MAX_ATTEMPTS - $otp->attempts) . 'x.']);
        }

        $request->session()->put('reset_verified', $otp->id);

        return redirect()->route('password.new');
    }

    public function showNewPasswordForm(Request $request): View|RedirectResponse
    {
        $otpId = $request->session()->get('reset_verified');
        $otp = $otpId ? PasswordResetOtp::find($otpId) : null;

        if (! $otp || $otp->isExpired()) {
            $otp?->delete();
            $request->session()->forget(['reset_otp_id', 'reset_contact', 'reset_verified']);
            return redirect()->route('password.request');
        }

        return view('auth.new-password');
    }

    public function storeNewPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $otpId = $request->session()->get('reset_verified');
        $otp = $otpId ? PasswordResetOtp::find($otpId) : null;

        if (! $otp || $otp->isExpired()) {
            $otp?->delete();
            $request->session()->forget(['reset_otp_id', 'reset_contact', 'reset_verified']);
            return redirect()->route('password.request')->withErrors(['contact' => 'Sesi reset kedaluwarsa. Minta kode baru.']);
        }

        $user = $otp->email ? User::where('email', $otp->email)->first() : null;

        if (! $user) {
            $otp->delete();
            $request->session()->forget(['reset_otp_id', 'reset_contact', 'reset_verified']);
            return redirect()->route('password.request')->withErrors(['contact' => 'Akun tidak ditemukan.']);
        }

        $user->forceFill(['password' => Hash::make($request->password)])->save();

        $otp->delete();
        $request->session()->forget(['reset_otp_id', 'reset_contact', 'reset_verified']);

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan masuk dengan password baru.');
    }
}
