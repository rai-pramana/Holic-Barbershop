@extends('layouts.admin')

@section('title', 'Loket Operasional')
@section('page-title', 'Loket Operasional')
@section('page-subtitle', 'Kelola antrean dan check-in customer dalam satu tampilan')

@section('content')

{{-- Branch Selector (hanya cabang yang buka) --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    @foreach($openBranches as $branch)
    <a href="{{ route('admin.queues.manage', ['branch_id' => $branch->id]) }}"
       class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all
              {{ $selectedBranch?->id === $branch->id
                  ? 'bg-gradient-to-r from-gray-900 to-slate-800 text-white shadow-lg shadow-gray-900/20'
                  : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300 hover:text-gray-900' }}">
        {{ $branch->name }}
    </a>
    @endforeach
    @if($selectedIsClosed && $selectedBranch)
    <span class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-red-50 border border-red-200 text-red-600">
        {{ $selectedBranch->name }} · Tutup ({{ $selectedBranch->open_time }}–{{ $selectedBranch->close_time }})
    </span>
    @endif
    @if($openBranches->isEmpty() && ! $selectedBranch)
    <p class="text-sm text-gray-400">Semua cabang sedang tutup.</p>
    @endif
</div>
@if($selectedIsClosed && $selectedBranch)
<div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-sm text-red-700">
    Cabang ini sudah tutup (jam {{ $selectedBranch->open_time }}–{{ $selectedBranch->close_time }}).
    Selesaikan antrean tersisa — antrean baru tidak dapat dibuat.
</div>
@endif

{{-- Flash Messages dirender oleh layouts/admin.blade.php --}}

{{-- Main Grid: left 2 (queue boards) | right 1 (check-in panel) --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- LEFT: Papan Antrean Per Barber --}}
    <div class="xl:col-span-2 space-y-4">

        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            Papan Antrean
        </h2>

        @if(!$selectedBranch)
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <p class="font-semibold text-gray-500">Pilih cabang di atas untuk melihat antrean.</p>
        </div>

        @elseif($barbers->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>
            </div>
            <p class="font-semibold text-gray-500">Tidak ada barber aktif di cabang ini.</p>
            <a href="{{ route('admin.barbers.create') }}" class="mt-3 inline-block text-sm text-gray-900 hover:underline">+ Tambah Barber</a>
        </div>

        @else
        <div class="grid sm:grid-cols-2 gap-4" id="barber-boards">
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
                        @if($barber->specialty)<p class="text-gray-400 text-xs truncate">{{ $barber->specialty }}</p>@endif
                        @if(!$barber->is_available)
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[10px] font-semibold bg-red-500/20 text-red-200 border border-red-400/30 whitespace-nowrap">Tidak Tersedia — selesaikan antrean tersisa</span>
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
                    <div class="bg-gray-100 border border-gray-300 rounded-xl p-4 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-black text-gray-700 text-xl font-mono">{{ $activeQ->queue_number }}</p>
                            <p class="text-gray-700 text-sm font-medium">{{ $activeQ->customer_name }}</p>
                            <p class="text-gray-700 text-xs">{{ $activeQ->service->name }}</p>
                            @if($activeQ->notes)
                            <p class="text-gray-600 text-xs italic mt-1 flex items-start gap-1">
                                <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                <span>{{ $activeQ->notes }}</span>
                            </p>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2">
                            <form method="POST" action="{{ route('admin.queues.complete', $activeQ) }}">
                                @csrf
                                <button type="submit" class="w-full bg-gray-900 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Selesai
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.queues.skip', $activeQ) }}">
                                @csrf
                                <button type="submit" class="w-full bg-gray-200 text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-gray-300 border border-gray-300 transition-colors flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Lewati
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
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
                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg font-mono flex-shrink-0">{{ $q->queue_number }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $q->customer_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $q->service->name }}</p>
                            @if($q->notes)
                            <p class="text-xs text-gray-500 italic truncate" title="{{ $q->notes }}">📝 {{ $q->notes }}</p>
                            @endif
                        </div>
                        <div class="flex-shrink-0">
                            @if($q->status === 'active' && !$activeQ)
                            <form method="POST" action="{{ route('admin.queues.call', $q) }}">@csrf
                                <button type="submit" class="bg-gradient-to-r from-gray-900 to-slate-800 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:opacity-90 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    Panggil
                                </button>
                            </form>
                            @elseif($q->status === 'active')
                            <span class="text-xs font-semibold bg-gray-200 text-gray-500 px-2 py-1 rounded-lg">Hadir</span>
                            @else
                            <span class="text-xs font-semibold bg-gray-50 border border-gray-200 text-gray-600 px-2 py-1 rounded-lg">Menunggu</span>
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
    </div>{{-- /left --}}

    {{-- RIGHT: Panel Check-in --}}
    <div class="xl:col-span-1 space-y-4">

        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            Loket Check-in
        </h2>

        {{-- QR Code Card (mengikuti cabang terpilih di atas) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">QR Cabang</p>
            </div>
            <div class="p-5 flex flex-col items-center text-center">
                <p class="text-xs text-gray-400 mb-1" id="branch-name">{{ $selectedBranch->name ?? $branches->first()->name ?? '-' }}</p>
                <p class="text-xs text-gray-400 mb-4">Minta customer scan QR ini · berganti otomatis tiap menit</p>
                <div class="relative bg-white p-3 rounded-2xl shadow-lg border-4 border-gray-900 mb-4">
                    <div id="qr-canvas" class="w-44 h-44"></div>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-9 h-9 rounded-xl bg-gray-900 flex items-center justify-center shadow">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 font-medium">QR baru dalam <span id="qr-countdown" class="font-bold text-gray-700">60</span>d</p>
            </div>
        </div>

        {{-- Manual Input --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Input Manual</h3>
                    <p class="text-xs text-gray-400">Cari dengan nomor tiket</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.checkin.search') }}" class="space-y-3">
                @csrf
                <input type="text" name="queue_number" value="{{ old('queue_number') }}" placeholder="cth: Q0005"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-mono font-bold uppercase tracking-widest focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400 @error('queue_number') border-red-400 @enderror">
                @error('queue_number')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                @if($selectedBranch)
                <p class="text-xs text-gray-400">Mencari di cabang <span class="font-semibold text-gray-600">{{ $selectedBranch->name }}</span></p>
                @endif
                <button type="submit" class="w-full bg-gradient-to-r from-gray-900 to-slate-800 text-white font-semibold py-2.5 rounded-xl hover:opacity-90 text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari Antrean
                </button>
            </form>
        </div>

        {{-- Tervalidasi Hari Ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-3 flex items-center justify-between">
                <span class="flex items-center gap-1.5 text-sm">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Tervalidasi Hari Ini
                </span>
                <span class="text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-0.5 rounded-full">
                    {{ \App\Models\Queue::whereDate('created_at', today())->whereNotNull('checked_in_at')->count() }}
                </span>
            </h3>
            @forelse($recent as $q)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <span class="font-mono font-bold text-xs text-gray-700 w-14 flex-shrink-0">{{ $q->queue_number }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $q->customer_name }}</p>
                    <p class="text-xs text-gray-400">{{ $q->checked_in_at->format('H:i') }} · {{ $q->branch->name }}</p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full badge badge-{{ $q->status }} flex-shrink-0">{{ $q->status_label }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada yang tervalidasi hari ini.</p>
            @endforelse
        </div>

    </div>{{-- /right --}}

</div>{{-- /main grid --}}

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
let currentBranchId = '{{ $selectedBranch->id ?? $branches->first()->id ?? 1 }}';
let currentUrl = '';
const tokenUrlTemplate = '{{ route('admin.queues.checkin-token', ['branch' => '__ID__']) }}';

async function fetchQrUrl(branchId) {
    try {
        const res = await fetch(tokenUrlTemplate.replace('__ID__', branchId), { cache: 'no-store' });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        return data.url || '';
    } catch (e) {
        return '';
    }
}

function generateQR(url) {
    document.getElementById('qr-canvas').innerHTML = '';
    new QRCode(document.getElementById('qr-canvas'), {
        text: url, width: 176, height: 176,
        colorDark: '#111827', colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M,
    });
}
async function refreshQrForCurrentBranch() {
    const url = await fetchQrUrl(currentBranchId);
    if (!url) return;
    currentUrl = url;
    generateQR(url);
    resetQrCountdown();
}
document.addEventListener('DOMContentLoaded', () => refreshQrForCurrentBranch());
// QR berputar tiap 60 detik — samakan dengan slot token server.
setInterval(() => { refreshQrForCurrentBranch(); }, 60000);
// Countdown penanda QR berikutnya (selaras slot 60 detik server).
function resetQrCountdown() {
    const el = document.getElementById('qr-countdown');
    if (!el) return;
    const remain = 60 - (Math.floor(Date.now() / 1000) % 60);
    el.textContent = remain;
}
setInterval(() => {
    const el = document.getElementById('qr-countdown');
    if (!el) return;
    let v = parseInt(el.textContent || '60', 10) - 1;
    if (v <= 0) v = 60;
    el.textContent = v;
}, 1000);
// QR ikut terganti saat poll halus me-refresh konten → regenerate.
document.addEventListener('live-content-updated', () => {
    if (document.getElementById('qr-canvas')) refreshQrForCurrentBranch();
});
// Live update ditangani poll halus layouts/admin (tiap 8 dtk, ganti #live-content
// hanya bila berubah) — tanpa reload penuh agar scroll & fokus tidak reset.
setTimeout(() => {
    const f = document.getElementById('flash-msg');
    if(f) f.style.transition='opacity 0.5s', f.style.opacity='0', setTimeout(()=>f.remove(),500);
}, 4000);
</script>
@include('components.filter-dropdown-script')
@endpush
@endsection