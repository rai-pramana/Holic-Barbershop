<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendPasswordResetOtp;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Services\PasswordResetOtpService;
use App\Services\WhatsAppService;
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

    public function store(Request $request, PasswordResetOtpService $otps, WhatsAppService $wa): RedirectResponse
    {
        $request->validate([
            'contact' => ['required', 'string', 'max:255'],
        ], [
            'contact.required' => 'Email atau nomor WhatsApp wajib diisi.',
        ]);

        $contact = trim($request->contact);
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL) !== false;

        $email = null;
        $phone = null;
        $user = null;

        if ($isEmail) {
            $email = strtolower($contact);
            $user = User::where('email', $email)->first();
            $phone = $user?->phone ? $wa->normalizePhone($user->phone) : null;
        } else {
            $normalized = $wa->normalizePhone($contact);
            if (! $normalized) {
                return back()->withErrors(['contact' => 'Format nomor WhatsApp tidak valid. Contoh: 0812xxxxxxx.'])->withInput();
            }
            $phone = $normalized;
            $user = User::where('phone', $normalized)
                ->orWhere('phone', $contact)
                ->orWhere('phone', ltrim($contact, '+'))
                ->first();
            $email = $user?->email;
        }

        $issued = $otps->issue($email, $phone);

        if ($user) {
            SendPasswordResetOtp::dispatch($issued['code'], $email, $phone, $user->name);
        }

        $request->session()->put('reset_otp_id', $issued['otp']->id);
        $request->session()->put('reset_contact', $contact);

        return redirect()->route('password.otp')->with(
            'status',
            'Jika data terdaftar, kode 6 digit telah dikirim ke WhatsApp/email Anda. Berlaku 10 menit.'
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

        $user = $otp->email
            ? User::where('email', $otp->email)->first()
            : User::where('phone', $otp->phone)->first();

        if (! $user && $otp->phone) {
            $user = User::where('phone', 'like', '%' . substr($otp->phone, -9))->first();
        }

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
