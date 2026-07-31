@extends('layouts.admin')

@section('title', $branch->name)
@section('page-title', $branch->name)
@section('page-subtitle', 'Detail cabang dan antrean hari ini')

@section('page-actions')
<a href="{{ route('admin.branches.edit', $branch) }}"
   class="bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-colors">
    Edit Cabang
</a>
@endsection

@section('content')
<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500 uppercase font-medium mb-1">Barber</p>
        <p class="text-3xl font-black text-gray-900">{{ $branch->barbers_count }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500 uppercase font-medium mb-1">Layanan</p>
        <p class="text-3xl font-black text-gray-900">{{ $branch->services_count }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <p class="text-xs text-gray-500 uppercase font-medium mb-1">Total Antrean</p>
        <p class="text-3xl font-black text-gray-900">{{ $branch->queues_count }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">Antrean Hari Ini</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">No.</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Customer</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Barber</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Layanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($todayQueues as $queue)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-5 py-3 font-bold text-gray-900 text-sm">{{ $queue->queue_number }}</td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $queue->customer->name }}</td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $queue->barber?->user?->name ?? '—' }}</td>
                    <td class="px-5 py-3 text-sm text-gray-700">{{ $queue->service->name }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-{{ $queue->status }}">
                            {{ $queue->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $queue->created_at->format('H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada antrean hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
