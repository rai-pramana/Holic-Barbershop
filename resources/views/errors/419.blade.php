@extends('layouts.auth')

@section('title', 'Sesi Kedaluwarsa')
@section('heading', 'Sesi Kedaluwarsa')
@section('subheading', 'Halaman ini sudah terlalu lama terbuka atau sesi Anda telah berakhir.')

@section('content')
<div class="text-center">
    <div class="w-16 h-16 bg-amber-500/10 border border-amber-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="space-y-2 mt-6">
        <button onclick="window.location.reload()"
                class="w-full py-3 rounded-xl bg-white text-gray-900 text-sm font-bold hover:bg-gray-200 transition-colors">
            Muat Ulang Halaman
        </button>
        <a href="{{ route('login') }}"
           class="block w-full py-3 rounded-xl border border-white/20 text-gray-300 text-sm font-semibold hover:bg-white/10 transition-colors text-center">
            Kembali ke Login
        </a>
    </div>
</div>
@endsection
