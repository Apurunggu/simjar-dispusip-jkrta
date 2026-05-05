

<?php $__env->startSection('title', 'Perangkat Jaringan'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-router"></i> Inventaris Perangkat Jaringan</h1>
    <a href="<?php echo e(route('perangkat-jaringan.create')); ?>" class="btn btn-primary btn-custom">
        <i class="bi bi-plus-circle"></i> Tambah Perangkat
    </a>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('perangkat-jaringan.index')); ?>" class="row g-3">
            <div class="col-md-6">
                <label for="lokasi" class="form-label">Filter Berdasarkan Lokasi</label>
                <select class="form-select" id="lokasi" name="lokasi">
                    <option value="">-- Semua Lokasi --</option>
                    <?php $__currentLoopData = $lokasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($l); ?>" <?php if(request('lokasi') == $l): ?> selected <?php endif; ?>><?php echo e($l); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-custom">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="<?php echo e(route('perangkat-jaringan.index')); ?>" class="btn btn-secondary btn-custom">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nomor Inventaris</th>
                        <th>Nama Perangkat</th>
                        <th>Tipe</th>
                        <th>Lokasi</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $perangkat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration + ($perangkat->currentPage() - 1) * $perangkat->perPage()); ?></td>
                            <td><strong><?php echo e($item->nomor_inventaris); ?></strong></td>
                            <td><?php echo e($item->nama_perangkat); ?></td>
                            <td><span class="badge bg-secondary"><?php echo e($item->tipe_perangkat); ?></span></td>
                            <td><?php echo e($item->lokasi); ?></td>
                            <td><?php echo e($item->ip_address ?? '-'); ?></td>
                            <td>
                                <span class="badge <?php if($item->status == 'aktif'): ?> badge-status-aktif <?php else: ?> badge-status-tidak-aktif <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $item->status))); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('perangkat-jaringan.show', $item->id)); ?>" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('perangkat-jaringan.edit', $item->id)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if($item->status == 'aktif'): ?>
                                    <form action="<?php echo e(route('perangkat-jaringan.deactivate', $item->id)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menonaktifkan?')" title="Nonaktifkan">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?php echo e(route('perangkat-jaringan.activate', $item->id)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin mengaktifkan?')" title="Aktifkan">
                                            <i class="bi bi-play-circle"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?php echo e(route('perangkat-jaringan.activity-log', $item->id)); ?>" class="btn btn-sm btn-primary" title="Log Aktivitas">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data perangkat jaringan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php echo e($perangkat->links('pagination::bootstrap-5')); ?>

            </ul>
        </nav>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/perangkat_jaringan/index.blade.php ENDPATH**/ ?>