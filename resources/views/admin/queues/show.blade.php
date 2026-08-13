@extends('layouts.admin')

@section('title', 'Detail Antrean #' . $queue->queue_number)
@section('page-title', 'Detail Antrean')
@section('page-subtitle', 'Antrean #' . $queue->queue_number . ' — ' . $queue->branch->name)

@section('page-actions')
<a href="{{ route('admin.queues.index') }}"
   class="bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-colors">
    ← Kembali
</a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Status header --}}
        <div class="p-6 text-center
            @if($queue->status === 'pending')   bg-gradient-to-br from-slate-700 to-gray-800
            @elseif($queue->status === 'active') bg-gradient-to-br from-slate-800 to-slate-900
            @elseif($queue->status === 'called') bg-gradient-to-br from-gray-900 to-slate-800
            @elseif($queue->status === 'completed') bg-gradient-to-br from-slate-600 to-gray-700
            @else bg-gradient-to-br from-gray-400 to-gray-600
            @endif text-white">
            <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-2">{{ $queue->branch->name }}</p>
            <div class="text-6xl font-black mb-3">{{ $queue->queue_number }}</div>
            <span class="inline-flex items-center px-4 py-1.5 bg-white/20 rounded-full text-sm font-semibold">
                {{ $queue->status_label }}
            </span>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">👤 Customer</p>
                    <p class="font-bold text-gray-900">{{ $queue->customer_name }}</p>
                    @if($queue->customer->phone)
                        <p class="text-xs text-gray-500">{{ $queue->customer->phone }}</p>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">💈 Barber</p>
                    <p class="font-bold text-gray-900">{{ $queue->barber?->user?->name ?? '—' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">✂️ Layanan</p>
                    <p class="font-bold text-gray-900">{{ $queue->service->name }}</p>
                    <p class="text-xs text-gray-500">{{ $queue->service->duration_minutes }} menit</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">💰 Harga</p>
                    <p class="font-bold text-gray-900">{{ $queue->service->formatted_price }}</p>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-3">Timeline</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-2 h-2 rounded-full bg-gray-600"></div>
                        <span class="text-gray-500">Dibuat:</span>
                        <span class="font-medium">{{ $queue->created_at->format('H:i') }}</span>
                    </div>
                    @if($queue->checked_in_at)
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-2 h-2 rounded-full bg-gray-200"></div>
                        <span class="text-gray-500">Divalidasi admin:</span>
                        <span class="font-medium">{{ $queue->checked_in_at->format('H:i') }}</span>
                    </div>
                    @endif
                    @if($queue->called_at)
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-2 h-2 rounded-full bg-gray-100"></div>
                        <span class="text-gray-500">Dipanggil:</span>
                        <span class="font-medium">{{ $queue->called_at->format('H:i') }}</span>
                    </div>
                    @endif
                    @if($queue->completed_at)
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                        <span class="text-gray-500">Selesai:</span>
                        <span class="font-medium">{{ $queue->completed_at->format('H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($queue->notes)
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm">
                <p class="font-medium text-gray-700 mb-1">Catatan:</p>
                <p class="text-gray-800">{{ $queue->notes }}</p>
            </div>
            @endif

            {{-- Admin shortcut: Validate if still pending --}}
            @if($queue->isPending())
            <div class="border-t border-gray-100 pt-4">
                <a href="{{ route('admin.checkin.confirm', $queue->validation_token) }}"
                   class="w-full inline-flex items-center justify-center gap-2 bg-gray-900 text-white font-semibold py-3 rounded-xl hover:bg-gray-800 transition-colors text-sm">
                    📲 Validasi Kehadiran Customer
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
