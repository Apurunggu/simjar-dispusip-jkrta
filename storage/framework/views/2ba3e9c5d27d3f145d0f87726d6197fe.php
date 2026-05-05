<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'SIMJAR'); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS (jika ada) -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #1e3c72 0%, #2a5298 100%);">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-diagram-3"></i> SIMJAR
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link"><?php echo e(date('d F Y')); ?></span>
                    </li>
                    <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo e(auth()->user()->name ?? 'User'); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <span class="dropdown-item-text">
                                    Role: <strong><?php echo e(optional(auth()->user()->role)->label ?? 'N/A'); ?></strong>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>



    <div class="container-fluid">
        <div class="row" style="min-height: 100vh;">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar d-none d-md-block">
                <h6 class="text-white px-3 mb-3 mt-4">MENU UTAMA</h6>
                <a href="<?php echo e(route('dashboard')); ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <hr class="bg-white opacity-10">
                <h6 class="text-white px-3 mb-3 mt-3">MODUL</h6>
                <a href="<?php echo e(route('barang-masuk.index')); ?>">
                    <i class="bi bi-box-seam"></i> Barang Masuk
                </a>
                <a href="<?php echo e(route('distribusi.index')); ?>">
                    <i class="bi bi-truck"></i> Distribusi Barang
                </a>
                <a href="<?php echo e(route('distribusi.activity-report')); ?>">
                    <i class="bi bi-graph-up"></i> Laporan Aktivitas Distribusi
                </a>
                <a href="<?php echo e(route('perangkat-jaringan.index')); ?>">
                    <i class="bi bi-router"></i> Perangkat Jaringan
                </a>
                <a href="#dokumenBarang" data-bs-toggle="collapse" style="text-decoration: none; color: inherit;">
                    <i class="bi bi-file-earmark-text"></i> Dokumen Barang
                    <i class="bi bi-chevron-down" style="font-size: 0.7rem; margin-left: 10px;"></i>
                </a>
                <div class="collapse" id="dokumenBarang" style="margin-left: 20px; margin-top: 5px; margin-bottom: 10px;">
                    <a href="<?php echo e(route('laporan-ttd.index')); ?>" style="font-size: 0.9rem;">
                        <i class="bi bi-file-earmark-pdf"></i> Dokumen Pihak ke 1
                    </a>
                    <a href="<?php echo e(route('dokumen-barang-pihak2.index')); ?>" style="font-size: 0.9rem;">
                        <i class="bi bi-file-earmark-pdf"></i> Dokumen Pihak ke 2
                    </a>
                </div>
                <a href="<?php echo e(route('draft-dokumen-distribusi.index')); ?>" title="Draft Dokumen Distribusi">
                    <i class="bi bi-file-earmark-pdf"></i> Draft Dokumen Distribusi
                </a>
                <?php if(auth()->check() && auth()->user()->hasRole('super_admin')): ?>
                    <hr class="bg-white opacity-10">
                    <h6 class="text-white px-3 mb-3 mt-3">KONTROL AKSES</h6>
                    <a href="<?php echo e(route('user-management.index')); ?>">
                        <i class="bi bi-people"></i> Manajemen User
                    </a>
                <?php endif; ?>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-4 main-content">
                <?php if(isset($errors) && $errors->any()): ?>
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

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/layout.blade.php ENDPATH**/ ?>