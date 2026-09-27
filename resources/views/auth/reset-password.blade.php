@extends('layouts.auth')

@section('title', 'Reset Password')
@section('heading', 'Buat Password Baru')
@section('subheading', 'Masukkan password baru untuk akun Anda')

@section('content')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        @include('components.auth-input', [
            'id' => 'email', 'label' => 'Email', 'type' => 'email',
            'value' => old('email', $email), 'placeholder' => 'email@contoh.com',
            'autofocus' => true, 'extra' => 'autocomplete="email"',
        ])

        @include('components.auth-input', [
            'id' => 'password', 'label' => 'Password Baru', 'type' => 'password',
            'placeholder' => 'Minimal 8 karakter', 'extra' => 'autocomplete="new-password"',
        ])

        {{-- Konfirmasi --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-gray-800/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-500 transition-colors"
                   placeholder="Ulangi password baru">
        </div>

        <button type="submit"
                class="w-full bg-slate-600 hover:bg-slate-500 text-white font-bold py-3.5 rounded-xl active:scale-[0.98] transition-all shadow-lg">
            Reset Password
        </button>
    </form>
@endsection
