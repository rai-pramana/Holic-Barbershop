<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendPasswordResetOtp;
use App\Models\User;
use App\Services\PasswordResetOtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request, PasswordResetOtpService $otps): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone'    => ['required', 'string', 'max:20', 'regex:/^[0-9+()\-\s]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Tandai agar halaman verifikasi tahu user siapa, lalu arahkan
        // ke verifikasi OTP (bukan langsung dashboard).
        $request->session()->put('verify_user_id', $user->id);

        // Kirim OTP LANGSUNG saat daftar — user tiba di halaman verifikasi
        // dengan kode sudah terkirim (tidak perlu klik "Kirim ulang").
        $issued = $otps->issue($user->email, null);
        $request->session()->put('verify_otp_id', $issued['otp']->id);
        SendPasswordResetOtp::dispatch($issued['code'], $user->email, null, $user->name, 'verify');

        return redirect()->route('verification.notice')->with(
            'status',
            'Kode verifikasi 6 digit dikirim ke ' . $user->email . '. Berlaku 10 menit.'
        );
    }
}
