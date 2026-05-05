

<?php $__env->startSection('title', 'Detail Perangkat Jaringan'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-info-circle"></i> Detail Perangkat Jaringan</h1>
    <div>
        <a href="<?php echo e(route('perangkat-jaringan.edit', $perangkatJaringan->id)); ?>" class="btn btn-warning btn-custom">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="<?php echo e(route('perangkat-jaringan.activity-log', $perangkatJaringan->id)); ?>" class="btn btn-info btn-custom">
            <i class="bi bi-clock-history"></i> Log Aktivitas
        </a>
        <a href="<?php echo e(route('perangkat-jaringan.index')); ?>" class="btn btn-secondary btn-custom">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Perangkat</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nomor Inventaris:</strong>
                    </div>
                    <div class="col-md-8">
                        <strong><?php echo e($perangkatJaringan->nomor_inventaris); ?></strong>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nama Perangkat:</strong>
                    </div>
                    <div class="col-md-8">
                        <?php echo e($perangkatJaringan->nama_perangkat); ?>

                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tipe Perangkat:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-secondary"><?php echo e($perangkatJaringan->tipe_perangkat); ?></span>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Lokasi:</strong>
                    </div>
                    <div class="col-md-8">
                        <?php echo e($perangkatJaringan->lokasi); ?>

                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>IP Address:</strong>
                    </div>
                    <div class="col-md-8">
                        <code><?php echo e($perangkatJaringan->ip_address ?? '-'); ?></code>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>MAC Address:</strong>
                    </div>
                    <div class="col-md-8">
                        <code><?php echo e($perangkatJaringan->mac_address ?? '-'); ?></code>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge <?php if($perangkatJaringan->status == 'aktif'): ?> badge-status-aktif <?php else: ?> badge-status-tidak-aktif <?php endif; ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $perangkatJaringan->status))); ?>

                        </span>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tanggal Pemasangan:</strong>
                    </div>
                    <div class="col-md-8">
                        <?php echo e($perangkatJaringan->tanggal_pemasangan->format('d F Y')); ?>

                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Keterangan:</strong>
                    </div>
                    <div class="col-md-8">
                        <?php echo e($perangkatJaringan->keterangan ?? 'Tidak ada keterangan'); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-history"></i> Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $logs->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mb-3">
                        <small class="text-muted"><?php echo e($log->tanggal_aktivitas->format('d-m-Y H:i')); ?></small>
                        <p class="mb-1"><strong><?php echo e($log->aktivitas); ?></strong></p>
                        <small class="text-secondary"><?php echo e($log->deskripsi); ?></small>
                        <hr>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted text-center">Belum ada aktivitas</p>
                <?php endif; ?>
                
                <?php if($logs->count() > 5): ?>
                    <a href="<?php echo e(route('perangkat-jaringan.activity-log', $perangkatJaringan)); ?>" class="btn btn-sm btn-info btn-custom w-100">
                        <i class="bi bi-arrow-right"></i> Lihat Semua
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/perangkat_jaringan/show.blade.php ENDPATH**/ ?>