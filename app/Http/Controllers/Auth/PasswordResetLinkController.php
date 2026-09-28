<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'contact' => ['required', 'string', 'max:255'],
        ], [
            'contact.required' => 'Email atau nomor WhatsApp wajib diisi.',
        ]);

        $contact = trim($request->contact);

        // Tentukan: email atau nomor WA?
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL) !== false;

        if ($isEmail) {
            $request->merge(['email' => strtolower($contact)]);
            $request->validate([
                'email' => ['email', 'max:255'],
            ], [
                'email.email' => 'Format email tidak valid.',
            ]);

            $status = Password::sendResetLink($request->only('email'));
        } else {
            $status = $this->sendResetLinkViaWhatsApp($contact);
        }

        // Pesan generik untuk cegah enumerasi akun terdaftar
        return back()->with('status', 'Jika data terdaftar di sistem kami, tautan reset password telah dikirim. Silakan cek inbox email/spam atau WhatsApp Anda.');
    }

    /**
     * Kirim tautan reset password via WhatsApp Fonnte.
     * Return Password::RESET_LINK_SENT agar alur sama dengan email.
     */
    protected function sendResetLinkViaWhatsApp(string $phone): string
    {
        $wa = app(WhatsAppService::class);
        $normalized = $wa->normalizePhone($phone);

        if (! $normalized) {
            return Password::RESET_LINK_SENT;
        }

        $user = User::where('phone', $normalized)
            ->orWhere('phone', $phone)
            ->orWhere('phone', ltrim($phone, '+'))
            ->first();

        if (! $user || ! $user->phone) {
            return Password::RESET_LINK_SENT;
        }

        $token = Password::createToken($user);

        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset(),
        ], false));

        // ASCII saja — Fonnte menolak karakter non-UTF8 (mis. em-dash).
        $message = "Halo, {$user->name}!\n\n"
            . "Anda meminta reset password akun HOLIC Barbershop.\n"
            . "Klik tautan berikut (berlaku 60 menit):\n"
            . $url . "\n\n"
            . "Jika Anda tidak meminta, abaikan pesan ini.\n\n"
            . "_HOLIC Barbershop_";

        $wa->send($user->phone, $message);

        return Password::RESET_LINK_SENT;
    }
}
