@extends('layouts.admin')

@section('title', 'Kelola Antrean')
@section('page-title', 'Antrean')
@section('page-subtitle', 'Monitor semua antrean')

@section('content')
{{-- Filters --}}
<form method="GET" action="{{ route('admin.queues.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Cabang</label>
            <select name="branch_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-pink-400">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-pink-400">
                <option value="">Semua Status</option>
                @foreach(['pending'=>'Menunggu','active'=>'Check-in','called'=>'Dipanggil','completed'=>'Selesai','skipped'=>'Dilewati','expired'=>'Kedaluwarsa'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
            <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}"
                   class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-pink-400">
        </div>
        <button type="submit"
                class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
            Filter
        </button>
        <a href="{{ route('admin.queues.index') }}" class="text-sm text-gray-500 hover:text-gray-700 py-2">Reset</a>
    </div>
</form>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">No. Antrean</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Customer</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Barber</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Layanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Cabang</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Dibuat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($queues as $queue)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-bold text-gray-900 text-sm">{{ $queue->queue_number }}</td>
                    <td class="px-5 py-3 text-sm">
                        <p class="font-medium text-gray-900">{{ $queue->customer->name }}</p>
                        <p class="text-xs text-gray-500">{{ $queue->customer->phone }}</p>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $queue->barber?->user?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-700">
                        <p>{{ $queue->service->name }}</p>
                        <p class="text-xs text-gray-500">{{ $queue->service->duration_minutes }} menit</p>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600">{{ $queue->branch->name }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-{{ $queue->status }}">
                            {{ $queue->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $queue->created_at->format('H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">Tidak ada antrean ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($queues->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $queues->links() }}</div>
    @endif
</div>
@endsection
