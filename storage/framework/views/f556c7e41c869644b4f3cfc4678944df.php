<?php $__env->startSection('title', 'Ambil Antrean — ' . $branch->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">

    
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="<?php echo e(route('customer.dashboard')); ?>" class="flex items-center gap-1 hover:text-pink-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-700 font-medium">Ambil Antrean</span>
    </nav>

    
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-5 md:p-6 text-white mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-white/60 text-xs font-medium uppercase tracking-wide mb-0.5">Cabang</p>
                <h1 class="text-lg md:text-xl font-bold truncate"><?php echo e($branch->name); ?></h1>
                <p class="text-white/60 text-sm flex items-center gap-1.5 mt-0.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <?php echo e($branch->address); ?>

                </p>
            </div>
        </div>
    </div>

    
    <div class="flex items-center gap-2 mb-6">
        <?php $__currentLoopData = ['Pilih Layanan', 'Pilih Barber', 'Catatan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center gap-2 <?php echo e($loop->last ? '' : 'flex-1'); ?>">
            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0"><?php echo e($i+1); ?></div>
            <span class="text-xs font-medium text-gray-600 whitespace-nowrap hidden sm:inline"><?php echo e($step); ?></span>
            <?php if(!$loop->last): ?>
            <div class="flex-1 h-px bg-gray-200 hidden sm:block"></div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form method="POST" action="<?php echo e(route('customer.queue.store', $branch)); ?>" id="take-queue-form" class="space-y-4">
        <?php echo csrf_field(); ?>

        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 text-xs font-bold">1</div>
                <h2 class="font-bold text-gray-900">Pilih Layanan</h2>
                <?php $__errorArgs = ['service_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-xs ml-auto"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label for="service_<?php echo e($service->id); ?>" class="cursor-pointer">
                    <input type="radio" id="service_<?php echo e($service->id); ?>" name="service_id" value="<?php echo e($service->id); ?>"
                           class="peer sr-only" <?php echo e(old('service_id') == $service->id ? 'checked' : ''); ?>>
                    <div class="border-2 border-gray-100 rounded-xl p-4 peer-checked:border-pink-400 peer-checked:bg-pink-50/60 hover:border-gray-200 transition-all h-full">
                        <div class="flex justify-between items-start gap-2 mb-1.5">
                            <p class="font-semibold text-gray-900 text-sm leading-tight"><?php echo e($service->name); ?></p>
                            <span class="text-pink-600 font-bold text-sm whitespace-nowrap flex-shrink-0"><?php echo e($service->formatted_price); ?></span>
                        </div>
                        <?php if($service->description): ?>
                            <p class="text-xs text-gray-500 mb-2 leading-relaxed"><?php echo e($service->description); ?></p>
                        <?php endif; ?>
                        <span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <?php echo e($service->formatted_duration); ?>

                        </span>
                    </div>
                </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 text-xs font-bold">2</div>
                <div>
                    <h2 class="font-bold text-gray-900">Pilih Barber</h2>
                    <p class="text-xs text-gray-400">Kosongkan untuk barber tercepat otomatis</p>
                </div>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                
                <label for="barber_auto" class="cursor-pointer">
                    <input type="radio" id="barber_auto" name="barber_id" value="" class="peer sr-only" checked>
                    <div class="border-2 border-gray-100 rounded-xl p-4 peer-checked:border-pink-400 peer-checked:bg-pink-50/60 hover:border-gray-200 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-sm shadow-pink-500/25">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Otomatis</p>
                                <p class="text-xs text-gray-500">Barber dengan antrean tercepat</p>
                            </div>
                        </div>
                    </div>
                </label>

                <?php $__currentLoopData = $barbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label for="barber_<?php echo e($barber->id); ?>" class="cursor-pointer">
                    <input type="radio" id="barber_<?php echo e($barber->id); ?>" name="barber_id" value="<?php echo e($barber->id); ?>" class="peer sr-only">
                    <div class="border-2 border-gray-100 rounded-xl p-4 peer-checked:border-pink-400 peer-checked:bg-pink-50/60 hover:border-gray-200 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                <?php echo e(strtoupper(substr($barber->name, 0, 1))); ?>

                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-gray-900 text-sm truncate"><?php echo e($barber->name); ?></p>
                                <?php if($barber->specialty): ?>
                                    <p class="text-xs text-gray-400 truncate"><?php echo e($barber->specialty); ?></p>
                                <?php endif; ?>
                                <span class="inline-flex items-center gap-1 text-xs font-medium mt-0.5
                                    <?php echo e($barber->pending_count === 0 ? 'text-green-600' : 'text-amber-600'); ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo e($barber->pending_count === 0 ? 'bg-green-400' : 'bg-amber-400'); ?>"></span>
                                    <?php echo e($barber->pending_count === 0 ? 'Kosong' : $barber->pending_count.' antrean'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 text-xs font-bold">3</div>
                <div>
                    <h2 class="font-bold text-gray-900">Catatan <span class="text-xs text-gray-400 font-normal">(opsional)</span></h2>
                </div>
            </div>
            <div class="p-4">
                <textarea name="notes" id="notes" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400/20 transition-all resize-none placeholder:text-gray-400"
                          placeholder="Contoh: minta fade tipis, jangan terlalu pendek..."><?php echo e(old('notes')); ?></textarea>
            </div>
        </div>

        
        <div class="flex gap-3 sticky bottom-4 md:static bg-gray-50/80 md:bg-transparent backdrop-blur-sm md:backdrop-blur-none p-4 md:p-0 -mx-4 md:mx-0 rounded-t-2xl md:rounded-none shadow-t-lg md:shadow-none">
            <a href="<?php echo e(route('customer.dashboard')); ?>"
               class="flex-1 flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-700 font-semibold py-3.5 rounded-2xl hover:bg-gray-50 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Batal
            </a>
            <button type="submit" id="submit-btn"
                    class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold py-3.5 rounded-2xl hover:opacity-90 active:scale-[0.98] transition-all shadow-lg shadow-pink-500/25 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                Ambil Nomor Antrean
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('take-queue-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Memproses...
    `;
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views/customer/queue/take.blade.php ENDPATH**/ ?>