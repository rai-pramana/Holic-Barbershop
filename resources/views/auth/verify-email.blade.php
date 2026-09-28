@extends('layouts.auth')

@section('title', 'Verifikasi Email')
@section('heading', 'Verifikasi Email Anda')
@section('subheading', 'Kode 6 digit dikirim ke {{ $email }}. Berlaku 10 menit.')

@section('content')
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

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full text-center text-gray-400 text-sm hover:text-white transition-colors">
            Tidak menerima kode? <span class="font-semibold underline underline-offset-2">Kirim ulang</span>
        </button>
    </form>

    <p class="text-center text-gray-400 text-sm mt-6">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
           class="hover:text-white transition-colors">Keluar / ganti akun</a>
    </p>
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
@endsection
