@extends('layouts.auth')

@section('title', 'Verifikasi Kode')
@section('heading', 'Masukkan Kode OTP')
@section('subheading', 'Masukkan 6 digit kode yang kami kirim. Berlaku 10 menit.')

@section('content')
    <p class="text-gray-400 text-sm mb-6 -mt-4">Kode 6 digit dikirim ke <span class="text-white font-semibold">{{ $contact ?? 'email Anda' }}</span>. Berlaku 10 menit.</p>
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

    @include('components.otp-resend', ['resendRoute' => route('password.otp.resend')])
@endsection
