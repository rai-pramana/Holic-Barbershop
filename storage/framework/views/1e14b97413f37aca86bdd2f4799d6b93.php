<?php $__env->startSection('title', 'Kelola Layanan'); ?>
<?php $__env->startSection('page-title', 'Layanan'); ?>
<?php $__env->startSection('page-subtitle', 'Kelola semua layanan barbershop per cabang'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.services.create')); ?>"
   class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Tambah Layanan
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Nama Layanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Cabang</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Durasi</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Harga</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900"><?php echo e($service->name); ?></p>
                        <?php if($service->description): ?>
                            <p class="text-xs text-gray-500 mt-0.5 max-w-xs truncate"><?php echo e($service->description); ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($service->branch->name); ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 text-sm text-gray-700 bg-gray-100 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?php echo e($service->formatted_duration); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800"><?php echo e($service->formatted_price); ?></td>
                    <td class="px-6 py-4">
                        <?php if($service->is_active): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Aktif</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5">
                            <a href="<?php echo e(route('admin.services.edit', $service)); ?>"
                               title="Edit"
                               class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-50 text-amber-600 px-2.5 py-1.5 rounded-lg hover:bg-amber-100 active:scale-95 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.services.destroy', $service)); ?>"
                                  onsubmit="return confirm('Hapus layanan <?php echo e($service->name); ?>? Tindakan ini tidak dapat dibatalkan.')">
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
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        Belum ada layanan. <a href="<?php echo e(route('admin.services.create')); ?>" class="text-pink-600 font-medium">Tambah sekarang</a>.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($services->hasPages()): ?>
    <div class="p-4 border-t border-gray-100"><?php echo e($services->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\services\index.blade.php ENDPATH**/ ?>