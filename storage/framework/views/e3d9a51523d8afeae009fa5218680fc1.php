<?php $__env->startSection('title', 'Kelola Barber'); ?>
<?php $__env->startSection('page-title', 'Barber'); ?>
<?php $__env->startSection('page-subtitle', 'Kelola semua barber HOLIC Barbershop'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.barbers.create')); ?>"
   class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Barber
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php $__empty_1 = true; $__currentLoopData = $barbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                <?php echo e(substr($barber->name, 0, 1)); ?>

            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-gray-900 truncate"><?php echo e($barber->name); ?></p>
                <p class="text-xs text-gray-500 truncate"><?php echo e($barber->phone ?? "-"); ?></p>
                <?php if($barber->specialty): ?>
                    <p class="text-xs text-pink-600 font-medium mt-1"><?php echo e($barber->specialty); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Cabang</span>
                <span class="font-medium text-gray-800 text-right max-w-[60%] truncate"><?php echo e($barber->branch->name); ?></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Status</span>
                <span class="font-medium <?php echo e($barber->is_available ? 'text-green-600' : 'text-red-500'); ?>">
                    <?php echo e($barber->is_available ? 'Tersedia' : 'Tidak Tersedia'); ?>

                </span>
            </div>
        </div>

        <div class="flex gap-2 mt-4">
            <a href="<?php echo e(route('admin.barbers.show', $barber)); ?>"
               title="Lihat Detail"
               class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold bg-blue-50 text-blue-600 py-2 rounded-xl hover:bg-blue-100 active:scale-95 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Lihat
            </a>
            <a href="<?php echo e(route('admin.barbers.edit', $barber)); ?>"
               title="Edit"
               class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold bg-amber-50 text-amber-600 py-2 rounded-xl hover:bg-amber-100 active:scale-95 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form method="POST" action="<?php echo e(route('admin.barbers.destroy', $barber)); ?>"
                  onsubmit="return confirm('Hapus barber <?php echo e($barber->name); ?>? Tindakan ini tidak dapat dibatalkan.')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" title="Hapus"
                        class="flex items-center gap-1.5 text-xs font-semibold bg-red-50 text-red-600 py-2 px-3 rounded-xl hover:bg-red-100 active:scale-95 transition-all h-full">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-span-3 bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400">
        Belum ada barber. <a href="<?php echo e(route('admin.barbers.create')); ?>" class="text-pink-600 font-medium">Tambah sekarang</a>.
    </div>
    <?php endif; ?>
</div>

<?php if($barbers->hasPages()): ?>
<div class="mt-6"><?php echo e($barbers->links()); ?></div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\barbers\index.blade.php ENDPATH**/ ?>