<?php $__env->startSection('title', 'Loket Check-in'); ?>
<?php $__env->startSection('page-title', '📲 Loket Check-in'); ?>
<?php $__env->startSection('page-subtitle', 'Tampilkan QR kepada customer atau input manual nomor antrean'); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>
<div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl p-4 text-sm mb-5">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 text-sm mb-5">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<div class="grid lg:grid-cols-2 gap-6 max-w-5xl">

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-3">Pilih Cabang</p>
            <div class="flex flex-wrap gap-2" id="branch-tabs">
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button onclick="switchBranch('<?php echo e($branch->id); ?>', '<?php echo e(addslashes($branch->name)); ?>', '<?php echo e(route('customer.checkin.scan', $branch->id)); ?>')"
                        id="tab-<?php echo e($branch->id); ?>"
                        class="branch-tab px-4 py-2 rounded-xl text-sm font-semibold transition-all
                               <?php echo e($i === 0 ? 'bg-gradient-to-r from-pink-500 to-purple-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'); ?>">
                    <?php echo e($branch->name); ?>

                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="p-6 flex flex-col items-center text-center">
            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Minta customer scan QR ini dengan kamera HP</p>
                <p class="text-xs text-gray-400" id="branch-name"><?php echo e($branches->first()->name ?? '-'); ?></p>
            </div>

            
            <div class="relative bg-white p-4 rounded-3xl shadow-xl border-4 border-gray-900 mb-5">
                <div id="qr-canvas" class="w-56 h-56"></div>
                
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-12 h-12 rounded-xl bg-gray-900 flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
            </div>

            
            <div class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 mb-4">
                <p class="text-xs text-gray-400 mb-0.5">URL Check-in</p>
                <p id="checkin-url" class="text-xs font-mono text-gray-600 break-all"><?php echo e(route('customer.checkin.scan', $branches->first()->id ?? 1)); ?></p>
            </div>

            <div class="flex gap-3 w-full">
                <button onclick="refreshQR()"
                        class="flex-1 flex items-center justify-center gap-2 bg-gray-100 text-gray-600 text-sm font-semibold py-2.5 rounded-xl hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Refresh
                </button>
                <button onclick="printQR()"
                        class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white text-sm font-semibold py-2.5 rounded-xl hover:opacity-90 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print QR
                </button>
            </div>
        </div>

        
        <div class="px-5 pb-5">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <p class="text-xs font-bold text-blue-800 mb-2">📋 Cara Penggunaan</p>
                <ol class="text-xs text-blue-700 space-y-1 list-decimal list-inside">
                    <li>Customer ambil nomor antrean via aplikasi</li>
                    <li>Saat tiba di barbershop, minta customer buka antrean di HP</li>
                    <li>Customer scan QR ini atau gunakan QR yang ada di tiket mereka</li>
                    <li>Status otomatis berubah menjadi <strong>Hadir ✅</strong></li>
                </ol>
            </div>
        </div>
    </div>

    
    <div class="space-y-5">

        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-xl">⌨️</div>
                <div>
                    <h3 class="font-bold text-gray-900">Input Manual</h3>
                    <p class="text-xs text-gray-400">Cari antrean dengan nomor tiket</p>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('admin.checkin.search')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Antrean</label>
                    <input type="text" name="queue_number"
                           value="<?php echo e(old('queue_number')); ?>"
                           placeholder="cth: Q0005"
                           autofocus
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm font-mono font-bold uppercase text-gray-800 tracking-widest focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400 <?php $__errorArgs = ['queue_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['queue_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cabang (opsional)</label>
                    <select name="branch_id"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400">
                        <option value="">— Semua Cabang —</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold py-3 rounded-xl hover:opacity-90 transition-opacity text-sm">
                    🔍 Cari Antrean
                </button>
            </form>
        </div>

        
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span>✅ Tervalidasi Hari Ini</span>
                <span class="text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">
                    <?php echo e(\App\Models\Queue::whereDate('created_at', today())->where('status', 'active')->count() +
                       \App\Models\Queue::whereDate('created_at', today())->where('status', 'called')->count() +
                       \App\Models\Queue::whereDate('created_at', today())->where('status', 'completed')->count()); ?>

                </span>
            </h3>
            <?php
                $recent = \App\Models\Queue::with(['customer', 'branch'])
                    ->whereDate('created_at', today())
                    ->whereNotNull('checked_in_at')
                    ->orderByDesc('checked_in_at')
                    ->take(6)
                    ->get();
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <span class="font-mono font-bold text-xs text-gray-700 w-14"><?php echo e($q->queue_number); ?></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate"><?php echo e($q->customer->name); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($q->checked_in_at->format('H:i')); ?> · <?php echo e($q->branch->name); ?></p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                    <?php echo e($q->status === 'called' ? 'bg-purple-100 text-purple-700' : ($q->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700')); ?>">
                    <?php echo e($q->status_label); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-sm text-gray-400 text-center py-4">Belum ada yang tervalidasi hari ini.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
let currentUrl = '<?php echo e(route('customer.checkin.scan', $branches->first()->id ?? 1)); ?>';
let currentBranchName = '<?php echo e(addslashes($branches->first()->name ?? '')); ?>';
let qrInstance = null;

function generateQR(url) {
    document.getElementById('qr-canvas').innerHTML = '';
    qrInstance = new QRCode(document.getElementById('qr-canvas'), {
        text: url,
        width: 224,
        height: 224,
        colorDark: '#111827',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M,
    });
}

function switchBranch(branchId, branchName, url) {
    currentUrl = url;
    currentBranchName = branchName;

    // Update URL display
    document.getElementById('checkin-url').textContent = url;
    document.getElementById('branch-name').textContent = branchName;

    // Update tab styles
    document.querySelectorAll('.branch-tab').forEach(btn => {
        btn.className = btn.className
            .replace('bg-gradient-to-r from-pink-500 to-purple-600 text-white', '')
            .replace('bg-gray-100 text-gray-600 hover:bg-gray-200', '')
            .trim();
        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    });
    const activeTab = document.getElementById('tab-' + branchId);
    activeTab.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    activeTab.classList.add('bg-gradient-to-r', 'from-pink-500', 'to-purple-600', 'text-white');

    // Regenerate QR
    generateQR(url);
}

function refreshQR() {
    generateQR(currentUrl);
}

function printQR() {
    const canvas = document.querySelector('#qr-canvas canvas') || document.querySelector('#qr-canvas img');
    if (!canvas) { alert('QR belum siap, tunggu sebentar.'); return; }

    const dataUrl = canvas.tagName === 'CANVAS' ? canvas.toDataURL() : canvas.src;
    const win = window.open('', '_blank');
    win.document.write(`
        <!DOCTYPE html><html><head>
        <title>QR Check-in - ${currentBranchName}</title>
        <style>
          body { margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; font-family: sans-serif; background: #fff; }
          .card { text-align: center; padding: 40px; border: 3px solid #111; border-radius: 20px; max-width: 320px; }
          h1 { font-size: 1.5rem; font-weight: 900; margin: 0 0 4px; }
          p { color: #666; font-size: 0.85rem; margin: 0 0 20px; }
          img { width: 220px; height: 220px; display: block; margin: 0 auto 20px; }
          .hint { font-size: 0.75rem; color: #999; margin-top: 16px; }
        </style>
        </head><body>
        <div class="card">
          <h1>HOLIC Barbershop</h1>
          <p>${currentBranchName}</p>
          <img src="${dataUrl}" alt="QR Check-in">
          <strong>Scan untuk Check-in</strong>
          <p class="hint">Pastikan Anda sudah login ke aplikasi sebelum scan</p>
        </div>
        <script>window.onload=()=>window.print()<\/script>
        </body></html>
    `);
    win.document.close();
}

// Init on load
document.addEventListener('DOMContentLoaded', () => generateQR(currentUrl));
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views/admin/checkin/index.blade.php ENDPATH**/ ?>