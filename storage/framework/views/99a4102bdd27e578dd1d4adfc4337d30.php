

<?php $__env->startSection('title', 'Halaman Tidak Ditemukan (404)'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card border-warning">
            <div class="card-header bg-warning">
                <h4 class="mb-0"><i class="bi bi-question-circle"></i> Halaman Tidak Ditemukan</h4>
            </div>
            <div class="card-body text-center py-5">
                <h1 class="display-1 text-warning">404</h1>
                <p class="fs-5 text-muted">Halaman yang Anda cari tidak ditemukan.</p>
                <p class="text-muted">Mungkin URL sudah berubah atau halaman telah dihapus.</p>
                
                <div class="mt-4">
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">
                        <i class="bi bi-house"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/errors/404.blade.php ENDPATH**/ ?>