<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Barber') - HOLIC Barbershop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .badge { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold; }
        .badge-pending   { @apply bg-yellow-100 text-yellow-800; }
        .badge-active    { @apply bg-blue-100 text-blue-800; }
        .badge-called    { @apply bg-amber-100 text-amber-800; }
        .badge-completed { @apply bg-green-100 text-green-800; }
        .badge-skipped   { @apply bg-red-100 text-red-800; }
        .badge-expired   { @apply bg-gray-100 text-gray-600; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-950 text-white">

{{-- Barber top nav --}}
<nav class="bg-gray-900 border-b border-gray-800 px-6 py-3 flex justify-between items-center">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center shadow-md ring-2 ring-amber-500/40 flex-shrink-0 overflow-hidden">
            <img src="/images/holic-logo.png" alt="HOLIC" class="w-9 h-9 object-cover">
        </div>
        <div>
            <p class="font-bold text-white text-sm">HOLIC Barbershop</p>
            <p class="text-amber-400 text-xs">Barber Panel — {{ auth()->user()->name }}</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('barber.dashboard') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-red-400 transition-colors">Keluar</button>
        </form>
    </div>
</nav>

{{-- Flash --}}
@if(session('success') || session('error'))
<div class="max-w-5xl mx-auto px-6 pt-4">
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl px-4 py-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl px-4 py-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-7V6a1 1 0 012 0v5a1 1 0 01-2 0zm0 3a1 1 0 102 0 1 1 0 00-2 0z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif
</div>
@endif

<main class="max-w-5xl mx-auto px-6 py-8" id="live-content">
    @yield('content')
</main>

<script>
// ─── Live Content Polling ──────────────────────────────────────────────────
(function() {
    const POLL_INTERVAL = 8000;
    let isPaused = false;

    document.addEventListener('focusin', e => {
        if (e.target.matches('input, textarea, select')) isPaused = true;
    });
    document.addEventListener('focusout', e => {
        if (e.target.matches('input, textarea, select')) isPaused = false;
    });

    async function pollContent() {
        if (isPaused || document.hidden) return;
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('_poll', Date.now());
            const res = await fetch(url.toString(), { headers: { 'X-Live-Poll': '1' }, cache: 'no-store' });
            if (!res.ok) return;
            const html = await res.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('#live-content');
            if (!newContent) return;
            const current = document.getElementById('live-content');
            if (current.innerHTML.trim() !== newContent.innerHTML.trim()) {
                const scrollY = window.scrollY;
                current.innerHTML = newContent.innerHTML;
                window.scrollTo(0, scrollY);
                current.querySelectorAll('script').forEach(s => {
                    const ns = document.createElement('script');
                    if (s.src) ns.src = s.src; else ns.textContent = s.textContent;
                    s.replaceWith(ns);
                });
            }
        } catch (e) {}
    }
    setInterval(pollContent, POLL_INTERVAL);
})();
</script>

@stack('scripts')
</body>
</html>
