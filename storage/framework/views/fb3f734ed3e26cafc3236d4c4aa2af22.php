<?php $__env->startSection('title', 'Edit Cabang'); ?>
<?php $__env->startSection('page-title', 'Edit Cabang: ' . $branch->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="<?php echo e(route('admin.branches.update', $branch)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Cabang *</label>
                    <input type="text" name="name" value="<?php echo e(old('name', $branch->name)); ?>" required
                           class="w-full border <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat *</label>
                    <textarea name="address" rows="2" required
                              class="w-full border <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400 resize-none"><?php echo e(old('address', $branch->address)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kota</label>
                    <input type="text" name="city" value="<?php echo e(old('city', $branch->city)); ?>"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" value="<?php echo e(old('phone', $branch->phone)); ?>"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Buka *</label>
                    <input type="time" name="open_time" value="<?php echo e(old('open_time', $branch->open_time)); ?>" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Tutup *</label>
                    <input type="time" name="close_time" value="<?php echo e(old('close_time', $branch->close_time)); ?>" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400 resize-none"><?php echo e(old('description', $branch->description)); ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Antrean <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 font-mono font-bold text-sm">Q</span>
                        <input type="text" name="queue_prefix" value="<?php echo e(old('queue_prefix', $branch->queue_prefix)); ?>" required
                               maxlength="3"
                               class="w-24 border <?php $__errorArgs = ['queue_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-xl px-4 py-2.5 text-sm font-mono font-bold focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400 uppercase">
                        <span class="text-xs text-gray-400">Tiket: <strong>Q<?php echo e($branch->queue_prefix); ?>001</strong>, <strong>Q<?php echo e($branch->queue_prefix); ?>002</strong>...</span>
                    </div>
                    <?php $__errorArgs = ['queue_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="text-xs text-gray-400 mt-1">⚠️ Ubah kode hanya jika belum ada antrean aktif hari ini.</p>
                </div>

                <div class="sm:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $branch->is_active) ? 'checked' : ''); ?>

                               class="w-5 h-5 rounded border-gray-300 text-pink-500 focus:ring-pink-400">
                        <span class="text-sm font-medium text-gray-700">Cabang Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="<?php echo e(route('admin.branches.index')); ?>"
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\branches\edit.blade.php ENDPATH**/ ?>