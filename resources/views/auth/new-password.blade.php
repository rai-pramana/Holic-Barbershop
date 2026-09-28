@extends('layouts.auth')

@section('title', 'Password Baru')
@section('heading', 'Buat Password Baru')
@section('subheading', 'Kode terverifikasi. Silakan buat password baru untuk akun Anda.')

@section('content')
    <form method="POST" action="{{ route('password.new.store') }}">
        @csrf

        @include('components.auth-input', [
            'id' => 'password', 'label' => 'Password Baru', 'type' => 'password',
            'value' => '', 'placeholder' => 'Minimal 8 karakter',
            'autofocus' => true, 'extra' => 'autocomplete="new-password"',
        ])

        @include('components.auth-input', [
            'id' => 'password_confirmation', 'label' => 'Konfirmasi Password', 'type' => 'password',
            'value' => '', 'placeholder' => 'Ulangi password baru',
            'autofocus' => false, 'extra' => 'autocomplete="new-password"',
        ])

        <button type="submit"
                class="w-full bg-slate-600 hover:bg-slate-500 text-white font-bold py-3.5 rounded-xl active:scale-[0.98] transition-all shadow-lg">
            Simpan Password Baru
        </button>
    </form>
@endsection
