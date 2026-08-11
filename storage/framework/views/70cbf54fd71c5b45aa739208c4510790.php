<?php $__env->startSection('title', 'Dashboard Admin'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Ringkasan antrean hari ini — ' . now()->isoFormat('dddd, D MMMM YYYY')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
        $stats = [
            ['label' => 'Total Cabang', 'value' => $totalBranches, 'color' => 'from-blue-500 to-cyan-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>'],
            ['label' => 'Total Barber', 'value' => $totalBarbers, 'color' => 'from-purple-500 to-violet-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'],
            ['label' => 'Total Customer', 'value' => $totalCustomers, 'color' => 'from-pink-500 to-rose-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
            ['label' => 'Selesai Hari Ini', 'value' => $statusSummary['completed'], 'color' => 'from-green-500 to-emerald-500', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        ];
        ?>

        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-xs font-medium uppercase tracking-wide"><?php echo e($stat['label']); ?></p>
                    <p class="text-3xl font-black text-gray-900 mt-2"><?php echo e($stat['value']); ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br <?php echo e($stat['color']); ?> flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <?php echo $stat['icon']; ?>

                    </svg>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4">Status Antrean Hari Ini</h3>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
            <?php
            $statusCards = [
                ['key' => 'pending',   'label' => 'Menunggu',    'cls' => 'bg-yellow-50 border-yellow-200 text-yellow-700'],
                ['key' => 'active',    'label' => 'Check-in',    'cls' => 'bg-blue-50 border-blue-200 text-blue-700'],
                ['key' => 'called',    'label' => 'Dipanggil',   'cls' => 'bg-purple-50 border-purple-200 text-purple-700'],
                ['key' => 'completed', 'label' => 'Selesai',     'cls' => 'bg-green-50 border-green-200 text-green-700'],
                ['key' => 'skipped',   'label' => 'Dilewati',    'cls' => 'bg-red-50 border-red-200 text-red-700'],
                ['key' => 'expired',   'label' => 'Kedaluwarsa', 'cls' => 'bg-gray-50 border-gray-200 text-gray-600'],
            ];
            ?>
            <?php $__currentLoopData = $statusCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border rounded-xl p-3 text-center <?php echo e($s['cls']); ?>">
                <p class="text-2xl font-black"><?php echo e($statusSummary[$s['key']]); ?></p>
                <p class="text-xs font-medium mt-1"><?php echo e($s['label']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Antrean Terbaru Hari Ini</h3>
            <a href="<?php echo e(route('admin.queues.index')); ?>" class="text-sm text-pink-600 hover:text-pink-700 font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">No. Antrean</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Customer</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Barber</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Layanan</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Status</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-6 py-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $recentQueues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $queue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900"><?php echo e($queue->queue_number); ?></td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 text-sm"><?php echo e($queue->customer->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($queue->branch->name); ?></p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?php echo e($queue->barber?->user?->name ?? '—'); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?php echo e($queue->service->name); ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-<?php echo e($queue->status); ?>">
                                <?php echo e($queue->status_label); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500"><?php echo e($queue->created_at->format('H:i')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">
                            Belum ada antrean hari ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $links = [
            ['href' => route('admin.branches.create'), 'icon' => '🏪', 'label' => 'Tambah Cabang'],
            ['href' => route('admin.barbers.create'), 'icon' => '💈', 'label' => 'Tambah Barber'],
            ['href' => route('admin.services.create'), 'icon' => '✂️', 'label' => 'Tambah Layanan'],
            ['href' => route('admin.queues.index'), 'icon' => '📋', 'label' => 'Kelola Antrean'],
        ];
        ?>
        <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($link['href']); ?>"
           class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center gap-3 hover:border-pink-200 hover:shadow-md transition-all group">
            <span class="text-2xl"><?php echo e($link['icon']); ?></span>
            <span class="text-sm font-semibold text-gray-700 group-hover:text-pink-600 transition-colors"><?php echo e($link['label']); ?></span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\raipr\Documents\Code\holic-barbershop\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>