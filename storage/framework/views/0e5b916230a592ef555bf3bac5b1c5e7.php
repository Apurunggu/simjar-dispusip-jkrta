


<?php $__env->startSection('title', 'Register'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(120deg, #236080 60%, #fff 100%);
        overflow: hidden;
        width: 100vw;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="login-split">
    <!-- Kiri: Form Register -->
    <div class="login-left" style="flex:1;background:#fff;display:flex;align-items:center;justify-content:center;">
        <div class="login-card" style="margin:0;box-shadow:none;width:370px;max-width:90vw;margin-top:-40px;">
            <div class="card-header" style="background:none;color:#222;font-size:2rem;font-weight:700;box-shadow:none;text-shadow:none;text-align:center;">
                Register hire.
            </div>
            <div class="card-body" style="padding:0 0 0 0;">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('register')); ?>" style="padding: 0 32px 0 32px;">
                    <div class="mb-3">
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="name" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Nama Lengkap">
                        <?php $__errorArgs = ['name'];
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
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="Email">
                        <?php $__errorArgs = ['email'];
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
                        <div style="position:relative;">
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="password" name="password" required placeholder="Password" style="padding-right:38px;">
                            <span onclick="togglePassword('password', this)" style="position:absolute;top:50%;right:12px;transform:translateY(-50%);cursor:pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </span>
                            <?php $__errorArgs = ['password'];
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
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2" style="background:#4ecdc4;border-radius:24px;font-size:1.1rem;font-weight:600;border:none;">
                        Register
                    </button>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-primary w-100 mb-2" style="margin-top:8px;border-radius:24px;font-size:1.1rem;font-weight:600;border:2px solid #4ecdc4;color:#1976d2;background:#fff;transition:background 0.2s, color 0.2s;">Login</a>
                </form>
            </div>
        </div>
    </div>
    <!-- Kanan: Panel gambar saja -->
    <div class="login-right" style="flex:1.1;background:url('/images/login.jpeg') center/cover no-repeat;display:flex;align-items:center;justify-content:center;"></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function togglePassword(id, el) {
    const input = document.getElementById(id);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        el.querySelector('svg').style.opacity = 0.5;
    } else {
        input.type = 'password';
        el.querySelector('svg').style.opacity = 1;
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/auth/register.blade.php ENDPATH**/ ?>