<?php $__env->startSection('title', 'Kelola Cabang'); ?>
<?php $__env->startSection('page-title', 'Cabang Barbershop'); ?>
<?php $__env->startSection('page-subtitle', 'Kelola semua cabang HOLIC Barbershop'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.branches.create')); ?>"
   class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Cabang
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Nama Cabang</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Alamat</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Barber</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Layanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Jam Buka</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900"><?php echo e($branch->name); ?></p>
                        <?php if($branch->phone): ?>
                            <p class="text-xs text-gray-500"><?php echo e($branch->phone); ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs"><?php echo e($branch->address); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium"><?php echo e($branch->barbers_count); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium"><?php echo e($branch->services_count); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($branch->open_time); ?> – <?php echo e($branch->close_time); ?></td>
                    <td class="px-6 py-4">
                        <?php if($branch->is_active): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Aktif</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5">
                            <a href="<?php echo e(route('admin.branches.show', $branch)); ?>"
                               title="Lihat Detail"
                               class="inline-flex items-center gap-1 text-xs font-semibold bg-blue-50 text-blue-600 px-2.5 py-1.5 rounded-lg hover:bg-blue-100 active:scale-95 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat
                            </a>
                            <a href="<?php echo e(route('admin.branches.edit', $branch)); ?>"
                               title="Edit"
                               class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 text-amber-600 px-2.5 py-1.5 rounded-lg hover:bg-amber-100 active:scale-95 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.branches.destroy', $branch)); ?>"
                                  onsubmit="return confirm('Hapus cabang <?php echo e($branch->name); ?>? Semua data terkait akan ikut terhapus.')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" title="Hapus"
                                        class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 text-red-600 px-2.5 py-1.5 rounded-lg hover:bg-red-100 active:scale-95 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        Belum ada cabang. <a href="<?php echo e(route('admin.branches.create')); ?>" class="text-pink-600 font-medium">Tambah sekarang</a>.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($branches->hasPages()): ?>
    <div class="p-4 border-t border-gray-100">
        <?php echo e($branches->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views/admin/branches/index.blade.php ENDPATH**/ ?>