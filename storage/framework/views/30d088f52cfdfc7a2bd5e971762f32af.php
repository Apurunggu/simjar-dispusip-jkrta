

<?php $__env->startSection('title', 'Buat Distribusi Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-truck"></i> Buat Distribusi Barang</h1>

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

        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('distribusi.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label for="barang_id" class="form-label">Barang <span class="text-danger">*</span></label>
                        <select name="barang_id" id="barang_id" class="form-select select2 <?php $__errorArgs = ['barang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php $__currentLoopData = $barangMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($barang->id); ?>" <?php echo e(old('barang_id') == $barang->id ? 'selected' : ''); ?>>
                                    <?php echo e($barang->nama_barang); ?> (Stok: <?php echo e($barang->stok); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['barang_id'];
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
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('#barang_id').select2({
                                placeholder: '-- Pilih Barang --',
                                allowClear: true
                            });
                        });
                    </script>

                    <div id="info-barang" class="mb-3" style="display:none;">
                        <div class="card card-body bg-light border">
                            <div class="row g-2">
                                <div class="col-md-4"><strong>Satuan:</strong> <span id="info-satuan">-</span></div>
                                <div class="col-md-4"><strong>Stok:</strong> <span id="info-stok">-</span></div>
                                <div class="col-md-4"><strong>Kategori:</strong> <span id="info-kategori">-</span></div>
                                <div class="col-md-4"><strong>Posisi:</strong> <span id="info-posisi">-</span></div>
                                <div class="col-md-4"><strong>Status:</strong> <span id="info-status">-</span></div>
                                <div class="col-md-4"><strong>Tahun Pengadaan:</strong> <span id="info-tahun">-</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cabang_asal_id" class="form-label">Cabang Asal <span class="text-danger">*</span></label>
                        <select name="cabang_asal_id" id="cabang_asal_id" class="form-select <?php $__errorArgs = ['cabang_asal_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Cabang Asal --</option>
                            <?php $__currentLoopData = $cabangAsal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cabang->id); ?>" <?php echo e(old('cabang_asal_id') == $cabang->id ? 'selected' : ''); ?>>
                                    <?php echo e($cabang->nama_cabang); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['cabang_asal_id'];
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
                        <label for="cabang_tujuan_id" class="form-label">Cabang Tujuan <span class="text-danger">*</span></label>
                        <select name="cabang_tujuan_id" id="cabang_tujuan_id" class="form-select <?php $__errorArgs = ['cabang_tujuan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Cabang Tujuan --</option>
                            <?php $__currentLoopData = $cabangTujuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cabang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cabang->id); ?>" <?php echo e(old('cabang_tujuan_id') == $cabang->id ? 'selected' : ''); ?>>
                                    <?php echo e($cabang->nama_cabang); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['cabang_tujuan_id'];
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
                        <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control <?php $__errorArgs = ['jumlah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               min="1" value="<?php echo e(old('jumlah')); ?>" required>
                        <?php $__errorArgs = ['jumlah'];
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

                    <!--
                    <div class="mb-3">
                        <label for="tanggal_kirim" class="form-label">Tanggal Kirim <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kirim" id="tanggal_kirim" class="form-control <?php $__errorArgs = ['tanggal_kirim'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('tanggal_kirim', date('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['tanggal_kirim'];
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
                    -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kirim</label>
                        <input type="text" class="form-control" value="Otomatis saat submit" disabled>
                        <div class="form-text">Tanggal kirim akan diisi otomatis saat distribusi dibuat.</div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"><?php echo e(old('keterangan')); ?></textarea>
                        <?php $__errorArgs = ['keterangan'];
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

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Distribusi
                        </button>
                        <a href="<?php echo e(route('distribusi.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Informasi</h6>
                <p class="small text-muted mb-2">
                    <strong>Alur Distribusi:</strong>
                </p>
                <ol class="small">
                    <li>Pilih barang dari stok pusat</li>
                    <li>Tentukan cabang tujuan</li>
                    <li>Masukkan jumlah yang didistribusikan</li>
                    <li>Stok pusat otomatis berkurang</li>
                    <li>Admin cabang tujuan menerima barang</li>
                </ol>
                <div class="alert alert-info small mt-3 mb-0">
                    <i class="bi bi-info-circle"></i> Stok barang akan otomatis berkurang setelah distribusi dibuat
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barang_id');
    const infoBox = document.getElementById('info-barang');
    const satuan = document.getElementById('info-satuan');
    const stok = document.getElementById('info-stok');
    const kategori = document.getElementById('info-kategori');
    const posisi = document.getElementById('info-posisi');
    const status = document.getElementById('info-status');
    const tahun = document.getElementById('info-tahun');

    barangSelect.addEventListener('change', function() {
        const id = this.value;
        if (!id) {
            infoBox.style.display = 'none';
            satuan.textContent = '-';
            stok.textContent = '-';
            kategori.textContent = '-';
            posisi.textContent = '-';
            status.textContent = '-';
            tahun.textContent = '-';
            return;
        }
        fetch(`/distribusi-barang/info/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    infoBox.style.display = 'none';
                } else {
                    satuan.textContent = data.satuan ?? '-';
                    stok.textContent = data.stok ?? '-';
                    kategori.textContent = data.kategori ?? '-';
                    posisi.textContent = data.posisi ?? '-';
                    status.textContent = data.status ?? '-';
                    tahun.textContent = data.tahun_pengadaan ?? '-';
                    infoBox.style.display = '';
                }
            });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/distribusi/create.blade.php ENDPATH**/ ?>