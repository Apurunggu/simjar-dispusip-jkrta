

<?php $__env->startSection('title', 'Activity Log Distribusi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Activity Log Distribusi
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>ID Distribusi:</strong> #<?php echo e($distribusi->id); ?></p>
                    <p><strong>Barang:</strong> <?php echo e($distribusi->barang->nama_barang ?? '-'); ?> (<?php echo e($distribusi->jumlah); ?> unit)</p>
                    <p><strong>Dari:</strong> <?php echo e($distribusi->cabangAsal->nama_cabang ?? '-'); ?> → <strong>Ke:</strong> <?php echo e($distribusi->cabangTujuan->nama_cabang ?? '-'); ?></p>
                    <p><strong>Status Saat Ini:</strong> <span class="badge bg-<?php echo e($distribusi->getStatusBadgeAttribute()); ?>"><?php echo e(ucfirst($distribusi->status)); ?></span></p>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Perubahan Status</h5>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="timeline-item mb-4 pb-4" style="border-bottom: 1px solid #dee2e6;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?php echo e($log->aktivitas); ?></h6>
                                    <p class="mb-2">
                                        <strong>Perubahan Status:</strong>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst(str_replace('_', ' ', $log->status_awal))); ?></span>
                                        <i class="bi bi-arrow-right"></i>
                                        <span class="badge bg-primary"><?php echo e(ucfirst(str_replace('_', ' ', $log->status_baru))); ?></span>
                                    </p>
                                    <?php if($log->user): ?>
                                        <p class="mb-2">
                                            <strong>Diubah oleh:</strong> <?php echo e($log->user->name); ?> (<?php echo e($log->user->email); ?>)
                                        </p>
                                    <?php endif; ?>
                                    <?php if($log->catatan): ?>
                                        <p class="mb-2">
                                            <strong>Catatan:</strong> <?php echo e($log->catatan); ?>

                                        </p>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted">
                                    <?php echo e(\Carbon\Carbon::parse($log->tanggal_aktivitas)->format('d-m-Y H:i:s')); ?>

                                </small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center">Tidak ada history untuk distribusi ini.</p>
                    <?php endif; ?>

                    <!-- Pagination -->
                    <?php if($logs->hasPages()): ?>
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php echo e($logs->links('pagination::bootstrap-5')); ?>

                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Informasi Distribusi</h6>
                </div>
                <div class="card-body">
                    <p>
                        <strong>ID:</strong> #<?php echo e($distribusi->id); ?>

                    </p>
                    <p>
                        <strong>Barang:</strong><br>
                        <?php echo e($distribusi->barang->nomor_barang ?? '-'); ?><br>
                        <?php echo e($distribusi->barang->nama_barang ?? '-'); ?>

                    </p>
                    <p>
                        <strong>Jumlah:</strong> <?php echo e($distribusi->jumlah); ?> unit
                    </p>
                    <p>
                        <strong>Asal:</strong><br>
                        <?php echo e($distribusi->cabangAsal->nama_cabang ?? '-'); ?>

                    </p>
                    <p>
                        <strong>Tujuan:</strong><br>
                        <?php echo e($distribusi->cabangTujuan->nama_cabang ?? '-'); ?>

                    </p>
                    <p>
                        <strong>Tanggal Kirim:</strong><br>
                        <?php echo e(\Carbon\Carbon::parse($distribusi->tanggal_kirim)->format('d-m-Y')); ?>

                    </p>
                    <?php if($distribusi->tanggal_terima): ?>
                        <p>
                            <strong>Tanggal Terima:</strong><br>
                            <?php echo e(\Carbon\Carbon::parse($distribusi->tanggal_terima)->format('d-m-Y')); ?>

                        </p>
                    <?php endif; ?>
                    <p>
                        <strong>Total Aktivitas:</strong> <?php echo e($distribusi->activityLogs()->count()); ?>

                    </p>
                </div>
            </div>

            <a href="<?php echo e(route('distribusi.show', $distribusi->id)); ?>" class="btn btn-primary btn-sm w-100 mt-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/distribusi/activity-log.blade.php ENDPATH**/ ?>