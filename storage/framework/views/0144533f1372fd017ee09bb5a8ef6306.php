<?php $__env->startSection('title', 'Daftarkan Antrean Walk-in'); ?>
<?php $__env->startSection('page-title', '➕ Daftarkan Antrean Walk-in'); ?>
<?php $__env->startSection('page-subtitle', 'Buat antrean untuk pelanggan tanpa HP atau tanpa akun (otomatis tervalidasi)'); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-medium flex items-center gap-2">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm font-medium flex items-center gap-2">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg">Antrean Walk-in</h2>
                    <p class="text-indigo-100 text-sm">Pelanggan tidak perlu akun — langsung aktif</p>
                </div>
            </div>
        </div>

        
        <div class="px-6 pt-5 pb-0">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 flex items-start gap-2 text-sm text-blue-700">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Cocok untuk pelanggan yang tidak membawa HP, anak kecil, atau daftar bersama (misal: bapak dan anak). Antrean ini <strong>otomatis tervalidasi</strong> — tidak perlu scan QR.</span>
            </div>
        </div>

        
        <form method="POST" action="<?php echo e(route('admin.queues.walkin.store')); ?>" class="p-6 space-y-5">
            <?php echo csrf_field(); ?>

            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Cabang <span class="text-red-500">*</span></label>
                <select name="branch_id" id="branch_id" required
                        onchange="this.form.action='<?php echo e(route('admin.queues.walkin')); ?>?branch_id='+this.value; this.form.method='GET'; this.form.submit();"
                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">— Pilih Cabang —</option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($branch->id); ?>" <?php if($selectedBranch?->id == $branch->id): echo 'selected'; endif; ?>><?php echo e($branch->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <?php if($selectedBranch): ?>

            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Layanan <span class="text-red-500">*</span></label>
                <select name="service_id" required
                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 <?php $__errorArgs = ['service_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">— Pilih Layanan —</option>
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($service->id); ?>" <?php if(old('service_id') == $service->id): echo 'selected'; endif; ?>>
                        <?php echo e($service->name); ?> — Rp <?php echo e(number_format($service->price, 0, ',', '.')); ?> (<?php echo e($service->duration_minutes); ?> menit)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['service_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Barber</label>
                <select name="barber_id"
                        class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">🤖 Otomatis (barber paling sedikit antrean)</option>
                    <?php $__currentLoopData = $barbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($barber->id); ?>" <?php if(old('barber_id') == $barber->id): echo 'selected'; endif; ?>>
                        <?php echo e($barber->name); ?> — <?php echo e($barber->pending_count); ?> antrean menunggu
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <hr class="border-gray-100">

            
            <div class="space-y-4">
                <p class="text-sm font-bold text-gray-800">Data Pelanggan</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" name="guest_name" value="<?php echo e(old('guest_name')); ?>" required
                               placeholder="Contoh: Budi Santoso"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 <?php $__errorArgs = ['guest_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['guest_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor HP <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <input type="tel" name="guest_phone" value="<?php echo e(old('guest_phone')); ?>"
                               placeholder="Contoh: 081234567890"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <textarea name="notes" rows="2" placeholder="Contoh: 2 orang (bapak dan anak), potong pendek"
                              class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 resize-none"><?php echo e(old('notes')); ?></textarea>
                </div>
            </div>

            
            <div class="flex gap-3 pt-2">
                <a href="<?php echo e(route('admin.queues.manage', ['branch_id' => $selectedBranch->id])); ?>"
                   class="flex-1 text-center py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-bold hover:opacity-90 transition-opacity shadow-sm">
                    ✅ Daftarkan Antrean
                </button>
            </div>

            <?php else: ?>
            <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                <p class="text-sm">Pilih cabang terlebih dahulu</p>
            </div>
            <?php endif; ?>

        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\queues\walkin.blade.php ENDPATH**/ ?>