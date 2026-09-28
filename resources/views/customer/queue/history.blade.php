@extends('layouts.app')

@section('title', 'Riwayat Antrean')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-1 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 font-medium">Riwayat Antrean</span>
    </nav>

    {{-- Header hero (konsisten dgn Ambil Antrean) --}}
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-5 md:p-6 text-white mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-white/60 text-xs font-medium uppercase tracking-wide mb-0.5">Pelanggan</p>
                <h1 class="text-lg md:text-xl font-bold truncate">Riwayat Antrean</h1>
                <p class="text-white/60 text-sm mt-0.5">Semua antrean Anda sebelumnya</p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('customer.queue.history') }}" id="cust-history-form" class="flex gap-2 flex-wrap mb-6">
        @include('components.filter-dropdown', [
            'id' => 'c-status', 'name' => 'status', 'label' => '',
            'icon' => '<svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'options' => ['completed'=>'Selesai','skipped'=>'Dilewati','expired'=>'Kedaluwarsa'],
            'value' => request('status', ''),
            'allLabel' => 'Semua Status',
            'formId' => 'cust-history-form',
        ])
    </form>

    {{-- History List --}}
    @forelse($histories as $queue)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5 mb-4">
        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                {{-- Queue Number --}}
                <div class="w-14 sm:w-16 h-12 sm:h-14 px-1 rounded-2xl flex items-center justify-center font-black text-base sm:text-lg font-mono flex-shrink-0
                    @if($queue->status === 'completed') bg-emerald-50 text-emerald-700
                    @elseif($queue->status === 'skipped') bg-red-50 text-red-500
                    @else bg-amber-50 text-amber-600 @endif">
                    {{ $queue->queue_number }}
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-gray-900 text-sm truncate">{{ $queue->branch->name }}</p>
                        {{-- Status Badge (di samping nama cabang — tidak mengubah tinggi) --}}
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold flex-shrink-0 whitespace-nowrap
                            @if($queue->status === 'completed') bg-emerald-100 text-emerald-700
                            @elseif($queue->status === 'skipped') bg-red-100 text-red-600
                            @else bg-amber-100 text-amber-600 @endif">
                            {{ $queue->status_label }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $queue->service->name }}</p>
                    {{-- Biaya --}}
                    <p class="text-xs font-semibold text-gray-700 mt-0.5 flex items-center gap-1">
                        <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $queue->service->formatted_price }}
                    </p>
                    {{-- Barber: selalu render agar tinggi kartu seragam --}}
                    <p class="text-xs text-gray-400 mt-0.5 truncate">Barber: {{ $queue->barber?->name ?? '—' }}</p>
                </div>
        </div>

        {{-- Date + Duration --}}
        <div class="mt-4 pt-3 border-t border-gray-50 flex flex-wrap gap-4 text-xs text-gray-400">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $queue->created_at->translatedFormat('d M Y, H:i') }} WITA
            </span>
            {{-- Selesai: selalu render agar tinggi footer kartu seragam --}}
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Selesai: {{ $queue->completed_at ? $queue->completed_at->translatedFormat('H:i') . ' WITA' : '—' }}
            </span>
            {{-- Catatan: selalu render (truncate) agar tinggi kartu seragam --}}
            <span class="flex items-center gap-1 italic min-w-0">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                <span class="truncate">{{ $queue->notes ?: '—' }}</span>
            </span>
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

@push('scripts')
@include('components.filter-dropdown-script')
@endpush