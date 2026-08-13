@extends('layouts.admin')

@section('title', 'Loket Check-in')
@section('page-title', 'Loket Check-in')
@section('page-subtitle', 'Tampilkan QR kepada customer atau input manual nomor antrean')

@section('content')

{{-- Flash messages --}}
@if(session('success'))
<div class="flex items-center gap-3 bg-gray-50 border border-gray-200 text-gray-700 rounded-2xl p-4 text-sm mb-5">
    <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="flex items-center gap-3 bg-gray-50 border border-gray-200 text-gray-700 rounded-2xl p-4 text-sm mb-5">
    <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    {{ session('error') }}
</div>
@endif

<div class="grid lg:grid-cols-2 gap-6 max-w-5xl">

    {{-- ── LEFT: QR Code Display ──────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Branch selector --}}
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-3">Pilih Cabang</p>
            <div class="flex flex-wrap gap-2" id="branch-tabs">
                @foreach($branches as $i => $branch)
                <button onclick="switchBranch('{{ $branch->id }}', '{{ addslashes($branch->name) }}', '{{ route('customer.checkin.scan', $branch->id) }}')"
                        id="tab-{{ $branch->id }}"
                        class="branch-tab px-4 py-2 rounded-xl text-sm font-semibold transition-all
                               {{ $i === 0 ? 'bg-gradient-to-r from-gray-900 to-slate-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $branch->name }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- QR Code area --}}
        <div class="p-6 flex flex-col items-center text-center">
            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Minta customer scan QR ini dengan kamera HP</p>
                <p class="text-xs text-gray-400" id="branch-name">{{ $branches->first()->name ?? '-' }}</p>
            </div>

            {{-- QR Code container --}}
            <div class="relative bg-white p-4 rounded-3xl shadow-xl border-4 border-gray-900 mb-5">
                <div id="qr-canvas" class="w-56 h-56"></div>
                {{-- Logo overlay --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- URL display --}}
            <div class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 mb-4">
                <p class="text-xs text-gray-400 mb-0.5">URL Check-in</p>
                <p id="checkin-url" class="text-xs font-mono text-gray-600 break-all">{{ route('customer.checkin.scan', $branches->first()->id ?? 1) }}</p>
            </div>

            <div class="flex gap-3 w-full">
                <button onclick="refreshQR()"
                        class="flex-1 flex items-center justify-center gap-2 bg-gray-100 text-gray-600 text-sm font-semibold py-2.5 rounded-xl hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Refresh
                </button>
                <button onclick="printQR()"
                        class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-gray-900 to-slate-800 text-white text-sm font-semibold py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print QR
                </button>
            </div>
        </div>

        {{-- How-to --}}
        <div class="px-5 pb-5">
            <div class="bg-gray-200 border border-gray-300 rounded-xl p-4">
                <p class="text-xs font-bold text-gray-700 mb-2">📋 Cara Penggunaan</p>
                <ol class="text-xs text-gray-700 space-y-1 list-decimal list-inside">
                    <li>Tampilkan QR ini di layar meja loket/kasir</li>
                    <li>Customer yang sudah ambil antrean online datang ke barbershop</li>
                    <li>Customer scan QR ini menggunakan kamera HP mereka</li>
                    <li>Status antrean otomatis berubah menjadi <strong>Hadir ✅</strong></li>
                </ol>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Manual Input ─────────────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Manual search --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-xl">⌨️</div>
                <div>
                    <h3 class="font-bold text-gray-900">Input Manual</h3>
                    <p class="text-xs text-gray-400">Cari antrean dengan nomor tiket</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.checkin.search') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Antrean</label>
                    <input type="text" name="queue_number"
                           value="{{ old('queue_number') }}"
                           placeholder="cth: Q0005"
                           autofocus
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm font-mono font-bold uppercase text-gray-800 tracking-widest focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400 @error('queue_number') border-red-400 @enderror">
                    @error('queue_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cabang (opsional)</label>
                    <select name="branch_id"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                        <option value="">— Semua Cabang —</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-gray-900 to-slate-800 text-white font-semibold py-3 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg> Cari Antrean
                </button>
            </form>
        </div>

        {{-- Recent validated today --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span>✅ Tervalidasi Hari Ini</span>
                <span class="text-xs bg-gray-100 text-gray-700 font-semibold px-2 py-0.5 rounded-full">
                    {{ \App\Models\Queue::whereDate('created_at', today())->where('status', 'active')->count() +
                       \App\Models\Queue::whereDate('created_at', today())->where('status', 'called')->count() +
                       \App\Models\Queue::whereDate('created_at', today())->where('status', 'completed')->count() }}
                </span>
            </h3>
            @php
                $recent = \App\Models\Queue::with(['customer', 'branch'])
                    ->whereDate('created_at', today())
                    ->whereNotNull('checked_in_at')
                    ->orderByDesc('checked_in_at')
                    ->take(6)
                    ->get();
            @endphp
            @forelse($recent as $q)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <span class="font-mono font-bold text-xs text-gray-700 w-14">{{ $q->queue_number }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $q->customer_name }}</p>
                    <p class="text-xs text-gray-400">{{ $q->checked_in_at->format('H:i') }} · {{ $q->branch->name }}</p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                    {{ $q->status === 'called' ? 'bg-gray-100 text-gray-700' : ($q->status === 'completed' ? 'bg-gray-100 text-gray-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ $q->status_label }}
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada yang tervalidasi hari ini.</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
let currentUrl = '{{ route('customer.checkin.scan', $branches->first()->id ?? 1) }}';
let currentBranchName = '{{ addslashes($branches->first()->name ?? '') }}';
let qrInstance = null;

function generateQR(url) {
    document.getElementById('qr-canvas').innerHTML = '';
    qrInstance = new QRCode(document.getElementById('qr-canvas'), {
        text: url,
        width: 224,
        height: 224,
        colorDark: '#111827',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M,
    });
}

function switchBranch(branchId, branchName, url) {
    currentUrl = url;
    currentBranchName = branchName;

    // Update URL display
    document.getElementById('checkin-url').textContent = url;
    document.getElementById('branch-name').textContent = branchName;

    // Update tab styles
    document.querySelectorAll('.branch-tab').forEach(btn => {
        btn.className = btn.className
            .replace('bg-gradient-to-r from-gray-900 to-slate-800 text-white', '')
            .replace('bg-gray-100 text-gray-600 hover:bg-gray-200', '')
            .trim();
        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    });
    const activeTab = document.getElementById('tab-' + branchId);
    activeTab.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    activeTab.classList.add('bg-gradient-to-r', 'from-gray-900', 'to-slate-800', 'text-white');

    // Regenerate QR
    generateQR(url);
}

function refreshQR() {
    generateQR(currentUrl);
}

function printQR() {
    const canvas = document.querySelector('#qr-canvas canvas') || document.querySelector('#qr-canvas img');
    if (!canvas) { alert('QR belum siap, tunggu sebentar.'); return; }

    const dataUrl = canvas.tagName === 'CANVAS' ? canvas.toDataURL() : canvas.src;
    const win = window.open('', '_blank');
    win.document.write(`
        <!DOCTYPE html><html><head>
        <title>QR Check-in - ${currentBranchName}</title>
        <style>
          body { margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; font-family: sans-serif; background: #fff; }
          .card { text-align: center; padding: 40px; border: 3px solid #111; border-radius: 20px; max-width: 320px; }
          h1 { font-size: 1.5rem; font-weight: 900; margin: 0 0 4px; }
          p { color: #666; font-size: 0.85rem; margin: 0 0 20px; }
          img { width: 220px; height: 220px; display: block; margin: 0 auto 20px; }
          .hint { font-size: 0.75rem; color: #999; margin-top: 16px; }
        </style>
        </head><body>
        <div class="card">
          <h1>HOLIC Barbershop</h1>
          <p>${currentBranchName}</p>
          <img src="${dataUrl}" alt="QR Check-in">
          <strong>Scan untuk Check-in</strong>
          <p class="hint">Pastikan Anda sudah login ke aplikasi sebelum scan</p>
        </div>
        <script>window.onload=()=>window.print()<\/script>
        </body></html>
    `);
    win.document.close();
}

// Init on load
document.addEventListener('DOMContentLoaded', () => generateQR(currentUrl));
</script>
@endpush
@endsection
