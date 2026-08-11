@extends('layouts.admin')

@section('title', 'Konfirmasi Check-in')
@section('page-title', 'Konfirmasi Kehadiran Customer')

@section('page-actions')
<a href="{{ route('admin.checkin.index') }}"
   class="bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-colors flex items-center gap-2">
    ← Kembali ke Loket
</a>
@endsection

@section('content')
<div class="max-w-xl mx-auto">

    @if(session('warning'))
    <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-4 text-sm mb-6">
        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <p>{{ session('warning') }}</p>
    </div>
    @endif

    {{-- Queue Info Card --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-lg overflow-hidden">

        {{-- Header by status --}}
        <div class="p-6
            @if($queue->isPending()) bg-gradient-to-br from-orange-400 to-pink-500
            @elseif($queue->isActive()) bg-gradient-to-br from-green-500 to-emerald-600
            @elseif($queue->isCalled()) bg-gradient-to-br from-purple-500 to-indigo-600
            @elseif($queue->isCompleted()) bg-gradient-to-br from-gray-400 to-gray-600
            @else bg-gradient-to-br from-red-400 to-red-600
            @endif text-white text-center">

            <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-2">{{ $queue->branch->name }}</p>
            <div class="text-6xl font-black mb-2">{{ $queue->queue_number }}</div>
            <span class="inline-flex items-center px-4 py-1.5 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                @if($queue->isPending()) ⏳ Menunggu Validasi
                @elseif($queue->isActive()) ✅ Sudah Divalidasi
                @elseif($queue->isCalled()) 🔔 Sudah Dipanggil
                @elseif($queue->isCompleted()) 🎉 Selesai
                @elseif($queue->isExpired()) ⌛ Kedaluwarsa
                @elseif($queue->isSkipped()) ⚠️ Dilewati
                @endif
            </span>
        </div>

        {{-- Customer & Booking Info --}}
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">👤 Customer</p>
                    <p class="font-bold text-gray-900">{{ $queue->customer_name }}</p>
                    @if($queue->customer->phone)
                        <p class="text-xs text-gray-500">{{ $queue->customer->phone }}</p>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">💈 Barber</p>
                    <p class="font-bold text-gray-900">{{ $queue->barber->name }}</p>
                    @if($queue->barber->specialty)
                        <p class="text-xs text-gray-500">{{ $queue->barber->specialty }}</p>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">✂️ Layanan</p>
                    <p class="font-bold text-gray-900">{{ $queue->service->name }}</p>
                    <p class="text-xs text-gray-500">{{ $queue->service->duration_minutes }} menit</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">💰 Harga</p>
                    <p class="font-bold text-gray-900">{{ $queue->service->formatted_price }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 col-span-2">
                    <p class="text-xs text-gray-500 font-medium mb-1">📅 Dibuat</p>
                    <p class="font-bold text-gray-900">{{ $queue->created_at->isoFormat('dddd, D MMM YYYY — HH:mm') }}</p>
                </div>
            </div>

            @if($queue->notes)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm">
                <p class="text-yellow-700 font-medium mb-1">📝 Catatan:</p>
                <p class="text-yellow-900">{{ $queue->notes }}</p>
            </div>
            @endif

            {{-- Action Section --}}
            <div class="pt-2 border-t border-gray-100">
                @if($queue->isPending())
                <p class="text-sm text-gray-500 text-center mb-4">
                    Pastikan customer ini hadir di lokasi sebelum memvalidasi.
                </p>
                <form method="POST" action="{{ route('admin.checkin.validate', $queue) }}">
                    @csrf
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-4 rounded-2xl hover:opacity-90 transition-opacity text-base shadow-lg shadow-green-500/25 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Validasi Kehadiran Customer
                    </button>
                </form>
                @elseif($queue->isActive())
                <div class="text-center bg-green-50 border border-green-200 rounded-2xl p-5">
                    <p class="text-4xl mb-2">✅</p>
                    <p class="text-green-800 font-semibold">Sudah divalidasi pada {{ $queue->checked_in_at?->format('H:i') }}</p>
                    <p class="text-green-600 text-sm mt-1">Customer menunggu dipanggil barber.</p>
                </div>
                @elseif($queue->isCompleted())
                <div class="text-center bg-gray-50 border border-gray-200 rounded-2xl p-5">
                    <p class="text-4xl mb-2">🎉</p>
                    <p class="text-gray-700 font-semibold">Layanan selesai pada {{ $queue->completed_at?->format('H:i') }}</p>
                </div>
                @else
                <div class="text-center bg-red-50 border border-red-200 rounded-2xl p-4">
                    <p class="text-red-700 font-semibold text-sm">Antrean tidak dapat divalidasi ({{ $queue->status_label }})</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
