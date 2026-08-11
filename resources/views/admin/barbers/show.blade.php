@extends('layouts.admin')

@section('title', 'Detail Barber')
@section('page-title', $barber->name)
@section('page-subtitle', $barber->branch->name . ' · ' . ($barber->specialty ?? 'Barber'))

@section('page-actions')
<a href="{{ route('admin.barbers.edit', $barber) }}"
   class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
    Edit
</a>
@endsection

@section('content')
<div class="max-w-3xl space-y-5">

    {{-- Barber Info Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-start gap-5">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-black text-2xl flex-shrink-0">
            {{ strtoupper(substr($barber->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <h2 class="text-xl font-bold text-gray-900">{{ $barber->name }}</h2>
            @if($barber->specialty)
                <p class="text-pink-600 font-medium text-sm">{{ $barber->specialty }}</p>
            @endif
            @if($barber->phone)
                <p class="text-gray-500 text-sm mt-1">📞 {{ $barber->phone }}</p>
            @endif
            @if($barber->bio)
                <p class="text-gray-600 text-sm mt-2">{{ $barber->bio }}</p>
            @endif
        </div>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
              {{ $barber->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
            {{ $barber->is_available ? '✅ Tersedia' : '❌ Tidak Tersedia' }}
        </span>
    </div>

    {{-- Today's Queues --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Antrean Hari Ini</h3>
            <span class="text-sm text-gray-400">{{ now()->format('d M Y') }}</span>
        </div>

        @if($todayQueues->isEmpty())
        <div class="text-center py-10 text-gray-400">
            <p class="text-3xl mb-2">📋</p>
            <p class="text-sm">Belum ada antrean hari ini.</p>
        </div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach($todayQueues as $queue)
            <div class="px-6 py-3 flex items-center gap-4">
                <span class="font-mono font-bold text-gray-800 w-16">{{ $queue->queue_number }}</span>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $queue->customer_name }}</p>
                    <p class="text-xs text-gray-400">{{ $queue->service->name }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                    @if($queue->status === 'pending')   bg-yellow-100 text-yellow-700
                    @elseif($queue->status === 'active')  bg-blue-100 text-blue-700
                    @elseif($queue->status === 'called')  bg-purple-100 text-purple-700
                    @elseif($queue->status === 'completed') bg-green-100 text-green-700
                    @else bg-gray-100 text-gray-500
                    @endif">
                    {{ $queue->status_label }}
                </span>
                <a href="{{ route('admin.queues.show', $queue) }}"
                   class="text-xs text-pink-500 hover:underline">Detail</a>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
