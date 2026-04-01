

<?php $__env->startSection('title', 'Laporan Aktivitas Distribusi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4 text-center">Laporan Aktivitas Distribusi</h2>
    <form method="GET" action="<?php echo e(route('distribusi.activity-report')); ?>" class="mb-3">
        <div class="row mb-2">
            <div class="col-md-3">
                <label>Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control" value="<?php echo e(request('tanggal_awal')); ?>">
            </div>
            <!-- <div class="col-md-3">
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="<?php echo e(request('tanggal_akhir')); ?>">
            </div> -->
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Semua --</option>
                    <option value="dikirim" <?php echo e(request('status')=='dikirim'?'selected':''); ?>>Dikirim</option>
                    <option value="diterima" <?php echo e(request('status')=='diterima'?'selected':''); ?>>Diterima</option>
                    <option value="ditolak" <?php echo e(request('status')=='ditolak'?'selected':''); ?>>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-8 text-end">
                <a href="<?php echo e(route('distribusi.activity-report.export.pdf', request()->all())); ?>" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
                <a href="<?php echo e(route('distribusi.activity-report.export.word', request()->all())); ?>" class="btn btn-primary"><i class="bi bi-file-earmark-word"></i> Export Word</a>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis / Merk</th>
                    <th>Jumlah</th>
                    <th>Foto</th>
                    <th>Tanggal Distribusi</th>
                    <!-- <th>Tanggal Kembali</th> -->
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($logs->firstItem() + $i); ?></td>
                        <td><?php echo e($log->user->name ?? '-'); ?></td>
                        <td><?php echo e($log->distribusi && $log->distribusi->barang ? $log->distribusi->barang->nama_barang : '-'); ?></td>
                        <td><?php echo e($log->distribusi ? $log->distribusi->jumlah : '-'); ?></td>
                        <td>
                            <?php if($log->distribusi && $log->distribusi->foto): ?>
                                <a href="<?php echo e(url('storage/' . $log->distribusi->foto)); ?>" target="_blank" class="btn btn-info btn-sm"><i class="bi bi-camera"></i> Lihat</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($log->distribusi && $log->distribusi->tanggal_kirim): ?>
                                <?php echo e(\Carbon\Carbon::parse($log->distribusi->tanggal_kirim)->format('Y-m-d H:i:s')); ?>

                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <!-- <td><?php echo e($log->distribusi ? $log->distribusi->tanggal_kembali : '-'); ?></td> -->
                        <td>
                            <span class="badge 
                                <?php if($log->status_baru=='diterima'): ?> bg-success
                                <?php elseif($log->status_baru=='dikirim'): ?> bg-primary
                                <?php else: ?> bg-secondary <?php endif; ?>">
                                <?php echo e(ucfirst($log->status_baru)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($log->distribusi): ?>
                                <?php if(auth()->user() && auth()->user()->hasRole('super_admin')): ?>
                                    <form action="<?php echo e(route('distribusi.uploadFoto', $log->distribusi->id)); ?>" method="POST" enctype="multipart/form-data" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <label for="foto-<?php echo e($log->distribusi->id); ?>" class="btn btn-warning btn-sm" title="Upload Foto"><i class="bi bi-camera"></i></label>
                                        <input type="file" name="foto" id="foto-<?php echo e($log->distribusi->id); ?>" accept="image/*" style="display:none;" onchange="this.form.submit()">
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <?php echo e($logs->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/distribusi/activity-report.blade.php ENDPATH**/ ?>