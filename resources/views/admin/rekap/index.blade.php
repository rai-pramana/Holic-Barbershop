@extends('layouts.admin')

@section('title', 'Rekap Kinerja')
@section('page-title', '📊 Rekap Kinerja')
@section('page-subtitle', 'Rangkuman performa barbershop berdasarkan periode')

@section('content')

{{-- ── Filter Bar ─────────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('admin.rekap.index') }}" id="rekap-form">
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-end">

        {{-- Preset chips --}}
        <div class="flex gap-2 flex-wrap">
            @foreach(['today' => 'Hari Ini', 'yesterday' => 'Kemarin', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $label)
            <button type="button" onclick="setPreset('{{ $key }}')"
                    id="preset-{{ $key }}"
                    class="px-4 py-2 rounded-xl text-xs font-semibold border transition-all preset-btn
                           {{ $preset === $key ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white border-transparent shadow-sm' : 'border-gray-200 text-gray-600 hover:border-pink-300 hover:text-pink-600' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Custom range --}}
        <div class="flex items-center gap-2 ml-auto">
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input type="date" name="date_from" id="date_from"
                       value="{{ $preset === 'custom' ? $from->toDateString() : '' }}"
                       class="bg-transparent text-sm text-gray-700 outline-none w-34"
                       onchange="clearPreset()">
                <span class="text-gray-400 text-xs">s/d</span>
                <input type="date" name="date_to" id="date_to"
                       value="{{ $preset === 'custom' && $from->toDateString() !== $to->toDateString() ? $to->toDateString() : '' }}"
                       class="bg-transparent text-sm text-gray-700 outline-none w-34"
                       onchange="clearPreset()">
            </div>

            {{-- Branch filter --}}
            <select name="branch_id" onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 bg-gray-50 outline-none focus:border-pink-300 cursor-pointer">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>

            <input type="hidden" name="preset" id="preset-input" value="{{ $preset }}">

            <button type="submit"
                    class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-5 py-2 rounded-xl hover:opacity-90 transition-opacity shadow-sm">
                Terapkan
            </button>
        </div>
    </div>

    {{-- Active period label --}}
    <p class="mt-3 text-xs text-gray-400 font-medium">
        📅 Periode:
        <span class="text-gray-600 font-semibold">
            {{ $from->isSameDay($to) ? $from->isoFormat('dddd, D MMMM YYYY') : $from->isoFormat('D MMM YYYY') . ' — ' . $to->isoFormat('D MMM YYYY') }}
        </span>
    </p>
</div>
</form>

{{-- ── KPI Cards ───────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    @php
    $kpis = [
        ['label'=>'Total Antrean',    'value'=>$total,         'sub'=>'terdaftar',          'color'=>'from-slate-600 to-slate-500',   'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label'=>'Selesai',          'value'=>$completed,     'sub'=>'dilayani',           'color'=>'from-emerald-500 to-green-500', 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Dilewati',         'value'=>$skipped,       'sub'=>'tidak hadir',        'color'=>'from-rose-500 to-red-500',      'icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Kedaluwarsa',      'value'=>$expired,       'sub'=>'tidak check-in',     'color'=>'from-gray-500 to-slate-400',    'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Tk. Kehadiran',    'value'=>$attendRate.'%','sub'=>'check-in / daftar',  'color'=>'from-blue-500 to-cyan-500',     'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4'],
        ['label'=>'Tk. Selesai',      'value'=>$completeRate.'%','sub'=>'selesai / hadir',  'color'=>'from-violet-500 to-purple-500', 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
    ];
    @endphp
    @foreach($kpis as $kpi)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col gap-2">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $kpi['color'] }} flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $kpi['value'] }}</p>
            <p class="text-xs font-semibold text-gray-700 leading-tight">{{ $kpi['label'] }}</p>
            <p class="text-[10px] text-gray-400">{{ $kpi['sub'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Row: Avg Duration + Revenue ────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-3xl font-black text-gray-900">{{ $avgMinutes !== null ? $avgMinutes.' menit' : '—' }}</p>
            <p class="text-sm font-semibold text-gray-700">Rata-rata Durasi Layanan</p>
            <p class="text-xs text-gray-400">dari panggil → selesai</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-3xl font-black text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-sm font-semibold text-gray-700">Estimasi Pendapatan</p>
            <p class="text-xs text-gray-400">harga × jumlah selesai</p>
        </div>
    </div>
</div>

{{-- ── Hourly Chart ────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-bold text-gray-900">Distribusi Antrean per Jam</h3>
            <p class="text-xs text-gray-400 mt-0.5">
                Jam tersibuk:
                <span class="text-pink-600 font-semibold">{{ str_pad($peakHour, 2, '0', STR_PAD_LEFT) }}:00 – {{ str_pad($peakHour+1, 2, '0', STR_PAD_LEFT) }}:00</span>
                ({{ $hourlyData[$peakHour] }} antrean)
            </p>
        </div>
    </div>
    <div class="flex items-end gap-1.5 h-28">
        @foreach($hourlyData as $hour => $count)
        @php $pct = $hourlyMax > 0 ? round($count / $hourlyMax * 100) : 0; @endphp
        <div class="flex-1 flex flex-col items-center gap-1 group" title="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00 — {{ $count }} antrean">
            <div class="relative w-full">
                @if($count > 0)
                <div class="absolute bottom-0 w-full rounded-t-md transition-all
                    {{ $hour === $peakHour ? 'bg-gradient-to-t from-pink-500 to-purple-500' : 'bg-gradient-to-t from-slate-300 to-slate-200 group-hover:from-pink-300 group-hover:to-purple-300' }}"
                     style="height: {{ max(4, $pct) }}px; max-height: 96px;">
                </div>
                @endif
            </div>
            @if($hour % 4 === 0)
            <span class="text-[9px] text-gray-400 mt-1">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}</span>
            @else
            <span class="text-[9px] text-transparent">·</span>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- ── Bottom Row: Per-Barber + Per-Service ────────────────────────────────── --}}
<div class="grid lg:grid-cols-2 gap-6">

    {{-- Per-Barber --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Kinerja per Barber</h3>
            <p class="text-xs text-gray-400">Berdasarkan antrean yang ditangani</p>
        </div>
        @if($barberStats->isEmpty())
        <div class="py-12 text-center text-gray-300">
            <p class="text-3xl mb-2">💈</p>
            <p class="text-sm">Tidak ada data pada periode ini</p>
        </div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach($barberStats as $b)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $b['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $b['branch'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-gray-900">{{ $b['total'] }}<span class="text-xs text-gray-400 font-normal ml-1">antrean</span></p>
                        <p class="text-xs font-semibold {{ $b['rate'] >= 80 ? 'text-emerald-600' : ($b['rate'] >= 50 ? 'text-amber-500' : 'text-rose-500') }}">
                            {{ $b['rate'] }}% selesai
                        </p>
                    </div>
                </div>
                {{-- mini bar --}}
                <div class="flex gap-0.5 h-2 rounded-full overflow-hidden bg-gray-100">
                    @if($b['completed'] > 0)
                    <div class="bg-emerald-400 rounded-full" style="width:{{ $b['total'] > 0 ? round($b['completed']/$b['total']*100) : 0 }}%"></div>
                    @endif
                    @if($b['skipped'] > 0)
                    <div class="bg-rose-400 rounded-full" style="width:{{ $b['total'] > 0 ? round($b['skipped']/$b['total']*100) : 0 }}%"></div>
                    @endif
                    @if($b['expired'] > 0)
                    <div class="bg-gray-300 rounded-full" style="width:{{ $b['total'] > 0 ? round($b['expired']/$b['total']*100) : 0 }}%"></div>
                    @endif
                </div>
                <div class="flex gap-4 mt-1.5 text-[10px] text-gray-400">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>Selesai {{ $b['completed'] }}</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-400 inline-block"></span>Lewati {{ $b['skipped'] }}</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span>Kadaluarsa {{ $b['expired'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Per-Service --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Popularitas Layanan</h3>
            <p class="text-xs text-gray-400">Jumlah pemakaian + estimasi pendapatan</p>
        </div>
        @if($serviceStats->isEmpty())
        <div class="py-12 text-center text-gray-300">
            <p class="text-3xl mb-2">✂️</p>
            <p class="text-sm">Tidak ada data pada periode ini</p>
        </div>
        @else
        <div class="divide-y divide-gray-50">
            @php $maxService = $serviceStats->max('total') ?: 1; @endphp
            @foreach($serviceStats as $s)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $s['name'] }}</p>
                        <p class="text-xs text-gray-400">Rp {{ number_format($s['price'], 0, ',', '.') }} / sesi</p>
                    </div>
                    <div class="text-right ml-3 flex-shrink-0">
                        <p class="font-black text-gray-900">{{ $s['total'] }}<span class="text-xs text-gray-400 font-normal ml-1">×</span></p>
                        <p class="text-xs text-emerald-600 font-semibold">Rp {{ number_format($s['revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-pink-400 to-purple-500 rounded-full transition-all"
                         style="width:{{ round($s['total']/$maxService*100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
const presets = {
    today:     [formatDate(0),  formatDate(0)],
    yesterday: [formatDate(-1), formatDate(-1)],
    week:      [formatDate(-getDayOfWeek()), formatDate(6-getDayOfWeek())],
    month:     [formatDate(-(new Date().getDate()-1)), formatDate(daysInMonth()-(new Date().getDate()))],
};

function getDayOfWeek() {
    const d = new Date().getDay();
    return d === 0 ? 6 : d - 1; // Mon=0
}
function daysInMonth() {
    const d = new Date();
    return new Date(d.getFullYear(), d.getMonth()+1, 0).getDate();
}
function formatDate(offsetDays) {
    const d = new Date();
    d.setDate(d.getDate() + offsetDays);
    return d.toISOString().slice(0, 10);
}

function setPreset(key) {
    document.getElementById('preset-input').value = key;
    const [from, to] = presets[key];
    document.getElementById('date_from').value = from;
    document.getElementById('date_to').value = from === to ? '' : to;
    // Update button styles
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.className = btn.className.replace('bg-gradient-to-r from-pink-500 to-purple-600 text-white border-transparent shadow-sm', 'border-gray-200 text-gray-600 hover:border-pink-300 hover:text-pink-600');
    });
    const active = document.getElementById('preset-' + key);
    if (active) active.className = active.className.replace('border-gray-200 text-gray-600 hover:border-pink-300 hover:text-pink-600', 'bg-gradient-to-r from-pink-500 to-purple-600 text-white border-transparent shadow-sm');
    document.getElementById('rekap-form').submit();
}

function clearPreset() {
    document.getElementById('preset-input').value = 'custom';
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.className = btn.className
            .replace('bg-gradient-to-r from-pink-500 to-purple-600 text-white border-transparent shadow-sm', '')
            .replace('border-gray-200 text-gray-600', 'border-gray-200 text-gray-600');
    });
}
</script>
@endpush
