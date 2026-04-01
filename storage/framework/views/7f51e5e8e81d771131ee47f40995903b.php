

<?php $__env->startSection('title', 'Daftar Laporan Pihak ke 1'); ?>

<?php $__env->startSection('content'); ?>
<h1 class="mb-4"><i class="bi bi-file-earmark-arrow-down"></i> Daftar Laporan Pihak ke 1</h1>
<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<div class="mb-3 d-flex justify-content-between align-items-center">
    <form method="GET" action="" class="d-flex align-items-center" style="gap:8px;">
        <input type="search" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari nama laporan / file dokumen..." style="width:260px;">
        <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
        <?php if(request()->filled('q')): ?>
            <a href="<?php echo e(route('laporan-ttd.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </form>
    <a href="<?php echo e(route('laporan-ttd.create')); ?>" class="btn btn-primary btn-custom">
        <i class="bi bi-upload"></i> Upload Laporan Baru
    </a>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Laporan</th>
            <th>Cabang</th>
            <th>Uploader</th>
            <th>Tanggal Upload</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $laporans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($loop->iteration); ?></td>
            <td>
                <?php $q = request('q'); ?>
                <?php if($q): ?>
                    <?php echo preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark>$1</mark>', e($laporan->nama_laporan)); ?>

                <?php else: ?>
                    <?php echo e($laporan->nama_laporan); ?>

                <?php endif; ?>
            </td>
            <td><?php echo e($laporan->cabang->nama_cabang ?? '-'); ?></td>
            <td><?php echo e($laporan->uploader->name ?? '-'); ?></td>
            <td><?php echo e($laporan->created_at->format('d-m-Y H:i')); ?></td>
            <td>
                <?php $q = request('q'); ?>
                <a href="<?php echo e(route('laporan-ttd.download', $laporan->id)); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-download"></i>
                    <?php if($q): ?>
                        <?php echo preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark>$1</mark>', e($laporan->file)); ?>

                    <?php else: ?>
                        Download
                    <?php endif; ?>
                </a>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="6" class="text-center">Belum ada laporan diunggah.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<div class="mb-3 d-flex justify-content-end">
    <a href="<?php echo e(route('dokumen-barang-pihak2.create')); ?>" class="btn btn-primary btn-custom">
        <i class="bi bi-upload"></i> Upload Laporan Baru
    </a>
</div>
<hr class="my-4">
<h2 class="mb-3"><i class="bi bi-file-earmark-text"></i> Dokumen Barang Pihak ke 2</h2>
<div class="mb-3 d-flex justify-content-between align-items-center">
    <form method="GET" action="" class="d-flex align-items-center" style="gap:8px;">
        <input type="search" name="q2" value="<?php echo e(request('q2')); ?>" class="form-control form-control-sm" placeholder="Cari nama dokumen / file..." style="width:260px;">
        <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
        <?php if(request()->filled('q2')): ?>
            <a href="<?php echo e(url()->current()); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Dokumen</th>
            <th>Cabang</th>
            <th>Uploader</th>
            <th>Tanggal Upload</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $laporanPihak2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($loop->iteration); ?></td>
            <td><?php echo e($dok->nama_laporan); ?></td>
            <td><?php echo e($dok->cabang->nama_cabang ?? '-'); ?></td>
            <td><?php echo e($dok->uploader->name ?? '-'); ?></td>
            <td><?php echo e($dok->created_at->format('d-m-Y H:i')); ?></td>
            <td>
                <a href="<?php echo e(route('dokumen-barang-pihak2.download', $dok->id)); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-download"></i> Download
                </a>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="6" class="text-center">Belum ada dokumen pihak ke 2.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/laporan_ttd/index.blade.php ENDPATH**/ ?>