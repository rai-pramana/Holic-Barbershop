<?php $__env->startSection('title', 'Detail Antrean #' . $queue->queue_number); ?>
<?php $__env->startSection('page-title', 'Detail Antrean'); ?>
<?php $__env->startSection('page-subtitle', 'Antrean #' . $queue->queue_number . ' — ' . $queue->branch->name); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.queues.index')); ?>"
   class="bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-colors">
    ← Kembali
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        
        <div class="p-6 text-center
            <?php if($queue->status === 'pending'): ?>   bg-gradient-to-br from-yellow-400 to-orange-500
            <?php elseif($queue->status === 'active'): ?> bg-gradient-to-br from-blue-500 to-cyan-600
            <?php elseif($queue->status === 'called'): ?> bg-gradient-to-br from-purple-500 to-indigo-600
            <?php elseif($queue->status === 'completed'): ?> bg-gradient-to-br from-green-500 to-emerald-600
            <?php else: ?> bg-gradient-to-br from-gray-400 to-gray-600
            <?php endif; ?> text-white">
            <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-2"><?php echo e($queue->branch->name); ?></p>
            <div class="text-6xl font-black mb-3"><?php echo e($queue->queue_number); ?></div>
            <span class="inline-flex items-center px-4 py-1.5 bg-white/20 rounded-full text-sm font-semibold">
                <?php echo e($queue->status_label); ?>

            </span>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">👤 Customer</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->customer->name); ?></p>
                    <?php if($queue->customer->phone): ?>
                        <p class="text-xs text-gray-500"><?php echo e($queue->customer->phone); ?></p>
                    <?php endif; ?>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">💈 Barber</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->barber?->user?->name ?? '—'); ?></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">✂️ Layanan</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->service->name); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($queue->service->duration_minutes); ?> menit</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">💰 Harga</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->service->formatted_price); ?></p>
                </div>
            </div>

            
            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-3">Timeline</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                        <span class="text-gray-500">Dibuat:</span>
                        <span class="font-medium"><?php echo e($queue->created_at->format('H:i')); ?></span>
                    </div>
                    <?php if($queue->checked_in_at): ?>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                        <span class="text-gray-500">Divalidasi admin:</span>
                        <span class="font-medium"><?php echo e($queue->checked_in_at->format('H:i')); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($queue->called_at): ?>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                        <span class="text-gray-500">Dipanggil:</span>
                        <span class="font-medium"><?php echo e($queue->called_at->format('H:i')); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($queue->completed_at): ?>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <span class="text-gray-500">Selesai:</span>
                        <span class="font-medium"><?php echo e($queue->completed_at->format('H:i')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($queue->notes): ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm">
                <p class="font-medium text-yellow-800 mb-1">Catatan:</p>
                <p class="text-yellow-900"><?php echo e($queue->notes); ?></p>
            </div>
            <?php endif; ?>

            
            <?php if($queue->isPending()): ?>
            <div class="border-t border-gray-100 pt-4">
                <a href="<?php echo e(route('admin.checkin.confirm', $queue->validation_token)); ?>"
                   class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-3 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    📲 Validasi Kehadiran Customer
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\queues\show.blade.php ENDPATH**/ ?>