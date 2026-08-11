@extends('layouts.admin')

@section('title', 'Kelola Antrean')
@section('page-title', '💈 Kelola Antrean')
@section('page-subtitle', 'Monitor dan kelola antrean per barber secara real-time')

@section('content')

{{-- Branch Selector + Actions --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    @foreach($branches as $branch)
    <a href="{{ route('admin.queues.manage', ['branch_id' => $branch->id]) }}"
       class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all
              {{ $selectedBranch?->id === $branch->id
                  ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow-lg shadow-pink-500/25'
                  : 'bg-white border border-gray-200 text-gray-600 hover:border-pink-300 hover:text-pink-600' }}">
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
    <p class="text-4xl mb-3">💈</p>
    <p class="font-semibold text-gray-500">Tidak ada barber aktif di cabang ini.</p>
    <a href="{{ route('admin.barbers.create') }}" class="mt-3 inline-block text-sm text-pink-500 hover:underline">+ Tambah Barber</a>
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
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
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
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <p class="font-black text-purple-900 text-xl font-mono">{{ $activeQ->queue_number }}</p>
                    <p class="text-purple-700 text-sm font-medium">{{ $activeQ->customer_name }}</p>
                    <p class="text-purple-500 text-xs">{{ $activeQ->service->name }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <form method="POST" action="{{ route('admin.queues.complete', $activeQ) }}">
                        @csrf
                        <button type="submit"
                                class="w-full bg-green-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-green-600 transition-colors">
                            ✅ Selesai
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.queues.skip', $activeQ) }}">
                        @csrf
                        <button type="submit"
                                class="w-full bg-red-100 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-200 transition-colors">
                            ⚠️ Lewati
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="text-center py-4 text-gray-300">
                <p class="text-2xl mb-1">💤</p>
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
                        <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg font-mono">{{ $q->queue_number }}</span>
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
                                class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:opacity-90 transition-opacity">
                            🔔 Panggil
                        </button>
                    </form>
                    @elseif($q->status === 'active')
                    <span class="text-xs text-blue-600 font-semibold bg-blue-50 px-2 py-1 rounded-lg">Hadir</span>
                    @else
                    <span class="text-xs text-yellow-600 font-semibold bg-yellow-50 px-2 py-1 rounded-lg">Menunggu</span>
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
    <p class="text-4xl mb-3">🏪</p>
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
