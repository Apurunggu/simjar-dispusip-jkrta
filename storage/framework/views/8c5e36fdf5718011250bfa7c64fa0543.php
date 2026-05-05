<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - SIMJAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100vw;
            overflow-x: hidden;
            background: #f5f6fa;
        }
        body {
            background: none !important;
        }
        .auth-outer-container {
            width: 98vw;
            height: 96vh;
            min-height: 400px;
            min-width: 280px;
            max-width: 1200px;
            max-height: 800px;
            margin: 1.5vh auto;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 8px 48px 0 rgba(31, 38, 135, 0.18), 0 1.5px 8px 0 rgba(33,150,243,0.10);
            background: #fff;
            position: relative;
            display: flex;
        }
        .login-split {
            display: flex;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 1;
        }
        .login-left, .login-right {
            height: 100%;
            min-height: 0;
            min-width: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-right img, .login-right-panel img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 16px auto;
        }
        .navbar { display: none !important; }
        /* Responsive for mobile */
        @media (max-width: 900px) {
            .auth-outer-container {
                min-height: 0;
                height: auto;
                max-height: none;
            }
            .login-split {
                flex-direction: column;
                height: auto;
                min-height: 0;
                position: static;
            }
            .login-left, .login-right {
                width: 100%;
                min-width: 0;
                min-height: 0;
                height: auto;
                justify-content: center;
                align-items: flex-start;
                padding: 0 0 24px 0;
            }
            .login-right {
                padding-bottom: 32px;
            }
        }
        @media (max-width: 600px) {
            .auth-outer-container {
                width: 100vw;
                border-radius: 0;
                margin: 0;
            }
            .login-card {
                max-width: 98vw;
                padding: 8px 2px;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="auth-outer-container">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/layout-login.blade.php ENDPATH**/ ?>