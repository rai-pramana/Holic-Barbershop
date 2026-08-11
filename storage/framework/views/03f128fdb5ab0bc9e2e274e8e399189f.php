<?php $__env->startSection('title', 'Kelola Antrean'); ?>
<?php $__env->startSection('page-title', '💈 Kelola Antrean'); ?>
<?php $__env->startSection('page-subtitle', 'Monitor dan kelola antrean per barber secara real-time'); ?>

<?php $__env->startSection('content'); ?>


<div class="flex flex-wrap items-center gap-3 mb-6">
    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('admin.queues.manage', ['branch_id' => $branch->id])); ?>"
       class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all
              <?php echo e($selectedBranch?->id === $branch->id
                  ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white shadow-lg shadow-pink-500/25'
                  : 'bg-white border border-gray-200 text-gray-600 hover:border-pink-300 hover:text-pink-600'); ?>">
        <?php echo e($branch->name); ?>

    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="ml-auto flex items-center gap-2">
        <a href="<?php echo e(route('admin.queues.walkin', $selectedBranch ? ['branch_id' => $selectedBranch->id] : [])); ?>"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Walk-in
        </a>
    </div>
</div>


<?php if(session('success')): ?>
<div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 text-sm mb-5" id="flash-msg">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 text-sm mb-5">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<?php if($selectedBranch): ?>

<?php if($barbers->isEmpty()): ?>
<div class="text-center py-16 text-gray-400">
    <p class="text-4xl mb-3">💈</p>
    <p class="font-semibold text-gray-500">Tidak ada barber aktif di cabang ini.</p>
    <a href="<?php echo e(route('admin.barbers.create')); ?>" class="mt-3 inline-block text-sm text-pink-500 hover:underline">+ Tambah Barber</a>
</div>
<?php else: ?>


<div class="grid lg:grid-cols-2 xl:grid-cols-3 gap-5" id="barber-boards">
    <?php $__currentLoopData = $barbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $activeQ   = $barber->queues->where('status', 'called')->first();
        $pendingQs = $barber->queues->whereIn('status', ['active', 'pending']);
    ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" data-barber="<?php echo e($barber->id); ?>">

        
        <div class="px-5 py-4 bg-gradient-to-r from-gray-900 to-gray-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                <?php echo e(strtoupper(substr($barber->name, 0, 1))); ?>

            </div>
            <div class="min-w-0">
                <p class="text-white font-bold truncate"><?php echo e($barber->name); ?></p>
                <?php if($barber->specialty): ?>
                    <p class="text-gray-400 text-xs truncate"><?php echo e($barber->specialty); ?></p>
                <?php endif; ?>
            </div>
            <div class="ml-auto text-right flex-shrink-0">
                <span class="text-xs text-gray-400">Antrean</span>
                <p class="text-white font-bold text-lg"><?php echo e($barber->queues->count()); ?></p>
            </div>
        </div>

        
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-3">Sedang Dilayani</p>
            <?php if($activeQ): ?>
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <p class="font-black text-purple-900 text-xl font-mono"><?php echo e($activeQ->queue_number); ?></p>
                    <p class="text-purple-700 text-sm font-medium"><?php echo e($activeQ->customer->name); ?></p>
                    <p class="text-purple-500 text-xs"><?php echo e($activeQ->service->name); ?></p>
                </div>
                <div class="flex flex-col gap-2">
                    <form method="POST" action="<?php echo e(route('admin.queues.complete', $activeQ)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                class="w-full bg-green-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-green-600 transition-colors">
                            ✅ Selesai
                        </button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.queues.skip', $activeQ)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                class="w-full bg-red-100 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-200 transition-colors">
                            ⚠️ Lewati
                        </button>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-4 text-gray-300">
                <p class="text-2xl mb-1">💤</p>
                <p class="text-xs text-gray-400">Belum ada yang dilayani</p>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="px-5 py-4">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-3">Antrean Menunggu (<?php echo e($pendingQs->count()); ?>)</p>

            <?php $__empty_1 = true; $__currentLoopData = $pendingQs->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <div class="flex-shrink-0">
                    <?php if($q->status === 'active'): ?>
                        <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg font-mono"><?php echo e($q->queue_number); ?></span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg font-mono"><?php echo e($q->queue_number); ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($q->customer->name); ?></p>
                    <p class="text-xs text-gray-400 truncate"><?php echo e($q->service->name); ?></p>
                </div>
                <div class="flex-shrink-0">
                    <?php if($q->status === 'active' && !$activeQ): ?>
                    <form method="POST" action="<?php echo e(route('admin.queues.call', $q)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:opacity-90 transition-opacity">
                            🔔 Panggil
                        </button>
                    </form>
                    <?php elseif($q->status === 'active'): ?>
                    <span class="text-xs text-blue-600 font-semibold bg-blue-50 px-2 py-1 rounded-lg">Hadir</span>
                    <?php else: ?>
                    <span class="text-xs text-yellow-600 font-semibold bg-yellow-50 px-2 py-1 rounded-lg">Menunggu</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-300 text-sm py-3">Tidak ada antrean menunggu</p>
            <?php endif; ?>

            <?php if($pendingQs->count() > 5): ?>
            <p class="text-center text-xs text-gray-400 mt-2">+<?php echo e($pendingQs->count() - 5); ?> antrean lagi</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php endif; ?>

<?php else: ?>
<div class="text-center py-16 text-gray-400">
    <p class="text-4xl mb-3">🏪</p>
    <p class="font-semibold text-gray-500">Pilih cabang di atas untuk melihat antrean.</p>
</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-refresh every 15 seconds
setInterval(() => {
    window.location.reload();
}, 15000);

// Auto-hide flash after 4s
setTimeout(() => {
    const flash = document.getElementById('flash-msg');
    if (flash) flash.style.transition = 'opacity 0.5s', flash.style.opacity = '0', setTimeout(() => flash.remove(), 500);
}, 4000);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\queues\manage.blade.php ENDPATH**/ ?>