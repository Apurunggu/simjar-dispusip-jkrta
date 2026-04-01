

<?php $__env->startSection('title', 'Draft Dokumen Distribusi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="bi bi-file-earmark-pdf"></i> Draft Dokumen Distribusi</h1>
    <a href="<?php echo e(route('draft-dokumen-distribusi.create')); ?>" class="btn btn-primary">
        <i class="bi bi-upload"></i> Upload Draft Baru
    </a>
</div>
<div class="mb-3">
    <form method="GET" action="" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari barang, kode, cabang..." value="<?php echo e(request('search')); ?>">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Serial Number</th>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th>Cabang Tujuan</th>
                        <th>Tanggal Distribusi</th>
                        <th>Jam</th>
                        <th>Dokumen PDF</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $drafts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $draft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($i+1); ?></td>
                            <td>
                                <?php if($draft->barang && $draft->barang->serialNumbers && $draft->barang->serialNumbers->count()): ?>
                                    <?php $__currentLoopData = $draft->barang->serialNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-dark text-white"><?php echo e($sn->serial_number); ?></span><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php elseif($draft->barang && $draft->barang->nomor_barang): ?>
                                    <span class="badge bg-dark text-white"><?php echo e($draft->barang->nomor_barang); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($draft->barang->nama_barang ?? '-'); ?></td>
                            <td><?php echo e($draft->barang->kategori ?? '-'); ?></td>
                            <td><?php echo e($draft->cabangTujuan->nama_cabang ?? '-'); ?></td>
                            <td><?php echo e($draft->tanggal_kirim ? $draft->tanggal_kirim->format('d-m-Y') : '-'); ?></td>
                            <td><?php echo e($draft->created_at ? $draft->created_at->format('H:i') : '-'); ?></td>
                            <td>
                                <?php if($draft->dokumen_pdf): ?>
                                    <a href="<?php echo e(route('draft-dokumen-distribusi.download', $draft->id)); ?>" class="btn btn-success btn-sm" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Download
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="d-flex gap-1">
                                <a href="<?php echo e(route('draft-dokumen-distribusi.show', $draft->id)); ?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> Detail</a>
                                <form action="<?php echo e(route('draft-dokumen-distribusi.destroy', $draft->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus draft ini?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center">Belum ada draft dokumen distribusi.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/draft-dokumen-distribusi/index.blade.php ENDPATH**/ ?>