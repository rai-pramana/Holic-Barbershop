<?php $__env->startSection('title', 'Detail Barber'); ?>
<?php $__env->startSection('page-title', $barber->name); ?>
<?php $__env->startSection('page-subtitle', $barber->branch->name . ' · ' . ($barber->specialty ?? 'Barber')); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.barbers.edit', $barber)); ?>"
   class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:opacity-90 transition-opacity">
    Edit
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl space-y-5">

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-start gap-5">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-black text-2xl flex-shrink-0">
            <?php echo e(strtoupper(substr($barber->name, 0, 1))); ?>

        </div>
        <div class="flex-1">
            <h2 class="text-xl font-bold text-gray-900"><?php echo e($barber->name); ?></h2>
            <?php if($barber->specialty): ?>
                <p class="text-pink-600 font-medium text-sm"><?php echo e($barber->specialty); ?></p>
            <?php endif; ?>
            <?php if($barber->phone): ?>
                <p class="text-gray-500 text-sm mt-1">📞 <?php echo e($barber->phone); ?></p>
            <?php endif; ?>
            <?php if($barber->bio): ?>
                <p class="text-gray-600 text-sm mt-2"><?php echo e($barber->bio); ?></p>
            <?php endif; ?>
        </div>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
              <?php echo e($barber->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'); ?>">
            <?php echo e($barber->is_available ? '✅ Tersedia' : '❌ Tidak Tersedia'); ?>

        </span>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Antrean Hari Ini</h3>
            <span class="text-sm text-gray-400"><?php echo e(now()->format('d M Y')); ?></span>
        </div>

        <?php if($todayQueues->isEmpty()): ?>
        <div class="text-center py-10 text-gray-400">
            <p class="text-3xl mb-2">📋</p>
            <p class="text-sm">Belum ada antrean hari ini.</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-gray-50">
            <?php $__currentLoopData = $todayQueues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="px-6 py-3 flex items-center gap-4">
                <span class="font-mono font-bold text-gray-800 w-16"><?php echo e($queue->queue_number); ?></span>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800"><?php echo e($queue->customer->name); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($queue->service->name); ?></p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                    <?php if($queue->status === 'pending'): ?>   bg-yellow-100 text-yellow-700
                    <?php elseif($queue->status === 'active'): ?>  bg-blue-100 text-blue-700
                    <?php elseif($queue->status === 'called'): ?>  bg-purple-100 text-purple-700
                    <?php elseif($queue->status === 'completed'): ?> bg-green-100 text-green-700
                    <?php else: ?> bg-gray-100 text-gray-500
                    <?php endif; ?>">
                    <?php echo e($queue->status_label); ?>

                </span>
                <a href="<?php echo e(route('admin.queues.show', $queue)); ?>"
                   class="text-xs text-pink-500 hover:underline">Detail</a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\barbers\show.blade.php ENDPATH**/ ?>