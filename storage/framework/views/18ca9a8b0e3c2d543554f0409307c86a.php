<?php $__env->startSection('title', 'Konfirmasi Check-in'); ?>
<?php $__env->startSection('page-title', 'Konfirmasi Kehadiran Customer'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.checkin.index')); ?>"
   class="bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-gray-200 transition-colors flex items-center gap-2">
    ← Kembali ke Loket
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-xl mx-auto">

    <?php if(session('warning')): ?>
    <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-4 text-sm mb-6">
        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <p><?php echo e(session('warning')); ?></p>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-3xl border border-gray-200 shadow-lg overflow-hidden">

        
        <div class="p-6
            <?php if($queue->isPending()): ?> bg-gradient-to-br from-orange-400 to-pink-500
            <?php elseif($queue->isActive()): ?> bg-gradient-to-br from-green-500 to-emerald-600
            <?php elseif($queue->isCalled()): ?> bg-gradient-to-br from-purple-500 to-indigo-600
            <?php elseif($queue->isCompleted()): ?> bg-gradient-to-br from-gray-400 to-gray-600
            <?php else: ?> bg-gradient-to-br from-red-400 to-red-600
            <?php endif; ?> text-white text-center">

            <p class="text-white/70 text-xs font-semibold uppercase tracking-widest mb-2"><?php echo e($queue->branch->name); ?></p>
            <div class="text-6xl font-black mb-2"><?php echo e($queue->queue_number); ?></div>
            <span class="inline-flex items-center px-4 py-1.5 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                <?php if($queue->isPending()): ?> ⏳ Menunggu Validasi
                <?php elseif($queue->isActive()): ?> ✅ Sudah Divalidasi
                <?php elseif($queue->isCalled()): ?> 🔔 Sudah Dipanggil
                <?php elseif($queue->isCompleted()): ?> 🎉 Selesai
                <?php elseif($queue->isExpired()): ?> ⌛ Kedaluwarsa
                <?php elseif($queue->isSkipped()): ?> ⚠️ Dilewati
                <?php endif; ?>
            </span>
        </div>

        
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">👤 Customer</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->customer->name); ?></p>
                    <?php if($queue->customer->phone): ?>
                        <p class="text-xs text-gray-500"><?php echo e($queue->customer->phone); ?></p>
                    <?php endif; ?>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">💈 Barber</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->barber->name); ?></p>
                    <?php if($queue->barber->specialty): ?>
                        <p class="text-xs text-gray-500"><?php echo e($queue->barber->specialty); ?></p>
                    <?php endif; ?>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">✂️ Layanan</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->service->name); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($queue->service->duration_minutes); ?> menit</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs text-gray-500 font-medium mb-1">💰 Harga</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->service->formatted_price); ?></p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 col-span-2">
                    <p class="text-xs text-gray-500 font-medium mb-1">📅 Dibuat</p>
                    <p class="font-bold text-gray-900"><?php echo e($queue->created_at->isoFormat('dddd, D MMM YYYY — HH:mm')); ?></p>
                </div>
            </div>

            <?php if($queue->notes): ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm">
                <p class="text-yellow-700 font-medium mb-1">📝 Catatan:</p>
                <p class="text-yellow-900"><?php echo e($queue->notes); ?></p>
            </div>
            <?php endif; ?>

            
            <div class="pt-2 border-t border-gray-100">
                <?php if($queue->isPending()): ?>
                <p class="text-sm text-gray-500 text-center mb-4">
                    Pastikan customer ini hadir di lokasi sebelum memvalidasi.
                </p>
                <form method="POST" action="<?php echo e(route('admin.checkin.validate', $queue)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-4 rounded-2xl hover:opacity-90 transition-opacity text-base shadow-lg shadow-green-500/25 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Validasi Kehadiran Customer
                    </button>
                </form>
                <?php elseif($queue->isActive()): ?>
                <div class="text-center bg-green-50 border border-green-200 rounded-2xl p-5">
                    <p class="text-4xl mb-2">✅</p>
                    <p class="text-green-800 font-semibold">Sudah divalidasi pada <?php echo e($queue->checked_in_at?->format('H:i')); ?></p>
                    <p class="text-green-600 text-sm mt-1">Customer menunggu dipanggil barber.</p>
                </div>
                <?php elseif($queue->isCompleted()): ?>
                <div class="text-center bg-gray-50 border border-gray-200 rounded-2xl p-5">
                    <p class="text-4xl mb-2">🎉</p>
                    <p class="text-gray-700 font-semibold">Layanan selesai pada <?php echo e($queue->completed_at?->format('H:i')); ?></p>
                </div>
                <?php else: ?>
                <div class="text-center bg-red-50 border border-red-200 rounded-2xl p-4">
                    <p class="text-red-700 font-semibold text-sm">Antrean tidak dapat divalidasi (<?php echo e($queue->status_label); ?>)</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\checkin\confirm.blade.php ENDPATH**/ ?>