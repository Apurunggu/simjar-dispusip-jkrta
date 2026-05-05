

<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    *, *::before, *::after { box-sizing: border-box; }
    html, body {
        height: 100%; margin: 0; padding: 0;
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(120deg, #236080 60%, #fff 100%);
        overflow: hidden; width: 100vw;
    }
    body {
        min-height: 100vh; height: 100vh; width: 100vw;
        display: flex; align-items: stretch; justify-content: stretch; overflow: hidden;
    }
    .login-split {
        display: flex; width: 100vw; height: 100vh; overflow: hidden; position: relative; z-index: 1;
    }
    .login-left {
        flex: 1; background: transparent; display: flex; align-items: center; justify-content: flex-end; padding-right: 0; height: 100vh;
    }
    .login-card {
        margin: 0 auto 24px auto;
        margin-top: -40px;
    }
    .card-header {
        text-align: center !important;
    }
    .login-right {
        flex: 1.1; background: url('/images/login.jpeg') center/cover no-repeat; display: flex; align-items: center; justify-content: center;
    }
    .login-right-panel {
        background: rgba(0,0,0,0.28); border-radius: 24px; padding: 48px 36px; max-width: 420px; text-align: center; box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    }
    .login-right-panel-title {
        font-size: 2.2rem; font-weight: 700; color: #fff; margin-bottom: 12px; line-height: 1.1;
    }
    .login-right-panel-desc {
        font-size: 1.1rem; color: #e3e8ee; margin-bottom: 32px;
    }
    .login-right-panel-link {
        display: inline-block; padding: 12px 38px; border: 2px solid #fff; border-radius: 32px; color: #fff; font-size: 1.15rem; font-weight: 600; text-decoration: none; background: rgba(255,255,255,0.08); transition: background 0.2s, color 0.2s; letter-spacing: 1px;
    }
    @media (max-width: 900px) {
        .login-split { flex-direction: column; height: 100vh; }
        .login-left, .login-right {
            /* width: 100vw; min-height: 0; justify-content: center; height: 50vh; */
            justify-content: center; height: 50vh;
        }
        .login-card { margin: 0 auto 24px auto; }
    }
    @media (max-width: 600px) {
        .login-card { padding: 8px 2px; max-width: 98vw; }
    }
</style>
<?php $__env->stopPush(); ?>

    <?php $__env->startSection('content'); ?>
    <div class="login-split">
        <!-- Kiri: Form Login -->
        <div class="login-left">
            <div class="login-card">
                <div class="card-header" style="background:none;color:#222;font-size:2rem;font-weight:700;box-shadow:none;text-shadow:none;justify-content:flex-start;text-align:left;">
                    Login SIMJAR.
                </div>
                <div class="card-body" style="padding:0 0 0 0;">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div><?php echo e($error); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('login')); ?>" style="padding: 0 32px 0 32px;">
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
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                            <div>
                                <input type="checkbox" id="remember" name="remember" style="margin-right:4px;">
                                <label for="remember" style="font-size:0.98rem;color:#555;">Remember me</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2" style="background:#4ecdc4;border-radius:24px;font-size:1.1rem;font-weight:600;border:none;">
                            Login
                        </button>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-primary w-100 mb-2" style="margin-top:8px;border-radius:24px;font-size:1.1rem;font-weight:600;border:2px solid #4ecdc4;color:#1976d2;background:#fff;transition:background 0.2s, color 0.2s;">Register</a>
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

<?php echo $__env->make('layout-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/auth/login.blade.php ENDPATH**/ ?>