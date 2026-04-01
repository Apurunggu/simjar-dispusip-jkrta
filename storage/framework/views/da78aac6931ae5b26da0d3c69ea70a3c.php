

<?php $__env->startSection('title', 'Upload Draft Dokumen Distribusi'); ?>

<?php $__env->startSection('content'); ?>
<h1 class="mb-4"><i class="bi bi-upload"></i> Upload Draft Dokumen Distribusi</h1>
<div class="card">
    <div class="card-body">
        <form action="<?php echo e(route('draft-dokumen-distribusi.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="distribusi_id" class="form-label">Pilih Distribusi</label>
                <select name="distribusi_id" id="distribusi_id" class="form-select <?php $__errorArgs = ['distribusi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">-- Pilih Distribusi --</option>
                    <?php $__currentLoopData = $distribusi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($d->id); ?>"><?php echo e($d->barang->nama_barang ?? '-'); ?> ke <?php echo e($d->cabangTujuan->nama_cabang ?? '-'); ?> (<?php echo e($d->tanggal_kirim ? $d->tanggal_kirim->format('d-m-Y') : '-'); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['distribusi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
                
            <div class="mb-3">
                <label for="dokumen_pdf" class="form-label">File PDF yang sudah ditandatangani</label>
                <input type="file" name="dokumen_pdf" id="dokumen_pdf" class="form-control <?php $__errorArgs = ['dokumen_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="application/pdf" required>
                <?php $__errorArgs = ['dokumen_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
            <a href="<?php echo e(route('draft-dokumen-distribusi.index')); ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/draft-dokumen-distribusi/create.blade.php ENDPATH**/ ?>