

<?php $__env->startSection('title', 'Upload Dokumen Barang Pihak ke 2'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Upload Dokumen Barang Pihak ke 2</h4>
    </div>
    <div class="card-body">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="<?php echo e(route('dokumen-barang-pihak2.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="nama_laporan" class="form-label">Nama Laporan</label>
                <input type="text" name="nama_laporan" id="nama_laporan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File Dokumen</label>
                <input type="file" name="file" id="file" class="form-control" required accept=".pdf,.doc,.docx">
            </div>
            <div class="mb-3">
                <label for="cabang_id" class="form-label">Cabang</label>
                <select name="cabang_id" id="cabang_id" class="form-control">
                    <option value="">- Pilih Cabang -</option>
                    <?php $__currentLoopData = $cabangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cabang->id); ?>"><?php echo e($cabang->nama_cabang); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Upload</button>
            <a href="<?php echo e(route('dokumen-barang-pihak2.index')); ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/dokumen-barang-pihak2/create.blade.php ENDPATH**/ ?>