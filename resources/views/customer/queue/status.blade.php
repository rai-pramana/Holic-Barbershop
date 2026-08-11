@extends('layouts.app')

@section('title', 'Status Antrean #' . $queue->queue_number)

@section('content')
<div class="max-w-lg mx-auto">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-1 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 font-medium">Status Antrean</span>
    </nav>

    {{-- Main Status Card --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-lg overflow-hidden mb-5" id="status-card">

        {{-- Colored Header --}}
        <div class="relative overflow-hidden text-white text-center
            @if($queue->status === 'called')    bg-gradient-to-br from-purple-500 to-indigo-600
            @elseif($queue->status === 'active')  bg-gradient-to-br from-blue-500 to-cyan-600
            @elseif($queue->status === 'pending') bg-gradient-to-br from-amber-400 to-orange-500
            @elseif($queue->status === 'completed') bg-gradient-to-br from-green-500 to-emerald-600
            @else bg-gradient-to-br from-gray-400 to-gray-600
            @endif p-6 md:p-8">

            {{-- Decoration --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>

            <div class="relative z-10">
                <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-1">{{ $queue->branch->name }}</p>

                {{-- Queue Number --}}
                <div class="text-6xl md:text-7xl font-black tracking-tight mb-3 font-mono" id="queue-number">
                    {{ $queue->queue_number }}
                </div>

                {{-- Status Badge --}}
                @php
                    $statusConfig = [
                        'called'    => ['icon' => 'M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z', 'label' => 'Anda Dipanggil!', 'animate' => true],
                        'active'    => ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Check-in Berhasil — Menunggu Panggilan', 'animate' => false],
                        'pending'   => ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Menunggu Check-in', 'animate' => false],
                        'completed' => ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Layanan Selesai', 'animate' => false],
                        'skipped'   => ['icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'label' => 'Antrean Dilewati', 'animate' => false],
                        'expired'   => ['icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Antrean Kedaluwarsa', 'animate' => false],
                    ];
                    $cfg = $statusConfig[$queue->status] ?? ['icon' => '', 'label' => $queue->status_label, 'animate' => false];
                @endphp
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-4 py-2 text-sm font-semibold {{ $cfg['animate'] ? 'animate-pulse' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/></svg>
                    {{ $cfg['label'] }}
                </div>
            </div>
        </div>

        <div class="p-5 md:p-6 space-y-5">

            {{-- Stats Grid --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-gray-50 rounded-2xl p-4 col-span-1">
                    <p class="text-xs text-gray-400 font-medium mb-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        Posisi
                    </p>
                    <p class="font-bold text-gray-900 text-sm" id="queue-position">
                        @if($queue->isActive_or_Pending())
                            ke-{{ $queue->position_in_queue }}
                        @else —
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 col-span-1">
                    <p class="text-xs text-gray-400 font-medium mb-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Di Depan
                    </p>
                    <p class="font-bold text-gray-900 text-sm" id="queues-ahead">
                        @if($queue->isActive_or_Pending())
                            {{ $queuesAhead + $pendingAhead > 0 ? ($queuesAhead + $pendingAhead).' org' : 'Hampir!' }}
                        @else —
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 col-span-1">
                    <p class="text-xs text-gray-400 font-medium mb-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Est. Tunggu
                    </p>
                    <p class="font-bold text-gray-900 text-sm" id="wait-time">
                        @if($queue->isActive_or_Pending() && $waitMinutes > 0)
                            ~{{ $waitMinutes }}m
                        @elseif($queue->isActive_or_Pending())
                            Segera!
                        @else —
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 col-span-1">
                    <p class="text-xs text-gray-400 font-medium mb-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Barber
                    </p>
                    <p class="font-bold text-gray-900 text-sm">{{ $queue->barber?->name ?? '—' }}</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 col-span-2">
                    <p class="text-xs text-gray-400 font-medium mb-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Layanan
                    </p>
                    <p class="font-bold text-gray-900 text-sm leading-tight">{{ $queue->service->name }} <span class="text-xs font-normal text-gray-400">({{ $queue->service->duration_minutes }}m)</span></p>
                </div>
            </div>

            {{-- Check-in Instructions (for pending) --}}
            @if($queue->isPending())
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5">
                <div class="text-center mb-4">
                    <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1.5 rounded-full mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        Langkah Check-in
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">1</div>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Datang ke barbershop</p>
                            <p class="text-xs text-blue-600">{{ $queue->branch->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">2</div>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Scan QR Code di loket</p>
                            <p class="text-xs text-blue-600">Gunakan kamera HP untuk scan QR yang ditampilkan di meja loket</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-full bg-green-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">✓</div>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Selesai! Status berubah otomatis</p>
                            <p class="text-xs text-blue-600">Antrean Anda langsung aktif setelah scan</p>
                        </div>
                    </div>
                </div>

                @if($queue->expired_at)
                <div class="mt-4 text-center">
                    <p class="text-xs text-blue-700 font-semibold">
                        ⏰ Berlaku s.d. <span class="local-time" data-utc="{{ $queue->expired_at->toISOString() }}">{{ $queue->expired_at->format('H:i') }}</span> WITA
                    </p>
                </div>
                @endif

                <p class="text-center text-blue-800 font-mono font-black text-2xl tracking-widest mt-4">{{ $queue->queue_number }}</p>
            </div>
            @endif


            {{-- Called alert --}}
            @if($queue->isCalled())
            <div class="bg-purple-50 border-2 border-purple-300 rounded-2xl p-4 animate-pulse">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    </div>
                    <div>
                        <p class="text-purple-900 font-bold text-sm">Nomor Anda Dipanggil!</p>
                        <p class="text-purple-700 text-xs">Segera menuju kursi barber <strong>{{ $queue->barber->name }}</strong></p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-3">Riwayat Status</p>
                <div class="space-y-2.5">
                    @php
                        $timelineItems = [
                            ['dot' => 'bg-green-400', 'label' => 'Antrean dibuat', 'time' => $queue->created_at],
                            $queue->checked_in_at ? ['dot' => 'bg-blue-400', 'label' => 'Check-in tervalidasi', 'time' => $queue->checked_in_at] : null,
                            $queue->called_at ? ['dot' => 'bg-purple-400', 'label' => 'Dipanggil barber', 'time' => $queue->called_at] : null,
                            $queue->completed_at ? ['dot' => 'bg-emerald-400', 'label' => 'Layanan selesai', 'time' => $queue->completed_at] : null,
                        ];
                    @endphp
                    @foreach(array_filter($timelineItems) as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full {{ $item['dot'] }} flex-shrink-0"></div>
                        <span class="text-sm text-gray-500 flex-1">{{ $item['label'] }}</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $item['time']->format('H:i') }}</span>
                    </div>
                    @endforeach
                    @if($queue->isPending() && $queue->expired_at)
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-red-300 flex-shrink-0"></div>
                        <span class="text-sm text-gray-400 flex-1">Kedaluwarsa pada</span>
                        <span class="text-sm font-semibold text-red-500">{{ $queue->expired_at->format('H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Push Notification Subscribe (hidden) --}}
            @if(false){{-- hidden: push notification feature disabled --}}
            @if($queue->isActive_or_Pending())
            <div id="push-banner" class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 hidden">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-indigo-900" id="push-title">Aktifkan Notifikasi</p>
                        <p class="text-xs text-indigo-600" id="push-desc">Dapat notifikasi otomatis saat dipanggil</p>
                    </div>
                    <button id="push-btn" onclick="handlePushSubscription()"
                            class="flex-shrink-0 bg-indigo-600 text-white text-xs font-bold px-3 py-2 rounded-xl hover:bg-indigo-700 active:scale-95 transition-all">
                        Aktifkan
                    </button>
                </div>
            </div>
            @endif
            @endif

            {{-- Back button --}}
            <a href="{{ route('customer.dashboard') }}"
               class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-700 font-semibold py-3 rounded-2xl hover:bg-gray-200 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- Notes --}}
    @if($queue->notes)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Catatan</p>
        <p class="text-sm text-gray-700">{{ $queue->notes }}</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
// ─── Config ──────────────────────────────────────────────────────────────────
const VAPID_PUBLIC_KEY = "{{ config('webpush.vapid.public_key') }}";
const SUBSCRIBE_URL    = "{{ route('customer.push.subscribe') }}";
const UNSUBSCRIBE_URL  = "{{ route('customer.push.unsubscribe') }}";
const CSRF_TOKEN       = document.querySelector('meta[name="csrf-token"]')?.content;

// ─── Service Worker + Push Subscribe ─────────────────────────────────────────
@if($queue->isActive_or_Pending())
document.addEventListener('DOMContentLoaded', async () => {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        return;
    }

    // Register service worker
    let swReg;
    try {
        swReg = await navigator.serviceWorker.register('/sw.js');
        await navigator.serviceWorker.ready;
    } catch (e) {
        console.warn('SW register failed:', e);
        return;
    }

    const banner = document.getElementById('push-banner');
    const btn    = document.getElementById('push-btn');
    const title  = document.getElementById('push-title');
    const desc   = document.getElementById('push-desc');

    const permission = Notification.permission;

    if (permission === 'denied') {
        // Blocked — hide banner
        if (banner) banner.classList.add('hidden');
        return;
    }

    // Check existing subscription
    const sub = await swReg.pushManager.getSubscription();

    if (sub) {
        // Already subscribed — sync to server silently & show active state
        await syncSubscription(sub);
        if (banner) {
            banner.classList.remove('hidden');
            if (title) title.textContent = 'Notifikasi Aktif ✓';
            if (desc)  desc.textContent  = 'Anda akan dapat notifikasi saat dipanggil';
            if (btn) {
                btn.textContent = 'Matikan';
                btn.classList.replace('bg-indigo-600', 'bg-gray-400');
                btn.onclick = () => handleUnsubscribe(swReg, sub);
            }
        }
    } else if (permission === 'granted') {
        // Permission already granted — subscribe automatically
        await doSubscribe(swReg);
    } else {
        // Not yet asked — show banner with subscribe button
        if (banner) banner.classList.remove('hidden');
    }
});

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const raw     = window.atob(base64);
    return new Uint8Array([...raw].map(c => c.charCodeAt(0)));
}

// Sync existing subscription to server (re-save in case keys changed)
async function syncSubscription(sub) {
    try {
        await fetch(SUBSCRIBE_URL, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body:    JSON.stringify(sub.toJSON()),
        });
    } catch(e) { /* silent */ }
}

// Subscribe and save to server
async function doSubscribe(swReg) {
    try {
        const sub = await swReg.pushManager.subscribe({
            userVisibleOnly:      true,
            applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
        });
        await syncSubscription(sub);

        // Update UI
        const banner = document.getElementById('push-banner');
        const title  = document.getElementById('push-title');
        const desc   = document.getElementById('push-desc');
        const btn    = document.getElementById('push-btn');
        if (title)  title.textContent = 'Notifikasi Aktif ✓';
        if (desc)   desc.textContent  = 'Anda akan dapat notifikasi saat dipanggil';
        if (btn) {
            btn.textContent = 'Matikan';
            btn.disabled    = false;
            btn.classList.replace('bg-indigo-600', 'bg-gray-400');
            btn.onclick = () => handleUnsubscribe(swReg, sub);
        }
        if (banner) banner.classList.remove('hidden');
        return sub;
    } catch(e) {
        console.warn('Auto-subscribe failed:', e.message);
        return null;
    }
}

async function handlePushSubscription() {
    const swReg = await navigator.serviceWorker.ready;
    const btn   = document.getElementById('push-btn');

    btn.disabled    = true;
    btn.textContent = '...';

    try {
        const sub = await swReg.pushManager.subscribe({
            userVisibleOnly:      true,
            applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
        });

        // Save to server
        const res = await fetch(SUBSCRIBE_URL, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body:    JSON.stringify(sub.toJSON()),
        });

        if (res.ok) {
            const title = document.getElementById('push-title');
            const desc  = document.getElementById('push-desc');
            const banner = document.getElementById('push-banner');

            title.textContent = 'Notifikasi Aktif ✓';
            desc.textContent  = 'Anda akan dapat notifikasi saat dipanggil';
            btn.textContent   = 'Matikan';
            btn.disabled      = false;
            btn.classList.replace('bg-indigo-600', 'bg-gray-400');
            btn.onclick = () => handleUnsubscribe(swReg, sub);

            // Show brief success style
            banner.classList.add('bg-green-50', 'border-green-200');
            banner.classList.remove('bg-indigo-50', 'border-indigo-200');
            setTimeout(() => {
                banner.classList.remove('bg-green-50', 'border-green-200');
                banner.classList.add('bg-indigo-50', 'border-indigo-200');
            }, 2000);
        } else {
            throw new Error('Server rejected subscription');
        }
    } catch (e) {
        console.error('Subscribe failed:', e);
        btn.disabled    = false;
        btn.textContent = 'Coba Lagi';
        if (e.name === 'NotAllowedError') {
            document.getElementById('push-desc').textContent = 'Izin notifikasi ditolak di browser.';
        }
    }
}

async function handleUnsubscribe(swReg, sub) {
    const btn = document.getElementById('push-btn');
    btn.disabled    = true;
    btn.textContent = '...';

    await sub.unsubscribe();
    await fetch(UNSUBSCRIBE_URL, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body:    JSON.stringify({ endpoint: sub.endpoint }),
    });

    document.getElementById('push-title').textContent = 'Aktifkan Notifikasi';
    document.getElementById('push-desc').textContent  = 'Dapat notifikasi otomatis saat dipanggil';
    btn.textContent = 'Aktifkan';
    btn.disabled    = false;
    btn.classList.replace('bg-gray-400', 'bg-indigo-600');
    btn.onclick = handlePushSubscription;
}

// ─── Live Polling ──────────────────────────────────────────────────────────────
const pollUrl  = "{{ route('customer.queue.poll', $queue) }}";
let prevStatus = "{{ $queue->status }}";

// Request notification permission on first visit
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Notification sound
function playCustomerNotifSound() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const freqs = [523, 659, 784]; // C5, E5, G5 — pleasant chime
        freqs.forEach((freq, i) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.value = freq;
            gain.gain.value = 0.25;
            osc.start(ctx.currentTime + i * 0.15);
            osc.stop(ctx.currentTime + i * 0.15 + 0.3);
        });
    } catch(e) {}
}

const statusMessages = {
    'called':    { title: '📢 Giliran Anda!', body: 'Silakan menuju barber Anda sekarang.' },
    'active':    { title: '✅ Check-in Berhasil', body: 'Antrean Anda sekarang aktif. Tunggu dipanggil.' },
    'completed': { title: '🎉 Selesai!', body: 'Layanan selesai. Terima kasih telah berkunjung!' },
    'skipped':   { title: '⚠️ Antrean Dilewati', body: 'Antrean Anda dilewati. Hubungi petugas jika ada kesalahan.' },
};

async function pollStatus() {
    try {
        const res  = await fetch(pollUrl);
        const data = await res.json();

        if (data.status !== prevStatus) {
            const newStatus = data.status;

            // Send browser notification
            const msgData = statusMessages[newStatus];
            if (msgData) {
                playCustomerNotifSound();

                if (Notification.permission === 'granted') {
                    new Notification(msgData.title, {
                        body: msgData.body,
                        icon: '/icons/icon-192.png',
                        tag: 'queue-status-{{ $queue->id }}',
                        renotify: true,
                    });
                }
            }

            prevStatus = newStatus;
            if (newStatus === 'called' && navigator.vibrate) {
                navigator.vibrate([300, 100, 300, 100, 300]);
            }
            window.location.reload();
        }

        // Live-update position, queues ahead, and estimated wait time
        const aheadEl    = document.getElementById('queues-ahead');
        const waitEl     = document.getElementById('wait-time');
        const positionEl = document.getElementById('queue-position');

        if (positionEl && data.position !== undefined) {
            positionEl.textContent = data.position > 0 ? 'ke-' + data.position : '—';
        }
        if (aheadEl && data.queues_ahead !== undefined) {
            const total = (data.queues_ahead ?? 0) + (data.pending_ahead ?? 0);
            aheadEl.textContent = total > 0 ? total + ' org' : 'Hampir!';
        }
        if (waitEl && data.wait_minutes !== undefined) {
            waitEl.textContent = data.wait_minutes > 0 ? '~' + data.wait_minutes + 'm' : 'Segera!';
        }
    } catch(e) { /* silent */ }
}
setInterval(pollStatus, 10000);
@endif

// ─── Local Time Display ───────────────────────────────────────────────────────
document.querySelectorAll('.local-time').forEach(el => {
    const utc = el.dataset.utc;
    if (utc) {
        const d = new Date(utc);
        el.textContent = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
    }
});
</script>
@endpush
@endsection
