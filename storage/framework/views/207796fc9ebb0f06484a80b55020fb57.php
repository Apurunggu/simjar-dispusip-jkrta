

<?php $__env->startSection('title', 'Detail Distribusi Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <h1><i class="bi bi-truck"></i> Detail Distribusi</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Distribusi</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>ID Distribusi:</strong></p>
                        <p>#<?php echo e($distribusi->id); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong></p>
                        <p>
                            <span class="badge bg-<?php echo e($distribusi->getStatusBadgeAttribute()); ?> fs-6">
                                <?php echo e(ucfirst($distribusi->status)); ?>

                            </span>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Barang:</strong></p>
                        <p><?php echo e($distribusi->barang->nama_barang ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Kategori:</strong></p>
                        <p><?php echo e($distribusi->barang->kategori ?? '-'); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Cabang Asal:</strong></p>
                        <p><?php echo e($distribusi->cabangAsal->nama_cabang ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Cabang Tujuan:</strong></p>
                        <p><?php echo e($distribusi->cabangTujuan->nama_cabang ?? '-'); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Jumlah:</strong></p>
                        <p><span class="badge bg-info"><?php echo e($distribusi->jumlah); ?> Unit</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tanggal Kirim:</strong></p>
                        <p><?php echo e(optional($distribusi->tanggal_kirim)->format('d-m-Y') ?? '-'); ?></p>
                    </div>
                </div>

                <?php if($distribusi->tanggal_terima): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Tanggal Terima:</strong></p>
                            <p><?php echo e(optional($distribusi->tanggal_terima)->format('d-m-Y') ?? '-'); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <p><strong>Keterangan:</strong></p>
                        <p><?php echo e($distribusi->keterangan ?? '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update Section -->
        <?php if(auth()->user()->hasAnyRole(['super_admin', 'admin_cabang'])): ?>
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('distribusi.distribusi.updateStatus', $distribusi)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Baru</label>
                            <select name="status" id="status" class="form-select" required>
                                <?php if(auth()->user()->hasRole('super_admin')): ?>
                                    <option value="pending" <?php echo e($distribusi->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="dikirim" <?php echo e($distribusi->status == 'dikirim' ? 'selected' : ''); ?>>Dikirim</option>
                                    <option value="diterima" <?php echo e($distribusi->status == 'diterima' ? 'selected' : ''); ?>>Diterima</option>
                                    <option value="ditolak" <?php echo e($distribusi->status == 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                                <?php elseif(auth()->user()->hasRole('admin_cabang')): ?>
                                    <option value="diterima" <?php echo e($distribusi->status == 'diterima' ? 'selected' : ''); ?>>Diterima</option>
                                    <option value="ditolak" <?php echo e($distribusi->status == 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan untuk perubahan status ini..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Status
                        </button>
                        <a href="<?php echo e(route('distribusi.activity-log', $distribusi->id)); ?>" class="btn btn-info">
                            <i class="bi bi-clock-history"></i> Lihat History
                        </a>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Info Pembuat</h6>
                <p class="small mb-2">
                    <strong>Dibuat oleh:</strong><br>
                    <?php echo e($distribusi->user->name ?? '-'); ?>

                </p>
                <p class="small mb-0">
                    <strong>Tanggal dibuat:</strong><br>
                    <?php echo e(optional($distribusi->created_at)->format('d-m-Y H:i') ?? '-'); ?>

                </p>
            </div>
        </div>

        <div class="mt-3">
            <a href="<?php echo e(route('distribusi.index')); ?>" class="btn btn-secondary w-100">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/distribusi/show.blade.php ENDPATH**/ ?>