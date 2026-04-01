

<?php $__env->startSection('title', 'Detail Draft Dokumen Distribusi'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-eye"></i> Detail Draft Dokumen Distribusi</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Kode Barang</th>
                <td><?php echo e($draft->barang->nomor_barang ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <td><?php echo e($draft->barang->nama_barang ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Cabang Tujuan</th>
                <td><?php echo e($draft->cabangTujuan->nama_cabang ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Tanggal Distribusi</th>
                <td><?php echo e($draft->tanggal_kirim ? $draft->tanggal_kirim->format('d-m-Y') : '-'); ?></td>
            </tr>
            <tr>
                <th>Jam Distribusi</th>
                <td><?php echo e($draft->tanggal_kirim ? $draft->tanggal_kirim->format('H:i') : '-'); ?></td>
            </tr>
            <tr>
                <th>Dokumen PDF</th>
                <td>
                    <?php if($draft->dokumen_pdf): ?>
                        <a href="<?php echo e(route('draft-dokumen-distribusi.download', $draft->id)); ?>" class="btn btn-success btn-sm" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Download
                        </a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <a href="<?php echo e(route('draft-dokumen-distribusi.index')); ?>" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/draft-dokumen-distribusi/show.blade.php ENDPATH**/ ?>