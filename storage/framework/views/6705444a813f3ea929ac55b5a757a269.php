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
        .gradient-hero { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #3b0764 100%); }
        .gradient-card { background: linear-gradient(135deg, rgba(236,72,153,0.1), rgba(139,92,246,0.1)); }
        .glow { box-shadow: 0 0 60px rgba(236,72,153,0.3), 0 0 120px rgba(139,92,246,0.2); }
        .float-anim { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .pulse-ring { animation: pulseRing 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite; }
        @keyframes pulseRing {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(236,72,153,0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 20px rgba(236,72,153,0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(236,72,153,0); }
        }
    </style>
</head>
<body class="bg-gray-950 text-white">


<nav class="fixed top-0 left-0 right-0 z-50 bg-gray-950/80 backdrop-blur-xl border-b border-white/5">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center glow">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <span class="font-bold text-lg">HOLIC <span class="text-pink-400">Barbershop</span></span>
        </div>
        <div class="flex items-center gap-4">
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-sm font-medium bg-gradient-to-r from-pink-500 to-purple-600 text-white px-5 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                        Admin Panel
                    </a>
                <?php elseif(auth()->user()->isBarber()): ?>
                    <a href="<?php echo e(route('barber.dashboard')); ?>" class="text-sm font-medium bg-gradient-to-r from-pink-500 to-purple-600 text-white px-5 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                        Barber Panel
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('customer.dashboard')); ?>" class="text-sm font-medium bg-gradient-to-r from-pink-500 to-purple-600 text-white px-5 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                        Dashboard Saya
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="text-sm text-gray-400 hover:text-white transition-colors font-medium">Masuk</a>
                <a href="<?php echo e(route('register')); ?>" class="text-sm font-semibold bg-gradient-to-r from-pink-500 to-purple-600 text-white px-5 py-2.5 rounded-xl hover:opacity-90 transition-opacity shadow-lg shadow-pink-500/25">
                    Daftar Gratis
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>


<section class="gradient-hero min-h-screen flex items-center relative overflow-hidden pt-20">
    
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-pink-500/20 rounded-full blur-3xl float-anim"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl float-anim" style="animation-delay: 3s"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-900/30 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-20 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            
            <div>
                <div class="inline-flex items-center gap-2 bg-pink-500/10 border border-pink-500/30 rounded-full px-4 py-2 text-sm text-pink-300 font-medium mb-6">
                    <span class="w-2 h-2 bg-pink-400 rounded-full pulse-ring inline-block"></span>
                    Sistem Antrean Online Tersedia
                </div>
                <h1 class="text-5xl lg:text-6xl font-black leading-tight mb-6">
                    Antri Cerdas,
                    <span class="block" style="background: linear-gradient(135deg, #ec4899, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        Tampil Keren
                    </span>
                </h1>
                <p class="text-gray-400 text-lg leading-relaxed mb-8 max-w-lg">
                    Pesan antrean di HOLIC Barbershop dari mana saja. Pilih barber favorit, pantau status real-time, dan datang tepat waktu. Tidak perlu lagi menunggu lama!
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(auth()->user()->isCustomer() ? route('customer.dashboard') : (auth()->user()->isAdmin() ? route('admin.dashboard') : route('barber.dashboard'))); ?>"
                           class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold px-8 py-4 rounded-2xl hover:opacity-90 transition-opacity shadow-xl shadow-pink-500/30 text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            Ke Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('register')); ?>"
                           class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold px-8 py-4 rounded-2xl hover:opacity-90 transition-opacity shadow-xl shadow-pink-500/30 text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            Ambil Antrean Sekarang
                        </a>
                        <a href="<?php echo e(route('login')); ?>"
                           class="inline-flex items-center justify-center gap-2 border border-white/20 text-white font-semibold px-8 py-4 rounded-2xl hover:bg-white/5 transition-colors text-center">
                            Sudah Punya Akun
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="lg:flex justify-center hidden">
                <div class="relative">
                    
                    <div class="bg-gray-900/80 backdrop-blur-xl border border-white/10 rounded-3xl p-8 w-80 shadow-2xl">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Nomor Antrean</p>
                                <p class="text-5xl font-black text-white mt-1" style="background: linear-gradient(135deg, #ec4899, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                    Q0731<br>042
                                </p>
                            </div>
                            <span class="bg-purple-500/20 text-purple-300 border border-purple-500/30 text-xs font-semibold px-3 py-1.5 rounded-full">
                                Dipanggil
                            </span>
                        </div>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Barber</span>
                                <span class="text-white font-medium">Budi Santoso</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Layanan</span>
                                <span class="text-white font-medium">Potong + Cukur</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Estimasi</span>
                                <span class="text-green-400 font-medium">Segera!</span>
                            </div>
                        </div>
                        <div class="bg-purple-500/10 border border-purple-500/30 rounded-xl p-3 text-center">
                            <p class="text-purple-300 text-sm font-medium">🎉 Anda dipanggil! Segera ke kursi.</p>
                        </div>
                    </div>

                    
                    <div class="absolute -top-4 -right-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-green-500/30">
                        Live ✓
                    </div>

                    
                    <div class="absolute -bottom-4 -left-8 bg-gray-800/60 backdrop-blur border border-white/5 rounded-2xl p-4 w-56 shadow-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                                <span class="text-yellow-400 text-sm font-bold">Q043</span>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold">Antrean berikutnya</p>
                                <p class="text-gray-400 text-xs">~30 menit lagi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-24 bg-gray-950">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Kenapa HOLIC?</h2>
            <p class="text-gray-400 max-w-xl mx-auto">Sistem antrean modern yang bikin pengalaman ke barbershop jadi lebih menyenangkan</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            $features = [
                ['icon' => '⚡', 'title' => 'Antrean Real-time', 'desc' => 'Pantau posisi antrean Anda secara langsung. Status selalu diperbarui otomatis.', 'color' => 'from-yellow-400 to-orange-500'],
                ['icon' => '✂️', 'title' => 'Pilih Barber Favorit', 'desc' => 'Pilih barber spesifik atau biarkan sistem memilih barber tercepat untuk Anda.', 'color' => 'from-pink-400 to-rose-500'],
                ['icon' => '📱', 'title' => 'Check-in Digital', 'desc' => 'Cukup klik tombol Check-in saat tiba. Tidak perlu scan QR code fisik.', 'color' => 'from-blue-400 to-cyan-500'],
                ['icon' => '🔔', 'title' => 'Notifikasi Panggilan', 'desc' => 'Halaman status otomatis memberi tahu saat nomor Anda dipanggil barber.', 'color' => 'from-purple-400 to-violet-500'],
                ['icon' => '⏰', 'title' => 'Estimasi Waktu', 'desc' => 'Ketahui perkiraan waktu tunggu berdasarkan antrian dan durasi layanan.', 'color' => 'from-green-400 to-emerald-500'],
                ['icon' => '🛡️', 'title' => 'Aman & Terverifikasi', 'desc' => 'Data antrean Anda terjamin aman. 1 akun = 1 antrean aktif per cabang.', 'color' => 'from-slate-400 to-gray-500'],
            ];
            ?>

            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group relative bg-gray-900/50 border border-white/5 rounded-2xl p-6 hover:border-pink-500/30 transition-all hover:bg-gray-900/80">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br <?php echo e($f['color']); ?> flex items-center justify-center text-xl mb-5 shadow-lg">
                    <?php echo e($f['icon']); ?>

                </div>
                <h3 class="text-white font-semibold text-lg mb-2"><?php echo e($f['title']); ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?php echo e($f['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="py-24 bg-gray-900/50">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Cara Kerja</h2>
            <p class="text-gray-400">4 langkah mudah untuk menikmati layanan HOLIC Barbershop</p>
        </div>
        <div class="grid md:grid-cols-4 gap-6">
            <?php
            $steps = [
                ['num' => '01', 'title' => 'Daftar Akun', 'desc' => 'Buat akun gratis dengan email dan nomor HP'],
                ['num' => '02', 'title' => 'Pilih Cabang', 'desc' => 'Pilih cabang HOLIC terdekat dari lokasi Anda'],
                ['num' => '03', 'title' => 'Ambil Antrean', 'desc' => 'Pilih layanan dan barber, dapatkan nomor antrean'],
                ['num' => '04', 'title' => 'Datang & Nikmati', 'desc' => 'Check-in saat tiba, tunggu panggilan, tampil keren!'],
            ];
            ?>
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-pink-500/25">
                    <span class="text-white font-black text-lg"><?php echo e($step['num']); ?></span>
                </div>
                <?php if(!$loop->last): ?>
                <div class="hidden md:block absolute top-8 left-3/4 w-1/2 h-0.5 bg-gradient-to-r from-pink-500/50 to-transparent"></div>
                <?php endif; ?>
                <h3 class="text-white font-semibold mb-2"><?php echo e($step['title']); ?></h3>
                <p class="text-gray-400 text-sm"><?php echo e($step['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="py-24 bg-gray-950">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <div class="bg-gradient-to-br from-pink-500/10 to-purple-600/10 border border-pink-500/20 rounded-3xl p-12">
            <h2 class="text-4xl font-black text-white mb-4">
                Siap tampil <span style="background: linear-gradient(135deg, #ec4899, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">keren</span>?
            </h2>
            <p class="text-gray-400 mb-8 text-lg">Daftar sekarang dan nikmati kemudahan antrean online HOLIC Barbershop.</p>
            <?php if(auth()->guard()->guest()): ?>
            <a href="<?php echo e(route('register')); ?>"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold px-10 py-4 rounded-2xl hover:opacity-90 transition-opacity shadow-xl shadow-pink-500/30 text-lg">
                Mulai Sekarang — Gratis!
            </a>
            <?php endif; ?>
            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('customer.dashboard')); ?>"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold px-10 py-4 rounded-2xl hover:opacity-90 transition-opacity shadow-xl shadow-pink-500/30 text-lg">
                Ke Dashboard Saya
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>


<footer class="bg-gray-900/50 border-t border-white/5 py-8">
    <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <span class="text-gray-400 text-sm">© <?php echo e(date('Y')); ?> HOLIC Barbershop. All rights reserved.</span>
        </div>
        <p class="text-gray-600 text-xs">Sistem Antrean Online v1.0</p>
    </div>
</footer>

</body>
</html>
<?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views/welcome.blade.php ENDPATH**/ ?>