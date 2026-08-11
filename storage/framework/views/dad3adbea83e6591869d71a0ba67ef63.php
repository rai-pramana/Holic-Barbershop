<?php $__env->startSection('title', 'Riwayat Antrean'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="flex items-center gap-3">
        <a href="<?php echo e(route('customer.dashboard')); ?>"
           class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors flex-shrink-0">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-gray-900">Riwayat Antrean</h1>
            <p class="text-sm text-gray-500">Semua antrean Anda sebelumnya</p>
        </div>
    </div>

    
    <form method="GET" action="<?php echo e(route('customer.queue.history')); ?>" class="flex gap-2 flex-wrap">
        <select name="status" onchange="this.form.submit()"
                class="rounded-xl border-gray-200 bg-white text-sm px-3 py-2 text-gray-700 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
            <option value="">Semua Status</option>
            <option value="completed" <?php if(request('status') === 'completed'): echo 'selected'; endif; ?>>✅ Selesai</option>
            <option value="skipped"   <?php if(request('status') === 'skipped'): echo 'selected'; endif; ?>>⚠️ Dilewati</option>
            <option value="expired"   <?php if(request('status') === 'expired'): echo 'selected'; endif; ?>>🕐 Kedaluwarsa</option>
        </select>
    </form>

    
    <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex justify-between items-start gap-3">
            <div class="flex items-center gap-4">
                
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-xl font-mono flex-shrink-0
                    <?php if($queue->status === 'completed'): ?> bg-green-100 text-green-700
                    <?php elseif($queue->status === 'skipped'): ?> bg-red-100 text-red-600
                    <?php else: ?> bg-gray-100 text-gray-500 <?php endif; ?>">
                    <?php echo e($queue->queue_number); ?>

                </div>
                <div>
                    <p class="font-bold text-gray-900 text-sm"><?php echo e($queue->branch->name); ?></p>
                    <p class="text-xs text-gray-500 mt-0.5"><?php echo e($queue->service->name); ?></p>
                    <?php if($queue->barber): ?>
                    <p class="text-xs text-gray-400">Barber: <?php echo e($queue->barber->name); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold flex-shrink-0
                <?php if($queue->status === 'completed'): ?> bg-green-100 text-green-700
                <?php elseif($queue->status === 'skipped'): ?> bg-red-100 text-red-600
                <?php else: ?> bg-gray-100 text-gray-500 <?php endif; ?>">
                <?php if($queue->status === 'completed'): ?> ✅
                <?php elseif($queue->status === 'skipped'): ?> ⚠️
                <?php else: ?> 🕐 <?php endif; ?>
                <?php echo e($queue->status_label); ?>

            </span>
        </div>

        
        <div class="mt-4 pt-3 border-t border-gray-50 flex flex-wrap gap-4 text-xs text-gray-400">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <?php echo e($queue->created_at->translatedFormat('d M Y, H:i')); ?> WITA
            </span>
            <?php if($queue->completed_at): ?>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Selesai: <?php echo e($queue->completed_at->translatedFormat('H:i')); ?> WITA
            </span>
            <?php endif; ?>
            <?php if($queue->notes): ?>
            <span class="flex items-center gap-1 italic">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                <?php echo e($queue->notes); ?>

            </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="text-gray-500 font-medium">Belum ada riwayat antrean</p>
        <p class="text-gray-400 text-sm mt-1">Riwayat akan muncul setelah antrean Anda selesai</p>
    </div>
    <?php endif; ?>

    
    <?php if($histories->hasPages()): ?>
    <div class="flex justify-center mt-4">
        <?php echo e($histories->links()); ?>

    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\customer\queue\history.blade.php ENDPATH**/ ?>