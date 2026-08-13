@extends('layouts.app')

@section('title', 'Riwayat Antrean')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('customer.dashboard') }}"
           class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-gray-900">Riwayat Antrean</h1>
            <p class="text-sm text-gray-500">Semua antrean Anda sebelumnya</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('customer.queue.history') }}" class="flex gap-2 flex-wrap">
        <select name="status" onchange="this.form.submit()"
                class="rounded-xl border border-gray-200 bg-white text-sm px-3 py-2 text-gray-700 focus:ring-2 focus:ring-gray-500 focus:border-gray-400 hover:border-gray-300 hover:bg-gray-50 cursor-pointer transition-colors">
            <option value="">Semua Status</option>
            <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
            <option value="skipped"   @selected(request('status') === 'skipped')>Dilewati</option>
            <option value="expired"   @selected(request('status') === 'expired')>Kedaluwarsa</option>
        </select>
    </form>

    {{-- History List --}}
    @forelse($histories as $queue)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex justify-between items-start gap-3">
            <div class="flex items-center gap-4">
                {{-- Queue Number --}}
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-xl font-mono flex-shrink-0
                    @if($queue->status === 'completed') bg-emerald-50 text-emerald-700
                    @elseif($queue->status === 'skipped') bg-red-50 text-red-500
                    @else bg-amber-50 text-amber-600 @endif">
                    {{ $queue->queue_number }}
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm">{{ $queue->branch->name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $queue->service->name }}</p>
                    {{-- Biaya --}}
                    <p class="text-xs font-semibold text-gray-700 mt-0.5 flex items-center gap-1">
                        <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $queue->service->formatted_price }}
                    </p>
                    @if($queue->barber)
                    <p class="text-xs text-gray-400 mt-0.5">Barber: {{ $queue->barber->name }}</p>
                    @endif
                </div>
            </div>

            {{-- Status Badge --}}
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold flex-shrink-0
                @if($queue->status === 'completed') bg-emerald-100 text-emerald-700
                @elseif($queue->status === 'skipped') bg-red-100 text-red-600
                @else bg-amber-100 text-amber-600 @endif">
                @if($queue->status === 'completed')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                @elseif($queue->status === 'skipped')
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
                {{ $queue->status_label }}
            </span>
        </div>

        {{-- Date + Duration --}}
        <div class="mt-4 pt-3 border-t border-gray-50 flex flex-wrap gap-4 text-xs text-gray-400">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $queue->created_at->translatedFormat('d M Y, H:i') }} WITA
            </span>
            @if($queue->completed_at)
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Selesai: {{ $queue->completed_at->translatedFormat('H:i') }} WITA
            </span>
            @endif
            @if($queue->notes)
            <span class="flex items-center gap-1 italic">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                {{ $queue->notes }}
            </span>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="text-gray-500 font-medium">Belum ada riwayat antrean</p>
        <p class="text-gray-400 text-sm mt-1">Riwayat akan muncul setelah antrean Anda selesai</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($histories->hasPages())
    <div class="flex justify-center mt-4">
        {{ $histories->links() }}
    </div>
    @endif

</div>
@endsection