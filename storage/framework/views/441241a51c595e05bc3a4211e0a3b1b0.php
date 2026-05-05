

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <h1 style="color: #ffffff; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Dashboard</h1>
        <p class="lead" style="font-weight: 700; font-size: 1.3rem; color: #ffffff; text-shadow: 1px 1px 3px rgba(0,0,0,0.2);"><strong>Selamat datang di SIMJAR - Sistem Inventory dan Distribusi Perangkat Jaringan</strong></p>
        
        <div class="row mt-4">
            <!-- Barang Masuk -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #000000; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><i class="bi bi-box-seam"></i> Total Barang Masuk</h5>
                        <h2><?php echo e($totalBarangMasuk ?? 0); ?></h2>
                        <small class="text-muted">Unit</small>
                        <div class="mt-2"><small class="text-secondary">Unik: <?php echo e($totalUnikBarang ?? 0); ?> jenis</small></div>
                    </div>
                </div>
            </div>

            <!-- Stok Tersedia -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #000000; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><i class="bi bi-bag-check"></i> Stok Pusat</h5>
                        <h2 class="text-success"><?php echo e($totalStok ?? 0); ?></h2>
                        <small class="text-muted">Unit tersedia</small>
                    </div>
                </div>
            </div>

            <!-- Terdistribusi -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #000000; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><i class="bi bi-truck"></i> Terdistribusi</h5>
                        <h2 class="text-info"><?php echo e($totalTerdistribusi ?? 0); ?></h2>
                        <small class="text-muted">Unit di cabang</small>
                    </div>
                </div>
            </div>

            <!-- Distribusi Pending -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #000000; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><i class="bi bi-clock-history"></i> Pending</h5>
                        <h2 class="text-warning"><?php echo e($distribusiPending ?? 0); ?></h2>
                        <small class="text-muted">Menunggu konfirmasi</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perangkat Jaringan -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #000000; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><i class="bi bi-router"></i> Perangkat Aktif</h5>
                        <h2 class="text-success"><?php echo e($totalPerangkatAktif ?? 0); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="color: #000000; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><i class="bi bi-x-circle"></i> Perangkat Tidak Aktif</h5>
                        <h2 class="text-danger"><?php echo e($totalPerangkatTidakAktif ?? 0); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unik per Kategori -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: #000000; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><i class="bi bi-grid-3x3-gap-fill"></i> Unik per Kategori</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <canvas id="chart-unique-kategori" height="140"></canvas>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <?php if(!empty($uniqueByKategori) && $uniqueByKategori->count()): ?>
                                <?php $__currentLoopData = $uniqueByKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-3 mb-2">
                                        <div class="p-2 border rounded text-center">
                                            <strong><?php echo e($k->kategori); ?></strong>
                                            <div class="text-muted"><?php echo e($k->unique_count); ?> jenis</div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="col-md-12 text-center text-muted">Tidak ada data kategori.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Cepat -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: #000000; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"><i class="bi bi-lightning-fill"></i> Menu Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="<?php echo e(route('barang-masuk.index')); ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-list"></i><br>Barang Masuk
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?php echo e(route('distribusi.index')); ?>" class="btn btn-info w-100">
                                    <i class="bi bi-truck"></i><br>Distribusi
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?php echo e(route('perangkat-jaringan.index')); ?>" class="btn btn-success w-100">
                                    <i class="bi bi-router"></i><br>Perangkat
                                </a>
                            </div>
                            <div class="col-md-3">
                                <?php if(auth()->user()->hasRole('super_admin')): ?>
                                    <a href="<?php echo e(route('barang-masuk.create')); ?>" class="btn btn-warning w-100">
                                        <i class="bi bi-plus-circle"></i><br>+ Barang
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('distribusi.create')); ?>" class="btn btn-warning w-100">
                                        <i class="bi bi-plus-circle"></i><br>+ Distribusi
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function(){
        const data = <?php echo json_encode($uniqueByKategori ?? []); ?>;
        if (!data || !data.length) return;
        const labels = data.map(d => d.kategori);
        const values = data.map(d => Number(d.unique_count));

        const ctx = document.getElementById('chart-unique-kategori');
        if (!ctx) return;

        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jenis Unik',
                    data: values,
                    backgroundColor: 'rgba(54,162,235,0.6)',
                    borderColor: 'rgba(54,162,235,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                layout: {
                    padding: {
                        bottom: 40
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: { size: 11 },
                            maxRotation: 0,
                            minRotation: 0,
                            autoSkip: false,
                            padding: 14,
                            callback: function(value, index, values) {
                                let label = this.getLabelForValue(value);
                                // Bagi label menjadi dua baris jika ada spasi dan panjang > 10
                                if (label.length > 10 && label.includes(' ')) {
                                    let parts = label.split(' ');
                                    let mid = Math.ceil(parts.length / 2);
                                    return [parts.slice(0, mid).join(' '), parts.slice(mid).join(' ')];
                                }
                                return label;
                            }
                        }
                    },
                    y: { beginAtZero: true, precision:0 }
                }
            }
        });
    })();
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/dashboard.blade.php ENDPATH**/ ?>