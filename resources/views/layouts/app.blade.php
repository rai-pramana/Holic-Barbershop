<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    <link rel="manifest" href="/manifest.json">
    <title>@yield('title', 'HOLIC Barbershop') — HOLIC Barbershop</title>
    <meta name="description" content="@yield('description', 'Sistem Antrean Online HOLIC Barbershop — Potong rambut tanpa ribet, antri dari mana saja.')">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-text { background: linear-gradient(135deg, #0f172a, #475569); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15); }

        /* Status badges — monochrome with readable contrast */
        .badge-pending   { background: #fef9c3; color: #713f12; border: 1px solid #fde68a; }
        .badge-active    { background: #e0f2fe; color: #0c4a6e; border: 1px solid #bae6fd; }
        .badge-called    { background: #e2e8f0; color: #1e293b; border: 1px solid #94a3b8; }
        .badge-completed { background: #f0fdf4; color: #14532d; border: 1px solid #bbf7d0; }
        .badge-skipped   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-expired   { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

        /* Nav link */
        .nav-link { display: inline-flex; align-items: center; gap: 6px; font-size: 0.875rem; font-weight: 500; color: #6b7280; padding: 6px 12px; border-radius: 10px; transition: all 0.15s; }
        .nav-link:hover { color: #0f172a; background: #f1f5f9; }
        .nav-link.active { color: #0f172a; font-weight: 700; }

        /* Mobile menu */
        #mobile-nav { display: none; }
        #mobile-nav.open { display: block; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }

        /* Card hover */
        .card-hover { transition: box-shadow 0.2s, transform 0.2s; }
        .card-hover:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.08); transform: translateY(-1px); }

        /* Pulse animation for called status */
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }
        .pulse-ring::before {
            content: ''; position: absolute; inset: 0; border-radius: inherit;
            border: 2px solid currentColor; animation: pulse-ring 1.5s ease-out infinite;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-50 flex flex-col">

{{-- Navigation --}}
<nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-md ring-1 ring-gray-200 flex-shrink-0 overflow-hidden">
                    <img src="/images/holic-logo.png" alt="HOLIC Barbershop" class="w-10 h-10 object-cover">
                </div>
                <div class="leading-tight">
                    <p class="font-black text-gray-900 text-base tracking-tight">HOLIC</p>
                    <p class="text-xs text-gray-500 font-semibold -mt-0.5">Barbershop</p>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-1">
                @auth
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('customer.dashboard') }}" class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin Panel
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Right: user + hamburger --}}
            <div class="flex items-center gap-2">
                @auth
                    {{-- User chip --}}
                    <div class="hidden sm:flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-800 to-slate-700 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors px-3 py-2 rounded-xl hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors px-3 py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold bg-gradient-to-r from-gray-900 to-slate-800 text-white px-4 py-2 rounded-xl hover:opacity-90 transition-opacity shadow-sm shadow-gray-900/10">
                        Daftar
                    </a>
                @endauth

                {{-- Mobile hamburger --}}
                @auth
                <button onclick="toggleMobileNav()" class="md:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                @endauth
            </div>
        </div>

        {{-- Mobile nav dropdown --}}
        @auth
        <div id="mobile-nav" class="md:hidden border-t border-gray-100 py-3 space-y-1">
            @if(auth()->user()->isCustomer())
                <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors" onclick="toggleMobileNav()">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard Saya
                </a>
            @endif
            <div class="px-3 py-2 flex items-center gap-2 text-sm text-gray-500">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-gray-800 to-slate-700 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                Halo, <strong class="text-gray-800">{{ auth()->user()->name }}</strong>
            </div>
        </div>
        @endauth
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success') || session('error') || session('info') || session('warning'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 space-y-2">
    @if(session('success'))
        <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-4 py-3" id="flash-success">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-4 py-3" id="flash-error">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif
    @if(session('info'))
        <div class="flex items-start gap-3 bg-gray-50 border border-gray-200 text-gray-800 rounded-2xl px-4 py-3" id="flash-info">
            <svg class="w-5 h-5 text-gray-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('info') }}</span>
        </div>
    @endif
    @if(session('warning'))
        <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl px-4 py-3" id="flash-warning">
            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('warning') }}</span>
        </div>
    @endif
</div>
@endif

{{-- Main Content --}}
<main class="@yield('main-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8') flex-1" id="live-content">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-100 mt-auto">
    <div class="max-w-7xl mx-auto px-4 py-5 flex flex-col sm:flex-row justify-between items-center gap-2">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-gray-900 to-slate-800 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-700">HOLIC Barbershop</p>
        </div>
        <p class="text-xs text-gray-400">© {{ date('Y') }} · Sistem Antrean Online v1.0</p>
    </div>
</footer>

<script>
function toggleMobileNav() {
    const nav = document.getElementById('mobile-nav');
    nav?.classList.toggle('open');
}

// Auto-dismiss flash messages
document.addEventListener('DOMContentLoaded', function() {
    ['success','error','info','warning'].forEach(type => {
        const el = document.getElementById('flash-' + type);
        if (el) {
            setTimeout(() => {
                el.style.transition = 'all 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(() => el.remove(), 400);
            }, type === 'error' ? 6000 : 4500);
        }
    });
});

// ─── Service Worker Registration (for PWA + Push Notifications) ────────────
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
}

// ─── Live Content Polling ──────────────────────────────────────────────────
(function() {
    const POLL_INTERVAL = 8000;
    let isPaused = false;

    document.addEventListener('focusin', e => {
        if (e.target.matches('input, textarea, select, [contenteditable]')) isPaused = true;
    });
    document.addEventListener('focusout', e => {
        if (e.target.matches('input, textarea, select, [contenteditable]')) isPaused = false;
    });

    async function pollContent() {
        if (isPaused || document.hidden) return;

        try {
            const url = new URL(window.location.href);
            url.searchParams.set('_poll', Date.now());
            const res = await fetch(url.toString(), {
                headers: { 'X-Live-Poll': '1' },
                cache: 'no-store'
            });
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

                // Re-run inline scripts
                current.querySelectorAll('script').forEach(oldScript => {
                    const newScript = document.createElement('script');
                    if (oldScript.src) newScript.src = oldScript.src;
                    else newScript.textContent = oldScript.textContent;
                    oldScript.replaceWith(newScript);
                });
            }
        } catch (e) { /* silent */ }
    }

    setInterval(pollContent, POLL_INTERVAL);
})();
</script>

@stack('scripts')
</body>
</html>
