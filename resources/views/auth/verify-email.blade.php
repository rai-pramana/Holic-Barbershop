@extends('layouts.auth')

@section('title', 'Verifikasi Email')
@section('heading', 'Verifikasi Email Anda')
@section('subheading', 'Masukkan 6 digit kode yang kami kirim ke email Anda. Berlaku 10 menit.')

@section('content')
    <p class="text-gray-400 text-sm mb-6 -mt-4">Kode 6 digit dikirim ke <span class="text-white font-semibold">{{ $email }}</span>. Berlaku 10 menit.</p>
    @error('code')
        <div class="bg-red-500/10 border border-red-500/30 text-red-300 rounded-xl px-4 py-3 text-sm mb-5">{{ $message }}</div>
    @enderror
    <form method="POST" action="{{ route('verification.verify') }}" class="mb-4">
        @csrf

        @include('components.auth-input', [
            'id' => 'code', 'label' => 'Kode Verifikasi 6 digit', 'type' => 'text',
            'value' => '', 'placeholder' => '123456',
            'autofocus' => true, 'extra' => 'inputmode="numeric" autocomplete="one-time-code" maxlength="6"',
        ])

        <button type="submit"
                class="w-full bg-slate-600 hover:bg-slate-500 text-white font-bold py-3.5 rounded-xl active:scale-[0.98] transition-all shadow-lg">
            Verifikasi Email
        </button>
    </form>

    <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
        @csrf
        <button type="submit" id="resend-btn" class="w-full text-center text-gray-400 text-sm hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
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

    <p class="text-center text-gray-400 text-sm mt-6">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
           class="hover:text-white transition-colors">Keluar / ganti akun</a>
    </p>
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
@endsection
