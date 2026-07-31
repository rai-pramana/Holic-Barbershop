<?php $__env->startSection('title', 'Dashboard Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 md:space-y-8">

    
    <div class="bg-gradient-to-br from-pink-500 via-pink-600 to-purple-700 rounded-2xl md:rounded-3xl p-6 md:p-8 text-white relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-48 h-48 md:w-72 md:h-72 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 md:w-48 md:h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>
        <div class="absolute top-1/2 right-12 w-16 h-16 bg-white/5 rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-pink-100 text-sm font-medium mb-1">Selamat datang kembali,</p>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight"><?php echo e(auth()->user()->name); ?></h1>
                <p class="text-pink-100 text-sm mt-1">
                    <svg class="w-4 h-4 inline-block mr-1 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <?php echo e(now()->isoFormat('dddd, D MMMM YYYY')); ?>

                </p>
            </div>
            <div class="flex-shrink-0">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center text-white text-2xl font-black">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                </div>
            </div>
        </div>
    </div>

    
    <?php if($activeQueues->isNotEmpty()): ?>
    <section>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Antrean Aktif Anda</h2>
            <span class="text-xs bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full"><?php echo e($activeQueues->count()); ?></span>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <?php $__currentLoopData = $activeQueues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 md:p-6 card-hover">
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide"><?php echo e($queue->branch->name); ?></p>
                        <p class="text-4xl font-black text-gray-900 mt-0.5 font-mono"><?php echo e($queue->queue_number); ?></p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                        <?php if($queue->status === 'pending'): ?>   badge-pending
                        <?php elseif($queue->status === 'active'): ?> badge-active
                        <?php elseif($queue->status === 'called'): ?> badge-called animate-pulse
                        <?php endif; ?> relative <?php echo e($queue->status === 'called' ? 'pulse-ring' : ''); ?>">
                        <?php if($queue->status === 'called'): ?>
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                        <?php elseif($queue->status === 'active'): ?>
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <?php else: ?>
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        <?php endif; ?>
                        <?php echo e($queue->status_label); ?>

                    </span>
                </div>

                
                <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                    <div class="bg-gray-50 rounded-xl px-2 py-2.5">
                        <p class="text-xs text-gray-400 mb-0.5">Barber</p>
                        <p class="text-xs font-semibold text-gray-800 truncate"><?php echo e($queue->barber->name); ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-2 py-2.5">
                        <p class="text-xs text-gray-400 mb-0.5">Layanan</p>
                        <p class="text-xs font-semibold text-gray-800 truncate"><?php echo e($queue->service->name); ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-2 py-2.5">
                        <p class="text-xs text-gray-400 mb-0.5">Durasi</p>
                        <p class="text-xs font-semibold text-gray-800"><?php echo e($queue->service->duration_minutes); ?>m</p>
                    </div>
                </div>

                
                <div class="flex gap-2">
                    <a href="<?php echo e(route('customer.queue.status', $queue)); ?>"
                       class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-900 text-white text-sm font-semibold py-2.5 rounded-xl hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Status
                    </a>
                    <?php if($queue->isPending()): ?>
                    <a href="<?php echo e(route('customer.queue.status', $queue)); ?>"
                       class="flex-1 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        QR Check-in
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <?php endif; ?>

    
    <section>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl bg-pink-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900">Pilih Cabang</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 card-hover group">
                
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-pink-50 to-purple-50 border border-pink-100 flex items-center justify-center group-hover:from-pink-100 group-hover:to-purple-100 transition-colors">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-100 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        Buka
                    </span>
                </div>

                <h3 class="font-bold text-gray-900 mb-1 text-sm md:text-base"><?php echo e($branch->name); ?></h3>
                <div class="space-y-1 mb-4">
                    <p class="text-xs text-gray-500 flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <?php echo e($branch->address); ?>

                    </p>
                    <p class="text-xs text-gray-500 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php echo e($branch->open_time); ?> – <?php echo e($branch->close_time); ?>

                    </p>
                    <?php if($branch->phone): ?>
                    <p class="text-xs text-gray-500 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <?php echo e($branch->phone); ?>

                    </p>
                    <?php endif; ?>
                </div>

                <?php $existingQueue = auth()->user()->activeQueue($branch->id); ?>

                <?php if($existingQueue): ?>
                    <a href="<?php echo e(route('customer.queue.status', $existingQueue)); ?>"
                       class="w-full inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 border border-blue-200 font-semibold text-sm py-2.5 rounded-xl hover:bg-blue-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Antrean #<?php echo e($existingQueue->queue_number); ?>

                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('customer.queue.take', $branch)); ?>"
                       class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold text-sm py-2.5 rounded-xl hover:opacity-90 transition-opacity shadow-sm shadow-pink-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ambil Antrean
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-16">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                </div>
                <p class="text-gray-500 font-medium">Belum ada cabang yang tersedia.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views/customer/dashboard.blade.php ENDPATH**/ ?>