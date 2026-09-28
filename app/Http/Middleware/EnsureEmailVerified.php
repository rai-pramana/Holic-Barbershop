<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Wajibkan verifikasi email sebelum mengakses fitur customer.
 * Admin dikecualikan (akun internal). User belum verifikasi diarahkan
 * ke halaman verifikasi OTP dengan pesan yang jelas.
 */
class EnsureEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Admin/internal lolos — verifikasi hanya untuk customer.
        if ($user->role !== 'customer') {
            return $next($request);
        }

        if (! $user->hasVerifiedEmail()) {
            // Tandai agar halaman verifikasi tahu user siapa.
            $request->session()->put('verify_user_id', $user->id);

            // Hindari redirect loop bila sudah di halaman verifikasi/logout.
            if ($request->routeIs('verification.*', 'logout')) {
                return $next($request);
            }

            return redirect()->route('verification.notice')
                ->with('status', 'Verifikasi email Anda terlebih dahulu untuk mulai antre.');
        }

        return $next($request);
    }
}
