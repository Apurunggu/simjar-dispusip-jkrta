

<?php $__env->startSection('title', 'Import Barang Masuk'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-file-earmark-arrow-up"></i> Import Barang Masuk</h1>
    <a href="<?php echo e(route('barang-masuk.index')); ?>" class="btn btn-secondary btn-custom">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<!-- Download Template -->
<div class="card mb-4 border-success">
    <div class="card-body text-center">
        <h5 class="mb-3">Belum punya template?</h5>
        <a href="<?php echo e(asset('sample_import_barang.xlsx')); ?>" class="btn btn-success btn-lg">
            <i class="bi bi-download"></i> Download Template Excel
        </a>
        <p class="text-muted mt-2">File ini sudah berisi format yang benar dengan contoh data</p>
    </div>
</div>

<!-- Import Form -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-upload"></i> Upload File</h5>
    </div>
    <div class="card-body">
        <form action="<?php echo e(route('barang-masuk.import')); ?>" method="POST" enctype="multipart/form-data" id="importForm">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label for="file" class="form-label">Pilih file Excel</label>
                <input type="file" name="file" id="file" class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    accept=".xlsx,.xls,.csv" required onchange="validateFile(this)">
                <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback" style="display: block;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="form-text" id="fileInfo"></div>
            </div>

            <button type="submit" class="btn btn-primary btn-custom" id="submitBtn">
                <i class="bi bi-upload"></i> Import
            </button>
            <a href="<?php echo e(route('barang-masuk.index')); ?>" class="btn btn-secondary btn-custom">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function validateFile(input) {
    const fileInfo = document.getElementById('fileInfo');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!input.files || !input.files[0]) {
        fileInfo.innerHTML = '';
        submitBtn.disabled = false;
        return;
    }
    
    const file = input.files[0];
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                         'application/vnd.ms-excel',
                         'text/csv',
                         'application/vnd.ms-excel.sheet.binary.macroEnabled.12'];
    
    // Check file size
    if (file.size > maxSize) {
        fileInfo.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle"></i> File terlalu besar (max 5MB)</span>';
        submitBtn.disabled = true;
        input.value = '';
        return;
    }
    
    // Check file type
    if (!allowedTypes.includes(file.type)) {
        fileInfo.innerHTML = '<span class="text-warning"><i class="bi bi-info-circle"></i> Format mungkin tidak valid. File akan dicek saat upload.</span>';
    } else {
        fileInfo.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> ' + file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)</span>';
    }
    
    submitBtn.disabled = false;
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/barang_masuk/import.blade.php ENDPATH**/ ?>