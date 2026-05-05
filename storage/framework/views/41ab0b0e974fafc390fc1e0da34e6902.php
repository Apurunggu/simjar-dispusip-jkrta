

<?php $__env->startSection('title', 'Detail User'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Detail User</h2>
    <div class="card mt-3">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nama</dt>
                <dd class="col-sm-9"><?php echo e($user->name); ?></dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><?php echo e($user->email); ?></dd>

                <dt class="col-sm-3">Role</dt>
                <dd class="col-sm-9"><?php echo e($user->role->label ?? '-'); ?></dd>

                <dt class="col-sm-3">Cabang</dt>
                <dd class="col-sm-9"><?php echo e($user->cabang->nama_cabang ?? '-'); ?></dd>

                <dt class="col-sm-3">Tanggal Dibuat</dt>
                <dd class="col-sm-9"><?php echo e($user->created_at ? $user->created_at->format('d-m-Y H:i') : '-'); ?></dd>

                <dt class="col-sm-3">Tanggal Update</dt>
                <dd class="col-sm-9"><?php echo e($user->updated_at ? $user->updated_at->format('d-m-Y H:i') : '-'); ?></dd>

                <dt class="col-sm-3">Password</dt>
                <dd class="col-sm-9">
                    <span id="pw-text">********</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="togglePassword()">Lihat Password</button>
                </dd>
            </dl>
            <form action="<?php echo e(route('user-management.reset-password', $user->id)); ?>" method="POST" class="mt-3 d-inline-block" onsubmit="return confirm('Reset password user ini?')">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <input type="text" name="password" class="form-control form-control-sm" placeholder="Password baru" required minlength="4">
                    <button type="submit" class="btn btn-warning btn-sm">Reset Password</button>
                </div>
            </form>
            <a href="<?php echo e(route('user-management.index')); ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<script>
function togglePassword() {
    var pw = document.getElementById('pw-text');
    if (pw.innerText === '********') {
        pw.innerText = <?php echo json_encode($user->password ?? '-', 15, 512) ?>;
    } else {
        pw.innerText = '********';
    }
}
</script>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/user_management/show.blade.php ENDPATH**/ ?>