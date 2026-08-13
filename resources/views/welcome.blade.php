<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOLIC Barbershop — Antrean Online</title>
    <meta name="description" content="Sistem antrean online HOLIC Barbershop. Pilih cabang, pilih barber, ambil nomor antrean — tanpa ribet.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Hero: deep dark slate, no purple */
        .gradient-hero {
            background: linear-gradient(150deg, #0a0f1a 0%, #111827 45%, #1e293b 100%);
        }

        /* Metallic shimmer for glass cards */
        .glass-metallic {
            background: linear-gradient(135deg, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0.02) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .glass-metallic::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .float-anim { animation: float 7s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-18px); }
        }
        .float-anim-slow { animation: float 10s ease-in-out infinite; animation-delay: 4s; }

        .pulse-ring { animation: pulseRing 2.5s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite; }
        @keyframes pulseRing {
            0%   { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255,255,255,0.45); }
            70%  { transform: scale(1);    box-shadow: 0 0 0 10px rgba(255,255,255,0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255,255,255,0); }
        }

        /* Silver gradient text — visible on dark bg */
        .text-silver {
            background: linear-gradient(135deg, #e2e8f0 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card hover lift */
        .card-lift { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .card-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
    </style>
</head>
<body class="bg-gray-950 text-white overflow-x-hidden">

{{-- ── Navbar ─────────────────────────────────────────────────────────── --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-gray-950/85 backdrop-blur-xl border-b border-white/6">
    <div class="max-w-7xl mx-auto px-6 py-3.5 flex justify-between items-center">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white overflow-hidden flex-shrink-0 ring-1 ring-white/20 shadow-md">
                <img src="/images/holic-logo.png" alt="HOLIC" class="w-full h-full object-cover">
            </div>
            <span class="font-black text-lg tracking-tight">HOLIC <span class="text-gray-500 font-semibold">Barbershop</span></span>
        </a>

        {{-- Nav links --}}
        <div class="flex items-center gap-4">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold bg-white/10 border border-white/15 text-white px-5 py-2 rounded-xl hover:bg-white/15 transition-colors">
                        Admin Panel
                    </a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="text-sm font-semibold bg-white/10 border border-white/15 text-white px-5 py-2 rounded-xl hover:bg-white/15 transition-colors">
                        Dashboard Saya
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="text-sm font-bold bg-white text-gray-900 px-5 py-2 rounded-xl hover:bg-gray-100 transition-colors shadow-md inline-flex items-center justify-center text-center">
                    Daftar Gratis
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- ── Hero ──────────────────────────────────────────────────────────────── --}}
<section class="gradient-hero min-h-screen flex items-center relative overflow-hidden pt-20">
    {{-- Ambient background blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/5 w-[500px] h-[500px] bg-slate-600/10 rounded-full blur-[120px] float-anim"></div>
        <div class="absolute bottom-1/4 right-1/5 w-[400px] h-[400px] bg-slate-400/8 rounded-full blur-[100px] float-anim-slow"></div>
        {{-- Subtle grid --}}
        <div class="absolute inset-0" style="background-image: linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px); background-size: 60px 60px;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-20 relative z-10 w-full">
        <div class="grid lg:grid-cols-2 gap-20 items-center">

            {{-- Left: Text --}}
            <div>
                {{-- Status pill --}}
                <div class="inline-flex items-center gap-2.5 bg-white/5 border border-white/15 rounded-full px-4 py-2 text-sm text-gray-300 font-medium mb-8">
                    <span class="w-2 h-2 bg-white rounded-full pulse-ring inline-block flex-shrink-0"></span>
                    Sistem Antrean Online Tersedia
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl lg:text-7xl font-black leading-[1.05] tracking-tight mb-6">
                    <span class="text-white block">Antri Cerdas,</span>
                    <span class="text-silver block">Tampil Keren</span>
                </h1>

                <p class="text-gray-400 text-lg leading-relaxed mb-10 max-w-md">
                    Pesan antrean HOLIC Barbershop dari mana saja. Pilih barber favorit, pantau status real-time, datang tepat waktu.
                </p>

                {{-- CTA buttons --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    @auth
                        <a href="{{ auth()->user()->isCustomer() ? route('customer.dashboard') : (auth()->user()->isAdmin() ? route('admin.dashboard') : route('barber.dashboard')) }}"
                           class="inline-flex items-center justify-center gap-2 bg-white text-gray-900 font-bold px-8 py-4 rounded-2xl hover:bg-gray-100 transition-colors shadow-xl text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            Ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 bg-white text-gray-900 font-bold px-8 py-4 rounded-2xl hover:bg-gray-100 transition-colors shadow-xl text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            Ambil Antrean Sekarang
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 border border-white/20 text-white font-semibold px-8 py-4 rounded-2xl hover:bg-white/5 transition-colors text-center">
                            Sudah Punya Akun
                        </a>
                    @endauth
                </div>

                {{-- Trust row --}}
                <div class="flex items-center gap-6 mt-10 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Gratis selamanya
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Real-time update
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Tanpa app tambahan
                    </span>
                </div>
            </div>

            {{-- Right: Queue Card Preview --}}
            <div class="lg:flex justify-center hidden">
                <div class="relative pb-16">

                    {{-- Main card --}}
                    <div class="relative glass-metallic rounded-3xl p-8 w-80 shadow-2xl overflow-hidden">
                        {{-- Shimmer top bar --}}
                        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/25 to-transparent"></div>

                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-gray-400 text-xs font-semibold uppercase tracking-[0.15em]">Nomor Antrean</p>
                                <p class="text-5xl font-black text-white mt-2 font-mono tracking-widest leading-none">Q0008</p>
                            </div>
                            <span class="bg-white text-gray-900 text-xs font-black px-3 py-1.5 rounded-full shadow-md tracking-wide">
                                DIPANGGIL
                            </span>
                        </div>

                        <div class="space-y-3 mb-6 border-t border-white/8 pt-5">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Barber</span>
                                <span class="text-white font-semibold">Budi Santoso</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Layanan</span>
                                <span class="text-white font-semibold">Potong + Cukur</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Estimasi</span>
                                <span class="text-white font-semibold">Segera!</span>
                            </div>
                        </div>

                        <div class="bg-white/8 border border-white/12 rounded-xl p-3 text-center">
                            <p class="text-white text-sm font-semibold"><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg> Anda dipanggil! Segera ke kursi.</p>
                        </div>

                        {{-- Bottom shimmer --}}
                        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
                    </div>

                    {{-- Live badge --}}
                    <div class="absolute -top-3 -right-3 bg-white text-gray-900 text-xs font-black px-3 py-1.5 rounded-full shadow-lg ring-2 ring-gray-950 tracking-wide">
                        LIVE ✓
                    </div>

                    {{-- Second card — metallic glass --}}
                    <div class="absolute -bottom-4 -left-10 relative overflow-hidden rounded-2xl p-4 w-60 shadow-2xl"
                         style="background: linear-gradient(135deg, rgba(255,255,255,0.07) 0%, rgba(255,255,255,0.02) 100%); backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.13);">
                        {{-- Top shimmer --}}
                        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.03)); border: 1px solid rgba(255,255,255,0.15);">
                                <span class="text-white text-sm font-black font-mono">Q0009</span>
                            </div>
                            <div>
                                <p class="text-white text-xs font-bold">Antrean berikutnya</p>
                                <p class="text-gray-400 text-xs mt-0.5">~30 menit lagi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Features ──────────────────────────────────────────────────────────── --}}
<section class="py-28 bg-gray-950 relative">
    {{-- Divider line at top --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-px h-16 bg-gradient-to-b from-white/15 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-gray-500 text-sm font-semibold uppercase tracking-widest mb-3">Kenapa HOLIC?</p>
            <h2 class="text-4xl lg:text-5xl font-black text-white mb-4">Semua yang kamu butuhkan</h2>
            <p class="text-gray-400 max-w-lg mx-auto text-lg">Sistem antrean modern yang bikin pengalaman ke barbershop jadi lebih menyenangkan.</p>
        </div>

        @php
        $features = [
            [
                'shade' => 'bg-gray-900', 'ring' => 'ring-white/10',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>',
                'title' => 'Antrean Real-time',
                'desc'  => 'Pantau posisi antrean secara langsung. Status selalu diperbarui otomatis tanpa reload.',
            ],
            [
                'shade' => 'bg-gray-800', 'ring' => 'ring-white/8',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/>',
                'title' => 'Pilih Barber Favorit',
                'desc'  => 'Pilih barber spesifik atau biarkan sistem memilih barber tercepat untuk Anda.',
            ],
            [
                'shade' => 'bg-gray-700', 'ring' => 'ring-white/8',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>',
                'title' => 'Check-in Digital',
                'desc'  => 'Scan QR Code di loket untuk check-in instan. Status langsung berubah aktif.',
            ],
            [
                'shade' => 'bg-gray-600', 'ring' => 'ring-white/6',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
                'title' => 'Notifikasi Panggilan',
                'desc'  => 'Halaman status otomatis memberi tahu saat nomor Anda dipanggil barber.',
            ],
            [
                'shade' => 'bg-gray-500', 'ring' => 'ring-white/5',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'title' => 'Estimasi Waktu',
                'desc'  => 'Ketahui perkiraan waktu tunggu berdasarkan antrean dan durasi layanan.',
            ],
            [
                'shade' => 'bg-gray-400', 'ring' => 'ring-white/5',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                'title' => 'Aman & Terverifikasi',
                'desc'  => 'Data antrean terjamin aman. 1 akun = 1 antrean aktif per cabang.',
            ],
        ];
        @endphp

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($features as $f)
            <div class="card-lift group bg-white/[0.025] border border-white/6 rounded-2xl p-6 hover:border-white/15 hover:bg-white/[0.04] transition-all">
                <div class="w-12 h-12 rounded-2xl {{ $f['shade'] }} ring-1 {{ $f['ring'] }} flex items-center justify-center mb-5 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $f['icon'] !!}
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg mb-2">{{ $f['title'] }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── How it works ──────────────────────────────────────────────────────── --}}
<section class="py-28 relative" style="background: linear-gradient(180deg, #0f172a 0%, #111827 100%);">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-gray-500 text-sm font-semibold uppercase tracking-widest mb-3">Cara Kerja</p>
            <h2 class="text-4xl lg:text-5xl font-black text-white mb-4">4 langkah mudah</h2>
            <p class="text-gray-400 text-lg">Dari daftar sampai duduk di kursi barber, semua bisa dari HP.</p>
        </div>

        @php
        $steps = [
            ['num' => '01', 'title' => 'Daftar Akun',    'desc' => 'Buat akun gratis dengan email dan nomor HP Anda.'],
            ['num' => '02', 'title' => 'Pilih Cabang',   'desc' => 'Pilih cabang HOLIC terdekat dari lokasi Anda.'],
            ['num' => '03', 'title' => 'Ambil Antrean',  'desc' => 'Pilih layanan & barber, dapatkan nomor antrean.'],
            ['num' => '04', 'title' => 'Datang & Nikmati','desc' => 'Check-in saat tiba, tunggu panggilan, tampil keren!'],
        ];
        @endphp

        <div class="grid md:grid-cols-4 gap-6 relative">
            @foreach($steps as $i => $step)
            <div class="relative text-center group">
                {{-- Step number circle --}}
                <div class="w-16 h-16 rounded-2xl border border-white/15 bg-white/5 backdrop-blur flex items-center justify-center mx-auto mb-5 group-hover:border-white/30 group-hover:bg-white/8 transition-all shadow-lg">
                    <span class="text-white font-black text-xl font-mono">{{ $step['num'] }}</span>
                </div>
                {{-- Connector --}}
                @if(!$loop->last)
                <div class="hidden md:block absolute top-8 left-[60%] w-[80%] h-px bg-gradient-to-r from-white/15 to-transparent"></div>
                @endif
                <h3 class="text-white font-bold mb-2 text-base">{{ $step['title'] }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ───────────────────────────────────────────────────────────────── --}}
<section class="py-28 bg-gray-950 relative overflow-hidden">
    {{-- Ambient glow --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div class="w-[600px] h-[300px] bg-slate-700/15 rounded-full blur-[80px]"></div>
    </div>

    <div class="max-w-3xl mx-auto px-6 text-center relative z-10">
        <div class="bg-white/[0.025] border border-white/10 rounded-3xl p-12 backdrop-blur-sm relative overflow-hidden">
            {{-- Top shimmer --}}
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

            {{-- Logo --}}
            <div class="w-16 h-16 rounded-full bg-white overflow-hidden mx-auto mb-6 ring-1 ring-white/20 shadow-xl">
                <img src="/images/holic-logo.png" alt="HOLIC" class="w-full h-full object-cover">
            </div>

            <h2 class="text-4xl lg:text-5xl font-black mb-4">
                <span class="text-white">Siap tampil </span><span class="text-silver">keren</span><span class="text-white">?</span>
            </h2>
            <p class="text-gray-400 mb-8 text-lg max-w-md mx-auto">Daftar sekarang dan nikmati kemudahan antrean online HOLIC Barbershop.</p>

            @guest
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-3 bg-white text-gray-900 font-black px-10 py-4 rounded-2xl hover:bg-gray-100 transition-colors shadow-xl text-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                Mulai Sekarang — Gratis!
            </a>
            @endguest
            @auth
            <a href="{{ route('customer.dashboard') }}"
               class="inline-flex items-center gap-3 bg-white text-gray-900 font-black px-10 py-4 rounded-2xl hover:bg-gray-100 transition-colors shadow-xl text-lg">
                Ke Dashboard Saya
            </a>
            @endauth
        </div>
    </div>
</section>

{{-- ── Footer ───────────────────────────────────────────────────────────── --}}
<footer class="border-t border-white/5 py-8" style="background: #0a0f1a;">
    <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-full bg-white overflow-hidden ring-1 ring-white/10 flex-shrink-0">
                <img src="/images/holic-logo.png" alt="HOLIC" class="w-full h-full object-cover">
            </div>
            <span class="text-gray-500 text-sm">© {{ date('Y') }} HOLIC Barbershop. All rights reserved.</span>
        </div>
        <p class="text-gray-700 text-xs">Sistem Antrean Online v1.0</p>
    </div>
</footer>

</body>
</html>
