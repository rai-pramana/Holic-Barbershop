@extends('layouts.auth')

@section('title', 'Daftar')
@section('heading', 'Buat Akun Baru')
@section('subheading', 'Daftar gratis dan mulai antrean online')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        @include('components.auth-input', [
            'id' => 'name', 'label' => 'Nama Lengkap',
            'value' => old('name'), 'placeholder' => 'Nama Anda',
            'autofocus' => true, 'extra' => 'autocomplete="name"',
        ])

        @include('components.auth-input', [
            'id' => 'email', 'label' => 'Email', 'type' => 'email',
            'value' => old('email'), 'placeholder' => 'email@contoh.com',
            'extra' => 'autocomplete="email"',
        ])

        @include('components.auth-input', [
            'id' => 'phone', 'label' => 'Nomor HP',
            'value' => old('phone'), 'placeholder' => '08xxxxxxxxxx',
            'extra' => 'autocomplete="tel" inputmode="tel"',
        ])

        @include('components.auth-input', [
            'id' => 'password', 'label' => 'Password', 'type' => 'password',
            'placeholder' => 'Minimal 8 karakter', 'extra' => 'autocomplete="new-password"',
        ])

        {{-- Konfirmasi --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-gray-800/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-500 transition-colors"
                   placeholder="Ulangi password Anda">
        </div>

        <button type="submit"
                class="w-full bg-slate-600 hover:bg-slate-500 text-white font-bold py-3.5 rounded-xl active:scale-[0.98] transition-all shadow-lg">
            Buat Akun
        </button>
    </form>

    <p class="text-center text-gray-400 text-sm mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-white hover:text-gray-300 font-semibold underline underline-offset-2">Masuk di sini</a>
    </p>
@endsection
