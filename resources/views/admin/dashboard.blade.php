@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan antrean hari ini — ' . now()->isoFormat('dddd, D MMMM YYYY'))

@section('content')
<div class="space-y-6">
    {{-- Stats row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
        $stats = [
            ['label' => 'Total Cabang',    'value' => $totalBranches,              'shade' => 'bg-gray-900',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m-2 0h18M9 21V9m6 12V9M9 9h6"/>'],
            ['label' => 'Total Barber',    'value' => $totalBarbers,               'shade' => 'bg-gray-700',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
            ['label' => 'Total Customer',  'value' => $totalCustomers,             'shade' => 'bg-gray-500',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
            ['label' => 'Selesai Hari Ini','value' => $statusSummary['completed'], 'shade' => 'bg-gray-400',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-medium uppercase tracking-wide">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $stat['value'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl {{ $stat['shade'] }} flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $stat['icon'] !!}
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Queue Status Summary --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4">Status Antrean Hari Ini</h3>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
            @php
            $statusCards = [
                ['key' => 'pending',   'label' => 'Menunggu',    'cls' => 'bg-yellow-50  border-yellow-200 text-yellow-800'],
                ['key' => 'active',    'label' => 'Check-in',    'cls' => 'bg-sky-50     border-sky-200    text-sky-800'],
                ['key' => 'called',    'label' => 'Dipanggil',   'cls' => 'bg-gray-900   border-gray-700   text-white'],
                ['key' => 'completed', 'label' => 'Selesai',     'cls' => 'bg-green-50   border-green-200  text-green-800'],
                ['key' => 'skipped',   'label' => 'Dilewati',    'cls' => 'bg-red-50     border-red-200    text-red-700'],
                ['key' => 'expired',   'label' => 'Kedaluwarsa', 'cls' => 'bg-gray-50    border-gray-200   text-gray-600'],
            ];
            @endphp
            @foreach($statusCards as $s)
            <div class="border rounded-xl p-3 text-center {{ $s['cls'] }}">
                <p class="text-2xl font-black">{{ $statusSummary[$s['key']] }}</p>
                <p class="text-xs font-medium mt-1">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Queues Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Antrean Terbaru Hari Ini</h3>
            <a href="{{ route('admin.queues.index') }}" class="text-sm text-gray-500 hover:text-gray-900 font-medium transition-colors">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">No. Antrean</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Customer</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Barber</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Layanan</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentQueues as $queue)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $queue->queue_number }}</td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 text-sm">{{ $queue->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $queue->branch->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $queue->barber?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $queue->service->name }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-{{ $queue->status }}">
                                {{ $queue->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $queue->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">
                            Belum ada antrean hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
        $links = [
            ['href' => route('admin.branches.create'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>',                                                                'label' => 'Tambah Cabang'],
            ['href' => route('admin.barbers.create'),  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>', 'label' => 'Tambah Barber'],
            ['href' => route('admin.services.create'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/>', 'label' => 'Tambah Layanan'],
            ['href' => route('admin.rekap.index'),     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>', 'label' => 'Rekap Kinerja'],
        ];
        @endphp
        @foreach($links as $link)
        <a href="{{ $link['href'] }}"
           class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center gap-3 hover:border-gray-900 hover:shadow-md transition-all group">
            <div class="w-9 h-9 rounded-xl bg-gray-100 group-hover:bg-gray-900 transition-colors flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-gray-700 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $link['icon'] !!}
                </svg>
            </div>
            <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition-colors">{{ $link['label'] }}</span>
        </a>
        @endforeach
    </div>
</div>
@endsection
