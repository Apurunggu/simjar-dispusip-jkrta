

<?php $__env->startSection('title', 'Dokumen Barang Pihak ke 1'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4 d-flex justify-content-between align-items-center" style="gap:16px;">
    <div style="display: flex; align-items: center;">
        <h1 class="mb-0" style="font-size:2rem; line-height:1; display:inline-block; vertical-align:middle; color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);\"><i class="bi bi-file-earmark-arrow-down"></i> Dokumen Barang Pihak ke 1</h1>
    </div>
    <a href="<?php echo e(route('laporan-ttd.create')); ?>" class="btn btn-primary btn-custom" style="height:40px; display:flex; align-items:center;">
        <i class="bi bi-upload"></i> Upload Laporan Baru
    </a>
</div>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<div class="mb-4 d-flex justify-content-between align-items-center">
    <form method="GET" action="" class="d-flex align-items-center" style="gap:8px;">
        <input type="search" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari nama laporan / file dokumen..." style="width:260px;">
        <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
        <?php if(request()->filled('q')): ?>
            <a href="<?php echo e(route('laporan-ttd.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $laporans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <i class="bi bi-file-earmark-pdf" style="font-size:2.5rem; color:#dc3545;"></i>
                </div>
                <h5 class="card-title mb-2">
                    <?php $q = request('q'); ?>
                    <?php if($q): ?>
                        <?php echo preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark>$1</mark>', e($laporan->nama_laporan)); ?>

                    <?php else: ?>
                        <?php echo e($laporan->nama_laporan); ?>

                    <?php endif; ?>
                </h5>
                <div class="mb-3 text-muted" style="font-size:0.9rem;">
                    <div class="mb-2">
                        <small><strong>Cabang:</strong> <?php echo e($laporan->cabang->nama_cabang ?? '-'); ?></small>
                    </div>
                    <div class="mb-2">
                        <small><strong>Uploader:</strong> <?php echo e($laporan->uploader->name ?? '-'); ?></small>
                    </div>
                    <div>
                        <small><strong>Upload:</strong> <?php echo e($laporan->created_at->format('d-m-Y H:i')); ?></small>
                    </div>
                </div>
                <div class="mt-auto">
                    <a href="<?php echo e(route('laporan-ttd.download', $laporan->id)); ?>" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="alert alert-info text-center" role="alert">
            <i class="bi bi-info-circle"></i> Belum ada laporan diunggah.
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/laporan_ttd/index.blade.php ENDPATH**/ ?>