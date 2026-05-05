

<?php $__env->startSection('title', 'Distribusi Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-truck"></i> Distribusi Barang</h1>
        <div class="mb-3">
            <a href="<?php echo e(route('distribusi.exportPdf', ['type' => 'serah-terima'])); ?>" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF Serah Terima
            </a>
            <a href="<?php echo e(route('distribusi.exportPdf', ['type' => 'pinjam'])); ?>" class="btn btn-warning">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF Pinjam
            </a>
            <a href="<?php echo e(route('distribusi.exportWord')); ?>" class="btn btn-primary">
                <i class="bi bi-file-earmark-word"></i> Export Word
            </a>
        </div>
        
        <?php if(auth()->user()->hasAnyRole(['super_admin', 'staff'])): ?>
            <a href="<?php echo e(route('distribusi.create')); ?>" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Buat Distribusi Baru
            </a>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi Kesalahan!</strong>
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-table"></i> Daftar Distribusi</h5>
            </div>
            <div class="card-body">
                <?php if($distribusi->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Dari Cabang</th>
                                    <th>Ke Cabang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Status</th>
                                    <th>Terpasang?</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $distribusi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($d->barang->nama_barang ?? '-'); ?></td>
                                        <td><?php echo e($d->cabangAsal->nama_cabang ?? '-'); ?></td>
                                        <td><?php echo e($d->cabangTujuan->nama_cabang ?? '-'); ?></td>
                                        <td><strong><?php echo e($d->jumlah); ?></strong></td>
                                        <td><?php echo e($d->tanggal_kirim->format('d-m-Y')); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($d->getStatusBadgeAttribute()); ?>">
                                                <?php echo e(ucfirst($d->status)); ?>

                                            </span>
                                        </td>
                                        <td>
                                                <form action="<?php echo e(route('distribusi.distribusi.updateStatus', $d)); ?>" method="POST" style="display:inline-block">
                                                <?php echo csrf_field(); ?>
                                                <div class="btn-group" role="group">
                                                    <button type="submit" name="is_terpasang" value="terpasang" class="btn btn-sm <?php echo e($d->is_terpasang == 'terpasang' ? 'btn-success active' : 'btn-outline-success'); ?>">Terpasang</button>
                                                    <button type="submit" name="is_terpasang" value="tidak_terpasang" class="btn btn-sm <?php echo e($d->is_terpasang == 'tidak_terpasang' ? 'btn-danger active' : 'btn-outline-danger'); ?>">Tidak Terpasang</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('distribusi.show', $d)); ?>" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            <?php if($d->status === 'pending' && auth()->user()->hasAnyRole(['super_admin', 'staff'])): ?>
                                                <form action="<?php echo e(route('distribusi.destroy', $d)); ?>" method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus distribusi ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Belum ada distribusi barang
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                        <!-- Tabel Kode Barang -->
                    <?php echo e($distribusi->links()); ?>

                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada data distribusi barang</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/distribusi/index.blade.php ENDPATH**/ ?>