@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('heading', 'Lupa Password?')
@section('subheading', 'Masukkan email atau nomor WhatsApp akun Anda. Kami akan mengirimkan tautan untuk mereset password.')

@section('content')
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @include('components.auth-input', [
            'id' => 'contact', 'label' => 'Email / No. WhatsApp', 'type' => 'text',
            'value' => old('contact'), 'placeholder' => 'email@contoh.com / 0812xxxxxxx',
            'autofocus' => true, 'extra' => 'autocomplete="username"',
        ])

        <button type="submit"
                class="w-full bg-slate-600 hover:bg-slate-500 text-white font-bold py-3.5 rounded-xl active:scale-[0.98] transition-all shadow-lg">
            Kirim Tautan Reset
        </button>
    </form>

    <p class="text-center text-gray-400 text-sm mt-6">
        Ingat password Anda?
        <a href="{{ route('login') }}" class="text-white hover:text-gray-300 font-semibold underline underline-offset-2">Kembali masuk</a>
    </p>
@endsection
