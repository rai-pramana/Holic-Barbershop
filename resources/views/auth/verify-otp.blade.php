@extends('layouts.auth')

@section('title', 'Verifikasi Kode')
@section('heading', 'Masukkan Kode OTP')
@section('subheading', 'Masukkan 6 digit kode yang kami kirim. Berlaku 10 menit.')

@section('content')
    <p class="text-gray-400 text-sm mb-6 -mt-4">Kode 6 digit dikirim ke <span class="text-white font-semibold">{{ $contact ?? 'WhatsApp/email Anda' }}</span>. Berlaku 10 menit.</p>
    @error('code')
        <div class="bg-red-500/10 border border-red-500/30 text-red-300 rounded-xl px-4 py-3 text-sm mb-5">{{ $message }}</div>
    @enderror
    <form method="POST" action="{{ route('password.otp.verify') }}">
        @csrf

        @include('components.auth-input', [
            'id' => 'code', 'label' => 'Kode OTP 6 digit', 'type' => 'text',
            'value' => '', 'placeholder' => '123456',
            'autofocus' => true, 'extra' => 'inputmode="numeric" autocomplete="one-time-code" maxlength="6"',
        ])

        <button type="submit"
                class="w-full bg-slate-600 hover:bg-slate-500 text-white font-bold py-3.5 rounded-xl active:scale-[0.98] transition-all shadow-lg">
            Verifikasi Kode
        </button>
    </form>

    <form method="POST" action="{{ route('password.otp.resend') }}">
        @csrf
        <button type="submit" id="resend-btn" class="w-full text-center text-gray-400 text-sm hover:text-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed mt-6" disabled>
            Tidak menerima kode? <span class="font-semibold underline underline-offset-2">Kirim ulang</span>
            <span id="resend-countdown" class="text-gray-500">(<span id="resend-secs">60</span>d)</span>
        </button>
    </form>

    <script>
    (function () {
        var secs = 60, el = document.getElementById('resend-secs'),
            btn = document.getElementById('resend-btn'),
            cd = document.getElementById('resend-countdown');
        var t = setInterval(function () {
            secs--;
            if (secs <= 0) {
                clearInterval(t);
                btn.disabled = false;
                if (cd) cd.style.display = 'none';
            } else if (el) {
                el.textContent = secs;
            }
        }, 1000);
    })();
    </script>
@endsection
