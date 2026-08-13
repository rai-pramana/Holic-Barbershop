@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<div class="space-y-6 md:space-y-8">

    {{-- Welcome hero --}}
    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-gray-800 rounded-2xl md:rounded-3xl p-6 md:p-8 text-white relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-48 h-48 md:w-72 md:h-72 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 md:w-48 md:h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>
        <div class="absolute top-1/2 right-12 w-16 h-16 bg-white/5 rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-gray-300 text-sm font-medium mb-1">Selamat datang kembali,</p>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight">{{ auth()->user()->name }}</h1>
                <p class="text-gray-300 text-sm mt-1">
                    <svg class="w-4 h-4 inline-block mr-1 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                </p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                {{-- Scan QR Button --}}
                <button onclick="openQrScanner()"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-sm font-semibold backdrop-blur-sm border border-white/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Scan QR
                </button>
                {{-- Avatar --}}
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white text-xl font-black">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Push Notification Banner (hidden) --}}
    @if(false){{-- hidden: push notification feature disabled --}}
    <div id="push-banner" class="hidden bg-gradient-to-r from-gray-800 to-slate-700 rounded-2xl p-4 text-white shadow-md">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <p class="font-bold text-sm" id="push-title">Aktifkan Notifikasi HP</p>
                    <p class="text-xs text-slate-700" id="push-desc">Dapatkan notifikasi otomatis saat antrean Anda dipanggil meskipun HP dikunci/aplikasi ditutup.</p>
                </div>
            </div>
            <button id="push-btn" class="w-full sm:w-auto px-4 py-2 bg-white text-slate-700 hover:bg-slate-900 font-bold text-xs rounded-xl shadow transition-colors flex-shrink-0">
                Aktifkan Sekarang
            </button>
        </div>
    </div>
    @endif

    {{-- Active Queues --}}
    @if($activeQueues->isNotEmpty())
    <section>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Antrean Aktif Anda</h2>
            <span class="text-xs bg-gray-900 text-white font-bold px-2 py-0.5 rounded-full">{{ $activeQueues->count() }}</span>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            @foreach($activeQueues as $queue)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6 card-hover">
                {{-- Header --}}
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">{{ $queue->branch->name }}</p>
                        <p class="text-4xl font-black text-gray-900 mt-0.5 font-mono">{{ $queue->queue_number }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                        @if($queue->status === 'pending')   badge-pending
                        @elseif($queue->status === 'active') badge-active
                        @elseif($queue->status === 'called') badge-called animate-pulse
                        @endif relative {{ $queue->status === 'called' ? 'pulse-ring' : '' }}">
                        @if($queue->status === 'called')
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                        @elseif($queue->status === 'active')
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        @endif
                        {{ $queue->status_label }}
                    </span>
                </div>

                {{-- Details grid --}}
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="bg-gray-50 rounded-xl px-2 py-2.5 text-center">
                        <p class="text-xs text-gray-400 mb-0.5">Layanan</p>
                        <p class="text-xs font-semibold text-gray-800 truncate">{{ $queue->service->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-2 py-2.5 text-center">
                        <p class="text-xs text-gray-400 mb-0.5">Biaya</p>
                        <p class="text-xs font-semibold text-gray-800">{{ $queue->service->formatted_price }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-2 py-2.5 text-center">
                        <p class="text-xs text-gray-400 mb-0.5">Barber</p>
                        <p class="text-xs font-semibold text-gray-800 truncate">{{ $queue->barber->name }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-2 py-2.5 text-center">
                        <p class="text-xs text-gray-400 mb-0.5">Durasi</p>
                        <p class="text-xs font-semibold text-gray-800">{{ $queue->service->duration_minutes }}m</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-2">
                    <a href="{{ route('customer.queue.status', $queue) }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-900 text-white text-sm font-semibold py-2.5 rounded-xl hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Status
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Branch Selection --}}
    <section>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Pilih Cabang</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($branches as $branch)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 card-hover group">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-5">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-100 flex items-center justify-center group-hover:from-gray-200 group-hover:to-gray-300 transition-colors">
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-700 bg-green-50 border border-green-200 px-3 py-1 rounded-full">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        Buka
                    </span>
                </div>

                <h3 class="font-bold text-gray-900 mb-2 text-base md:text-lg">{{ $branch->name }}</h3>
                <div class="space-y-1.5 mb-5">
                    <p class="text-sm text-gray-500 flex items-start gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $branch->address }}
                    </p>
                    <p class="text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $branch->open_time }} – {{ $branch->close_time }}
                    </p>
                    @if($branch->phone)
                    <p class="text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $branch->phone }}
                    </p>
                    @endif
                </div>

                @php $existingQueue = auth()->user()->activeQueue($branch->id); @endphp

                @if($existingQueue)
                    <a href="{{ route('customer.queue.status', $existingQueue) }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-700 border border-gray-300 font-semibold text-sm py-3 rounded-xl hover:bg-gray-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Antrean #{{ $existingQueue->queue_number }}
                    </a>
                @else
                    <a href="{{ route('customer.queue.take', $branch) }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-gray-900 to-slate-800 text-white font-semibold text-sm py-3 rounded-xl hover:opacity-90 transition-opacity shadow-sm shadow-gray-900/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ambil Antrean
                    </a>
                @endif
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                </div>
                <p class="text-gray-500 font-medium">Belum ada cabang yang tersedia.</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- Riwayat Antrean Link --}}
    <a href="{{ route('customer.queue.history') }}"
       class="flex items-center justify-between gap-3 bg-white border border-gray-100 rounded-2xl shadow-sm p-4 hover:border-gray-200 transition-colors group">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gray-100 group-hover:bg-gray-50 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Riwayat Antrean</p>
                <p class="text-xs text-gray-400">Lihat semua antrean Anda sebelumnya</p>
            </div>
        </div>
        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>

</div>

{{-- QR Scanner Modal --}}
<div id="qr-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <div>
                <h3 class="font-bold text-gray-900">Scan QR Check-in</h3>
                <p class="text-xs text-gray-400 mt-0.5">Arahkan kamera ke QR di loket barbershop</p>
            </div>
            <button onclick="closeQrScanner()" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4">
            <div id="qr-reader" class="rounded-xl overflow-hidden"></div>
            <p id="qr-status" class="text-center text-sm text-gray-500 mt-3">Menginisialisasi kamera...</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode = null;

function openQrScanner() {
    const modal = document.getElementById('qr-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('qr-status').textContent = 'Menginisialisasi kamera...';

    html5QrCode = new Html5Qrcode('qr-reader');

    const config = { fps: 10, qrbox: { width: 240, height: 240 } };

    html5QrCode.start(
        { facingMode: 'environment' },
        config,
        (decodedText) => {
            document.getElementById('qr-status').textContent = '✅ QR berhasil dibaca, mengalihkan...';
            // Stop scanner then redirect
            html5QrCode.stop().then(() => {
                // Only follow URLs from our domain
                try {
                    const url = new URL(decodedText);
                    const appHost = window.location.host;
                    if (url.host === appHost) {
                        window.location.href = decodedText;
                    } else {
                        document.getElementById('qr-status').textContent = '⚠️ QR tidak valid untuk aplikasi ini.';
                    }
                } catch(e) {
                    document.getElementById('qr-status').textContent = '⚠️ Format QR tidak dikenali.';
                }
            }).catch(() => {
                window.location.href = decodedText;
            });
        },
        (errorMessage) => {
            // Ignore scan errors — normal during scanning
        }
    ).then(() => {
        document.getElementById('qr-status').textContent = 'Scan QR yang ada di loket barbershop';
    }).catch((err) => {
        document.getElementById('qr-status').textContent = '❌ Tidak dapat mengakses kamera: ' + err;
    });
}

function closeQrScanner() {
    const modal = document.getElementById('qr-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    if (html5QrCode) {
        html5QrCode.stop().catch(() => {});
        html5QrCode = null;
    }
}

// Close modal if clicking backdrop
document.getElementById('qr-modal').addEventListener('click', function(e) {
    if (e.target === this) closeQrScanner();
});
</script>
@endpush

@push('scripts')
<script>
// ─── Active Queue Status Polling ───────────────────────────────────────────────
@if($activeQueues->isNotEmpty())
(function() {
    const STATUS_MESSAGES = {
        called:    { title: 'Dipanggil!',           body: 'Giliran Anda telah tiba! Segera ke barber Anda.' },
        completed: { title: 'Selesai!',              body: 'Layanan selesai. Terima kasih!' },
        skipped:   { title: 'Antrean Dilewati',      body: 'Antrean Anda dilewati. Hubungi petugas.' },
        active:    { title: 'Check-in Berhasil',     body: 'Antrean Anda aktif. Tunggu dipanggil.' },
    };

    const queues = [
        @foreach($activeQueues as $q)
        { id: {{ $q->id }}, status: '{{ $q->status }}', pollUrl: '{{ route('customer.queue.poll', $q) }}' },
        @endforeach
    ];
    const prevStatuses = {};
    queues.forEach(q => prevStatuses[q.id] = q.status);

    function playSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            [523, 659, 784].forEach((f, i) => {
                const o = ctx.createOscillator(), g = ctx.createGain();
                o.connect(g); g.connect(ctx.destination);
                o.frequency.value = f; g.gain.value = 0.2;
                o.start(ctx.currentTime + i * 0.15);
                o.stop(ctx.currentTime + i * 0.15 + 0.25);
            });
        } catch(e) {}
    }

    async function pollQueues() {
        for (const q of queues) {
            try {
                const res  = await fetch(q.pollUrl);
                const data = await res.json();
                if (data.status !== prevStatuses[q.id]) {
                    playSound();
                    prevStatuses[q.id] = data.status;
                    setTimeout(() => window.location.reload(), 1000);
                }
            } catch(e) {}
        }
    }

    setInterval(pollQueues, 10000);
})();
@endif
</script>
@endpush
@endsection
