@extends('layouts.barber')

@section('title', 'Dashboard Barber')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-5 text-center">
        <p class="text-3xl font-black text-white">{{ $pendingQueues->count() }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Menunggu</p>
    </div>
    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-5 text-center">
        <p class="text-3xl font-black text-green-400">{{ $completedToday }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Selesai</p>
    </div>
    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-5 text-center">
        <p class="text-3xl font-black text-red-400">{{ $skippedToday }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Dilewati</p>
    </div>
</div>

{{-- Active / Called Queue --}}
@if($activeQueue)
<div class="mb-8">
    <h2 class="text-lg font-bold text-white mb-3">Antrean Aktif Sekarang</h2>
    <div class="bg-gradient-to-br
        {{ $activeQueue->status === 'called' ? 'from-purple-900/80 to-indigo-900/80 border-purple-500/50' : 'from-blue-900/80 to-cyan-900/80 border-blue-500/50' }}
        border rounded-2xl p-6">

        <div class="flex justify-between items-start mb-5">
            <div>
                <p class="text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">Nomor Antrean</p>
                <p class="text-5xl font-black text-white">{{ $activeQueue->queue_number }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                {{ $activeQueue->status === 'called' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/40' : 'bg-blue-500/20 text-blue-300 border border-blue-500/40' }}">
                @if($activeQueue->status === 'called') 🔔 Dipanggil @else ✅ Check-in @endif
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p class="text-gray-500 mb-1">Customer</p>
                <p class="text-white font-semibold">{{ $activeQueue->customer_name }}</p>
                @if($activeQueue->customer->phone)
                    <p class="text-gray-400 text-xs">{{ $activeQueue->customer->phone }}</p>
                @endif
            </div>
            <div>
                <p class="text-gray-500 mb-1">Layanan</p>
                <p class="text-white font-semibold">{{ $activeQueue->service->name }}</p>
                <p class="text-gray-400 text-xs">{{ $activeQueue->service->duration_minutes }} menit • {{ $activeQueue->service->formatted_price }}</p>
            </div>
            @if($activeQueue->notes)
            <div class="col-span-2">
                <p class="text-gray-500 mb-1">Catatan Customer</p>
                <p class="text-gray-300 text-sm bg-gray-800/50 rounded-lg p-2">{{ $activeQueue->notes }}</p>
            </div>
            @endif
            @if($activeQueue->called_at)
            <div>
                <p class="text-gray-500 mb-1">Dipanggil pukul</p>
                <p class="text-white font-semibold">{{ $activeQueue->called_at->format('H:i') }}</p>
                <p class="text-xs {{ $activeQueue->called_at->diffInMinutes(now()) >= 5 ? 'text-red-400' : 'text-yellow-400' }}">
                    {{ $activeQueue->called_at->diffInMinutes(now()) }} menit lalu
                </p>
            </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3">
            @if($activeQueue->status === 'active')
            <form method="POST" action="{{ route('barber.queues.call', $activeQueue) }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    📣 Panggil Customer
                </button>
            </form>
            @endif

            @if($activeQueue->status === 'called')
            <form method="POST" action="{{ route('barber.queues.complete', $activeQueue) }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    ✅ Selesai
                </button>
            </form>
            @endif

            @if(in_array($activeQueue->status, ['called', 'active']))
            <form method="POST" action="{{ route('barber.queues.skip', $activeQueue) }}"
                  onsubmit="return confirm('Lewati antrean ini? Customer akan ditandai tidak hadir.')"
                  class="{{ $activeQueue->status === 'active' ? '' : 'flex-1' }}">
                @csrf
                <button type="submit"
                        class="w-full {{ $activeQueue->status === 'active' ? 'px-4' : '' }} bg-red-500/20 text-red-300 border border-red-500/30 font-bold py-3.5 rounded-xl hover:bg-red-500/30 transition-colors text-sm">
                    ⏭ Skip
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endif

{{-- Queue List --}}
<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-white">Antrean Hari Ini</h2>
        <button onclick="window.location.reload()" class="text-sm text-gray-400 hover:text-white transition-colors flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh
        </button>
    </div>

    @if($todayQueues->isEmpty())
    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-12 text-center">
        <p class="text-5xl mb-4">✂️</p>
        <p class="text-gray-400">Belum ada antrean hari ini.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($todayQueues->sortBy('id') as $queue)
        @if(!in_array($queue->status, ['completed', 'skipped', 'expired']))
        <div class="bg-gray-900 border border-gray-700/50 rounded-2xl p-4 flex items-center justify-between
            @if(in_array($queue->status, ['active', 'called'])) border-blue-500/30 bg-blue-900/10 @endif">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl
                    @if($queue->status === 'called') bg-purple-500/20 text-purple-300
                    @elseif($queue->status === 'active') bg-blue-500/20 text-blue-300
                    @else bg-gray-700 text-gray-400
                    @endif
                    flex items-center justify-center font-bold text-sm">
                    {{ $queue->queue_number }}
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">{{ $queue->customer_name }}</p>
                    <p class="text-gray-400 text-xs">{{ $queue->service->name }} — {{ $queue->service->duration_minutes }} menit</p>
                </div>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                @if($queue->status === 'pending') bg-yellow-500/15 text-yellow-400 border border-yellow-500/30
                @elseif($queue->status === 'active') bg-blue-500/15 text-blue-400 border border-blue-500/30
                @elseif($queue->status === 'called') bg-purple-500/15 text-purple-400 border border-purple-500/30
                @endif">
                {{ $queue->status_label }}
            </span>
        </div>
        @endif
        @endforeach

        {{-- Completed section --}}
        @php $doneQueues = $todayQueues->whereIn('status', ['completed', 'skipped', 'expired']); @endphp
        @if($doneQueues->isNotEmpty())
        <div class="pt-3 border-t border-gray-800">
            <p class="text-xs text-gray-600 uppercase font-medium tracking-wide mb-3">Selesai / Dilewati</p>
            @foreach($doneQueues->sortByDesc('id') as $queue)
            <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-3 flex items-center justify-between mb-2 opacity-60">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 text-gray-500 flex items-center justify-center font-bold text-xs">
                        {{ $queue->queue_number }}
                    </div>
                    <p class="text-gray-400 text-sm">{{ $queue->customer_name }}</p>
                </div>
                <span class="text-xs font-medium text-gray-500">{{ $queue->status_label }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif
</div>

@push('scripts')
<script>
// Auto-refresh every 30 seconds
setTimeout(() => window.location.reload(), 30000);
</script>
@endpush
@endsection
