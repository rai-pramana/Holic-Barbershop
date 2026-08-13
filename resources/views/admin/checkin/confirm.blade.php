@extends('layouts.admin')

@section('title', 'Konfirmasi Check-in')
@section('page-title', 'Konfirmasi Kehadiran Customer')

@section('page-actions')
<a href="{{ route('admin.checkin.index') }}"
   class="bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-colors flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Kembali ke Loket
</a>
@endsection

@section('content')
<div class="max-w-xl mx-auto">

    @if(session('warning'))
    <div class="flex items-start gap-3 bg-gray-100 border border-gray-300 text-gray-700 rounded-2xl p-4 text-sm mb-6">
        <svg class="w-5 h-5 text-gray-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p>{{ session('warning') }}</p>
    </div>
    @endif

    {{-- Queue Info Card --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Header - monochrome gradient --}}
        <div class="p-6 bg-gradient-to-br from-gray-900 to-slate-700 text-white text-center">
            <p class="text-white/60 text-xs font-semibold uppercase tracking-widest mb-2">{{ $queue->branch->name }}</p>
            <div class="text-6xl font-black mb-3 font-mono tracking-widest">{{ $queue->queue_number }}</div>
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-white/15 backdrop-blur rounded-full text-sm font-semibold">
                @if($queue->isPending())
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    Menunggu Validasi
                @elseif($queue->isActive())
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Sudah Divalidasi
                @elseif($queue->isCalled())
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    Sudah Dipanggil
                @elseif($queue->isCompleted())
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Selesai
                @elseif($queue->isExpired())
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    Kedaluwarsa
                @elseif($queue->isSkipped())
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Dilewati
                @endif
            </span>
        </div>

        {{-- Customer & Booking Info --}}
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium mb-1">Customer</p>
                    <p class="font-bold text-gray-900">{{ $queue->customer_name }}</p>
                    @if($queue->customer->phone)
                        <p class="text-xs text-gray-500">{{ $queue->customer->phone }}</p>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium mb-1">Barber</p>
                    <p class="font-bold text-gray-900">{{ $queue->barber->name }}</p>
                    @if($queue->barber->specialty)
                        <p class="text-xs text-gray-500">{{ $queue->barber->specialty }}</p>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium mb-1">Layanan</p>
                    <p class="font-bold text-gray-900">{{ $queue->service->name }}</p>
                    <p class="text-xs text-gray-500">{{ $queue->service->duration_minutes }} menit</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-400 font-medium mb-1">Harga</p>
                    <p class="font-bold text-gray-900">{{ $queue->service->formatted_price }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 col-span-2">
                    <p class="text-xs text-gray-400 font-medium mb-1">Dibuat</p>
                    <p class="font-bold text-gray-900">{{ $queue->created_at->isoFormat('dddd, D MMM YYYY - HH:mm') }}</p>
                </div>
            </div>

            @if($queue->notes)
            <div class="bg-gray-100 border border-gray-200 rounded-xl p-3 text-sm">
                <p class="text-gray-500 font-medium mb-1">Catatan:</p>
                <p class="text-gray-800">{{ $queue->notes }}</p>
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
                            class="w-full bg-gray-900 text-white font-bold py-4 rounded-2xl hover:bg-gray-800 transition-colors text-base flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Validasi Kehadiran Customer
                    </button>
                </form>
                @elseif($queue->isActive())
                <div class="text-center bg-gray-50 border border-gray-200 rounded-2xl p-5">
                    <div class="w-12 h-12 bg-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-gray-900 font-semibold">Sudah divalidasi pada {{ $queue->checked_in_at?->format('H:i') }}</p>
                    <p class="text-gray-500 text-sm mt-1">Customer menunggu dipanggil barber.</p>
                </div>
                @elseif($queue->isCompleted())
                <div class="text-center bg-gray-50 border border-gray-200 rounded-2xl p-5">
                    <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <p class="text-gray-700 font-semibold">Layanan selesai pada {{ $queue->completed_at?->format('H:i') }}</p>
                </div>
                @else
                <div class="text-center bg-gray-100 border border-gray-200 rounded-2xl p-4">
                    <p class="text-gray-700 font-semibold text-sm">Antrean tidak dapat divalidasi ({{ $queue->status_label }})</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection