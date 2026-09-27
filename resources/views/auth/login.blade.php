@extends('layouts.auth')

@section('title', 'Masuk')
@section('heading', 'Selamat Datang Kembali')
@section('subheading', 'Masuk untuk mengakses antrean Anda')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        @include('components.auth-input', [
            'id' => 'email', 'label' => 'Email', 'type' => 'email',
            'value' => old('email'), 'placeholder' => 'email@contoh.com',
            'autofocus' => true, 'extra' => 'autocomplete="email"',
        ])

        @include('components.auth-input', [
            'id' => 'password', 'label' => 'Password', 'type' => 'password',
            'placeholder' => '••••••••', 'extra' => 'autocomplete="current-password"',
        ])

        {{-- Remember --}}
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-white/10 bg-gray-800 text-gray-900 focus:ring-gray-500">
                <span class="text-sm text-gray-400">Ingat saya</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-gray-300 hover:text-white font-medium underline underline-offset-2">Lupa password?</a>
        </div>

        <button type="submit"
                class="w-full bg-slate-600 hover:bg-slate-500 text-white font-bold py-3.5 rounded-xl active:scale-[0.98] transition-all shadow-lg">
            Masuk
        </button>
    </form>

    <p class="text-center text-gray-400 text-sm mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-white hover:text-gray-300 font-semibold underline underline-offset-2">Daftar sekarang</a>
    </p>
@endsection
