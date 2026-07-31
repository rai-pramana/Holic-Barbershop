<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — HOLIC Barbershop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Sidebar link */
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 12px;
            font-size: 0.875rem; font-weight: 500;
            color: rgb(148 163 184); /* slate-400 */
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-weight: 600;
        }
        .sidebar-link .icon-wrap {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .sidebar-link.active .icon-wrap {
            background: linear-gradient(135deg, #ec4899, #a855f7);
        }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-pending   { background: #fef9c3; color: #854d0e; }
        .badge-active    { background: #dbeafe; color: #1e40af; }
        .badge-called    { background: #f3e8ff; color: #6b21a8; }
        .badge-completed { background: #dcfce7; color: #14532d; }
        .badge-skipped   { background: #fee2e2; color: #991b1b; }
        .badge-expired   { background: #f1f5f9; color: #64748b; }

        /* Sidebar overlay */
        #sidebar-overlay { display: none; }
        #sidebar-overlay.open { display: block; }

        /* Mobile sidebar slide */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed; top: 0; left: 0; bottom: 0; z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }
            #sidebar.open { transform: translateX(0); }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-slate-100 flex">

{{-- Mobile overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="toggleSidebar()"></div>

{{-- Sidebar --}}
<aside id="sidebar" class="w-64 min-h-screen bg-gradient-to-b from-slate-900 to-slate-800 flex flex-col flex-shrink-0 shadow-2xl">

    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-white/10 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center shadow-lg shadow-pink-500/30 flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-black text-base tracking-tight">HOLIC</p>
                <p class="text-slate-400 text-xs">Admin Panel</p>
            </div>
        </a>
        {{-- Close btn (mobile) --}}
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon-wrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </span>
            Dashboard
        </a>

        <div class="pt-4 pb-1.5 px-3">
            <p class="text-white/30 text-[10px] font-bold uppercase tracking-[0.15em]">Operasional</p>
        </div>

        <a href="{{ route('admin.queues.manage') }}"
           class="sidebar-link {{ request()->routeIs('admin.queues.manage') ? 'active' : '' }}">
            <span class="icon-wrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            </span>
            Kelola Antrean
        </a>

        <a href="{{ route('admin.checkin.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.checkin.*') ? 'active' : '' }}">
            <span class="icon-wrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </span>
            Loket Check-in
        </a>

        <a href="{{ route('admin.queues.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.queues.index') || request()->routeIs('admin.queues.show') ? 'active' : '' }}">
            <span class="icon-wrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </span>
            Riwayat Antrean
        </a>

        <div class="pt-4 pb-1.5 px-3">
            <p class="text-white/30 text-[10px] font-bold uppercase tracking-[0.15em]">Master Data</p>
        </div>

        <a href="{{ route('admin.branches.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
            <span class="icon-wrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </span>
            Cabang
        </a>

        <a href="{{ route('admin.barbers.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.barbers.*') ? 'active' : '' }}">
            <span class="icon-wrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
            Barber
        </a>

        <a href="{{ route('admin.services.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            <span class="icon-wrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </span>
            Layanan
        </a>
    </nav>

    {{-- User info --}}
    <div class="px-3 py-4 border-t border-white/10">
        <div class="flex items-center gap-3 px-2 mb-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-400 text-xs">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link w-full text-xs mt-1">
                <span class="icon-wrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </span>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Main Content --}}
<div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

    {{-- Top bar --}}
    <header class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 flex justify-between items-center sticky top-0 z-30">
        <div class="flex items-center gap-3">
            {{-- Hamburger (mobile) --}}
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-colors -ml-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-base md:text-xl font-bold text-gray-900 leading-tight">@yield('page-title', 'Dashboard')</h1>
                @hasSection('page-subtitle')
                <p class="text-xs md:text-sm text-gray-500 hidden sm:block">@yield('page-subtitle')</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            @yield('page-actions')
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success') || session('error') || session('warning') || session('info'))
    <div class="px-4 md:px-8 pt-4">
        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="flex items-center gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl px-4 py-3 text-sm">
                <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('warning') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 text-sm">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('info') }}</span>
            </div>
        @endif
    </div>
    @endif

    {{-- Page Content --}}
    <main class="flex-1 p-4 md:p-8">
        @yield('content')
    </main>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
    document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
}
// Close on Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.classList.contains('open')) toggleSidebar();
    }
});
</script>
@stack('scripts')
</body>
</html>
