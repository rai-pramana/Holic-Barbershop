@extends('layouts.admin')

@section('title', 'Kelola Antrean')
@section('page-title', 'Kelola Antrean')
@section('page-subtitle', 'Monitor dan kelola antrean per barber secara real-time')

@section('content')

{{-- Branch Selector + Actions --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    @foreach($branches as $branch)
    <a href="{{ route('admin.queues.manage', ['branch_id' => $branch->id]) }}"
       class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all
              {{ $selectedBranch?->id === $branch->id
                  ? 'bg-gradient-to-r from-gray-900 to-slate-800 text-white shadow-lg shadow-gray-900/20'
                  : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300 hover:text-gray-900' }}">
        {{ $branch->name }}
    </a>
    @endforeach
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 text-sm mb-5" id="flash-msg">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 text-sm mb-5">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    {{ session('error') }}
</div>
@endif

@if($selectedBranch)

@if($barbers->isEmpty())
<div class="text-center py-16 text-gray-400">
    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>
    </div>
    <p class="font-semibold text-gray-500">Tidak ada barber aktif di cabang ini.</p>
    <a href="{{ route('admin.barbers.create') }}" class="mt-3 inline-block text-sm text-gray-900 hover:underline">+ Tambah Barber</a>
</div>
@else

{{-- Barber Queue Boards --}}
<div class="grid lg:grid-cols-2 xl:grid-cols-3 gap-5" id="barber-boards">
    @foreach($barbers as $barber)
    @php
        $activeQ   = $barber->queues->where('status', 'called')->first();
        $pendingQs = $barber->queues->whereIn('status', ['active', 'pending']);
    @endphp
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" data-barber="{{ $barber->id }}">

        {{-- Barber Header --}}
        <div class="px-5 py-4 bg-gradient-to-r from-gray-900 to-gray-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-800 to-slate-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($barber->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-white font-bold truncate">{{ $barber->name }}</p>
                @if($barber->specialty)
                    <p class="text-gray-400 text-xs truncate">{{ $barber->specialty }}</p>
                @endif
            </div>
            <div class="ml-auto text-right flex-shrink-0">
                <span class="text-xs text-gray-400">Antrean</span>
                <p class="text-white font-bold text-lg">{{ $barber->queues->count() }}</p>
            </div>
        </div>

        {{-- Currently Serving --}}
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-3">Sedang Dilayani</p>
            @if($activeQ)
            <div class="bg-gray-100 border border-gray-300 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <p class="font-black text-gray-700 text-xl font-mono">{{ $activeQ->queue_number }}</p>
                    <p class="text-gray-700 text-sm font-medium">{{ $activeQ->customer_name }}</p>
                    <p class="text-gray-700 text-xs">{{ $activeQ->service->name }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <form method="POST" action="{{ route('admin.queues.complete', $activeQ) }}">
                        @csrf
                        <button type="submit"
                                class="w-full bg-gray-900 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Selesai
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.queues.skip', $activeQ) }}">
                        @csrf
                        <button type="submit"
                                class="w-full bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Lewati
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="text-center py-4 text-gray-300">
                <svg class="w-8 h-8 text-gray-200 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-xs text-gray-400">Belum ada yang dilayani</p>
            </div>
            @endif
        </div>

        {{-- Queue List --}}
        <div class="px-5 py-4">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-3">Antrean Menunggu ({{ $pendingQs->count() }})</p>

            @forelse($pendingQs->take(5) as $q)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <div class="flex-shrink-0">
                    @if($q->status === 'active')
                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg font-mono">{{ $q->queue_number }}</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg font-mono">{{ $q->queue_number }}</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $q->customer_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $q->service->name }}</p>
                </div>
                <div class="flex-shrink-0">
                    @if($q->status === 'active' && !$activeQ)
                    <form method="POST" action="{{ route('admin.queues.call', $q) }}">
                        @csrf
                        <button type="submit"
                                class="bg-gradient-to-r from-gray-900 to-slate-800 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:opacity-90 transition-opacity flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Panggil
                        </button>
                    </form>
                    @elseif($q->status === 'active')
                    <span class="text-xs text-gray-500 font-semibold bg-gray-200 px-2 py-1 rounded-lg">Hadir</span>
                    @else
                    <span class="text-xs text-gray-600 font-semibold bg-gray-50 border border-gray-200 px-2 py-1 rounded-lg">Menunggu</span>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-center text-gray-300 text-sm py-3">Tidak ada antrean menunggu</p>
            @endforelse

            @if($pendingQs->count() > 5)
            <p class="text-center text-xs text-gray-400 mt-2">+{{ $pendingQs->count() - 5 }} antrean lagi</p>
            @endif
        </div>
    </div>
    @endforeach
</div>

@endif

@else
<div class="text-center py-16 text-gray-400">
    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    </div>
    <p class="font-semibold text-gray-500">Pilih cabang di atas untuk melihat antrean.</p>
</div>
@endif

@push('scripts')
<script>
// Auto-refresh every 15 seconds
setInterval(() => {
    window.location.reload();
}, 15000);

// Auto-hide flash after 4s
setTimeout(() => {
    const flash = document.getElementById('flash-msg');
    if (flash) flash.style.transition = 'opacity 0.5s', flash.style.opacity = '0', setTimeout(() => flash.remove(), 500);
}, 4000);
</script>
@endpush
@endsection
