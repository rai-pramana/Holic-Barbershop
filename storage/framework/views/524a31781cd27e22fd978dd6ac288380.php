<?php $__env->startSection('title', 'Kelola Antrean'); ?>
<?php $__env->startSection('page-title', 'Antrean'); ?>
<?php $__env->startSection('page-subtitle', 'Monitor semua antrean'); ?>

<?php $__env->startSection('content'); ?>

<form method="GET" action="<?php echo e(route('admin.queues.index')); ?>" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Cabang</label>
            <select name="branch_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-pink-400">
                <option value="">Semua Cabang</option>
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($branch->id); ?>" <?php echo e(request('branch_id') == $branch->id ? 'selected' : ''); ?>><?php echo e($branch->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-pink-400">
                <option value="">Semua Status</option>
                <?php $__currentLoopData = ['pending'=>'Menunggu','active'=>'Check-in','called'=>'Dipanggil','completed'=>'Selesai','skipped'=>'Dilewati','expired'=>'Kedaluwarsa']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php echo e(request('status') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
            <input type="date" name="date" value="<?php echo e(request('date', today()->toDateString())); ?>"
                   class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-pink-400">
        </div>
        <button type="submit"
                class="bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
            Filter
        </button>
        <a href="<?php echo e(route('admin.queues.index')); ?>" class="text-sm text-gray-500 hover:text-gray-700 py-2">Reset</a>
    </div>
</form>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">No. Antrean</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Customer</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Barber</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Layanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Cabang</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Dibuat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $queues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-3 font-bold text-gray-900 text-sm"><?php echo e($queue->queue_number); ?></td>
                    <td class="px-5 py-3 text-sm">
                        <p class="font-medium text-gray-900"><?php echo e($queue->customer->name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($queue->customer->phone); ?></p>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-700"><?php echo e($queue->barber?->user?->name ?? '—'); ?></td>
                    <td class="px-5 py-3 text-sm text-gray-700">
                        <p><?php echo e($queue->service->name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($queue->service->duration_minutes); ?> menit</p>
                    </td>
                    <td class="px-5 py-3 text-sm text-gray-600"><?php echo e($queue->branch->name); ?></td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-<?php echo e($queue->status); ?>">
                            <?php echo e($queue->status_label); ?>

                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500"><?php echo e($queue->created_at->format('H:i')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">Tidak ada antrean ditemukan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($queues->hasPages()): ?>
    <div class="p-4 border-t border-gray-100"><?php echo e($queues->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\queues\index.blade.php ENDPATH**/ ?>