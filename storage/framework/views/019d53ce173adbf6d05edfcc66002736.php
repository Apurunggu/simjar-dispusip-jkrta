

<?php $__env->startSection('title', 'Log Aktivitas Perangkat'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-clock-history"></i> Log Aktivitas Perangkat</h1>
    <a href="<?php echo e(route('perangkat-jaringan.show', $perangkatJaringan->id)); ?>" class="btn btn-secondary btn-custom">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <strong>Perangkat:</strong> <?php echo e($perangkatJaringan->nama_perangkat); ?><br>
                <strong>Nomor Inventaris:</strong> <?php echo e($perangkatJaringan->nomor_inventaris); ?>

            </div>
            <div class="col-md-6 text-end">
                <strong>Total Aktivitas:</strong> <?php echo e($logs->total()); ?><br>
                <strong>Status:</strong> 
                <span class="badge <?php if($perangkatJaringan->status == 'aktif'): ?> badge-status-aktif <?php else: ?> badge-status-tidak-aktif <?php endif; ?>">
                    <?php echo e(ucfirst(str_replace('_', ' ', $perangkatJaringan->status))); ?>

                </span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tanggal & Waktu</th>
                        <th>Aktivitas</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration + ($logs->currentPage() - 1) * $logs->perPage()); ?></td>
                            <td>
                                <strong><?php echo e($log->tanggal_aktivitas->format('d-m-Y')); ?></strong><br>
                                <small class="text-muted"><?php echo e($log->tanggal_aktivitas->format('H:i:s')); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?php echo e($log->aktivitas); ?></span>
                            </td>
                            <td><?php echo e($log->deskripsi ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada log aktivitas</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php echo e($logs->links('pagination::bootstrap-5')); ?>

            </ul>
        </nav>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/perangkat_jaringan/activity_log.blade.php ENDPATH**/ ?>