<?php $__env->startSection('title', 'Dashboard Barber'); ?>

<?php $__env->startSection('content'); ?>

<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-5 text-center">
        <p class="text-3xl font-black text-white"><?php echo e($pendingQueues->count()); ?></p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Menunggu</p>
    </div>
    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-5 text-center">
        <p class="text-3xl font-black text-green-400"><?php echo e($completedToday); ?></p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Selesai</p>
    </div>
    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-5 text-center">
        <p class="text-3xl font-black text-red-400"><?php echo e($skippedToday); ?></p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Dilewati</p>
    </div>
</div>


<?php if($activeQueue): ?>
<div class="mb-8">
    <h2 class="text-lg font-bold text-white mb-3">Antrean Aktif Sekarang</h2>
    <div class="bg-gradient-to-br
        <?php echo e($activeQueue->status === 'called' ? 'from-purple-900/80 to-indigo-900/80 border-purple-500/50' : 'from-blue-900/80 to-cyan-900/80 border-blue-500/50'); ?>

        border rounded-2xl p-6">

        <div class="flex justify-between items-start mb-5">
            <div>
                <p class="text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">Nomor Antrean</p>
                <p class="text-5xl font-black text-white"><?php echo e($activeQueue->queue_number); ?></p>
            </div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                <?php echo e($activeQueue->status === 'called' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/40' : 'bg-blue-500/20 text-blue-300 border border-blue-500/40'); ?>">
                <?php if($activeQueue->status === 'called'): ?> 🔔 Dipanggil <?php else: ?> ✅ Check-in <?php endif; ?>
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p class="text-gray-500 mb-1">Customer</p>
                <p class="text-white font-semibold"><?php echo e($activeQueue->customer->name); ?></p>
                <?php if($activeQueue->customer->phone): ?>
                    <p class="text-gray-400 text-xs"><?php echo e($activeQueue->customer->phone); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-gray-500 mb-1">Layanan</p>
                <p class="text-white font-semibold"><?php echo e($activeQueue->service->name); ?></p>
                <p class="text-gray-400 text-xs"><?php echo e($activeQueue->service->duration_minutes); ?> menit • <?php echo e($activeQueue->service->formatted_price); ?></p>
            </div>
            <?php if($activeQueue->notes): ?>
            <div class="col-span-2">
                <p class="text-gray-500 mb-1">Catatan Customer</p>
                <p class="text-gray-300 text-sm bg-gray-800/50 rounded-lg p-2"><?php echo e($activeQueue->notes); ?></p>
            </div>
            <?php endif; ?>
            <?php if($activeQueue->called_at): ?>
            <div>
                <p class="text-gray-500 mb-1">Dipanggil pukul</p>
                <p class="text-white font-semibold"><?php echo e($activeQueue->called_at->format('H:i')); ?></p>
                <p class="text-xs <?php echo e($activeQueue->called_at->diffInMinutes(now()) >= 5 ? 'text-red-400' : 'text-yellow-400'); ?>">
                    <?php echo e($activeQueue->called_at->diffInMinutes(now())); ?> menit lalu
                </p>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="flex gap-3">
            <?php if($activeQueue->status === 'active'): ?>
            <form method="POST" action="<?php echo e(route('barber.queues.call', $activeQueue)); ?>" class="flex-1">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    📣 Panggil Customer
                </button>
            </form>
            <?php endif; ?>

            <?php if($activeQueue->status === 'called'): ?>
            <form method="POST" action="<?php echo e(route('barber.queues.complete', $activeQueue)); ?>" class="flex-1">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    ✅ Selesai
                </button>
            </form>
            <?php endif; ?>

            <?php if(in_array($activeQueue->status, ['called', 'active'])): ?>
            <form method="POST" action="<?php echo e(route('barber.queues.skip', $activeQueue)); ?>"
                  onsubmit="return confirm('Lewati antrean ini? Customer akan ditandai tidak hadir.')"
                  class="<?php echo e($activeQueue->status === 'active' ? '' : 'flex-1'); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        class="w-full <?php echo e($activeQueue->status === 'active' ? 'px-4' : ''); ?> bg-red-500/20 text-red-300 border border-red-500/30 font-bold py-3.5 rounded-xl hover:bg-red-500/30 transition-colors text-sm">
                    ⏭ Skip
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>


<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-white">Antrean Hari Ini</h2>
        <button onclick="window.location.reload()" class="text-sm text-gray-400 hover:text-white transition-colors flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh
        </button>
    </div>

    <?php if($todayQueues->isEmpty()): ?>
    <div class="bg-gray-900 rounded-2xl border border-gray-700 p-12 text-center">
        <p class="text-5xl mb-4">✂️</p>
        <p class="text-gray-400">Belum ada antrean hari ini.</p>
    </div>
    <?php else: ?>
    <div class="space-y-3">
        <?php $__currentLoopData = $todayQueues->sortBy('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(!in_array($queue->status, ['completed', 'skipped', 'expired'])): ?>
        <div class="bg-gray-900 border border-gray-700/50 rounded-2xl p-4 flex items-center justify-between
            <?php if(in_array($queue->status, ['active', 'called'])): ?> border-blue-500/30 bg-blue-900/10 <?php endif; ?>">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl
                    <?php if($queue->status === 'called'): ?> bg-purple-500/20 text-purple-300
                    <?php elseif($queue->status === 'active'): ?> bg-blue-500/20 text-blue-300
                    <?php else: ?> bg-gray-700 text-gray-400
                    <?php endif; ?>
                    flex items-center justify-center font-bold text-sm">
                    <?php echo e($queue->queue_number); ?>

                </div>
                <div>
                    <p class="text-white font-semibold text-sm"><?php echo e($queue->customer->name); ?></p>
                    <p class="text-gray-400 text-xs"><?php echo e($queue->service->name); ?> — <?php echo e($queue->service->duration_minutes); ?> menit</p>
                </div>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                <?php if($queue->status === 'pending'): ?> bg-yellow-500/15 text-yellow-400 border border-yellow-500/30
                <?php elseif($queue->status === 'active'): ?> bg-blue-500/15 text-blue-400 border border-blue-500/30
                <?php elseif($queue->status === 'called'): ?> bg-purple-500/15 text-purple-400 border border-purple-500/30
                <?php endif; ?>">
                <?php echo e($queue->status_label); ?>

            </span>
        </div>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php $doneQueues = $todayQueues->whereIn('status', ['completed', 'skipped', 'expired']); ?>
        <?php if($doneQueues->isNotEmpty()): ?>
        <div class="pt-3 border-t border-gray-800">
            <p class="text-xs text-gray-600 uppercase font-medium tracking-wide mb-3">Selesai / Dilewati</p>
            <?php $__currentLoopData = $doneQueues->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-3 flex items-center justify-between mb-2 opacity-60">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-800 text-gray-500 flex items-center justify-center font-bold text-xs">
                        <?php echo e($queue->queue_number); ?>

                    </div>
                    <p class="text-gray-400 text-sm"><?php echo e($queue->customer->name); ?></p>
                </div>
                <span class="text-xs font-medium text-gray-500"><?php echo e($queue->status_label); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-refresh every 30 seconds
setTimeout(() => window.location.reload(), 30000);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.barber', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\barber\dashboard.blade.php ENDPATH**/ ?>