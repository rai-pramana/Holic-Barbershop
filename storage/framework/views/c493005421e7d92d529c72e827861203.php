<?php $__env->startSection('title', 'Edit Layanan'); ?>
<?php $__env->startSection('page-title', 'Edit Layanan: ' . $service->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="<?php echo e(route('admin.services.update', $service)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cabang *</label>
                    <select name="branch_id" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400">
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($branch->id); ?>" <?php echo e(old('branch_id', $service->branch_id) == $branch->id ? 'selected' : ''); ?>><?php echo e($branch->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Layanan *</label>
                    <input type="text" name="name" value="<?php echo e(old('name', $service->name)); ?>" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="2"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 resize-none"><?php echo e(old('description', $service->description)); ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi (menit) *</label>
                        <input type="number" name="duration_minutes" value="<?php echo e(old('duration_minutes', $service->duration_minutes)); ?>" min="5" max="480" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp) *</label>
                        <input type="number" name="price" value="<?php echo e(old('price', $service->price)); ?>" min="0" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400">
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $service->is_active) ? 'checked' : ''); ?>

                               class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-400">
                        <span class="text-sm font-medium text-gray-700">Layanan Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                <a href="<?php echo e(route('admin.services.index')); ?>"
                   class="flex-1 text-center bg-gray-100 text-gray-700 font-semibold py-2.5 rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold py-2.5 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\services\edit.blade.php ENDPATH**/ ?>