

<?php $__env->startSection('title', 'Barang Masuk'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-box-seam"></i> Data Barang Masuk</h1>
    <div class="d-flex align-items-center gap-2">
        <form method="GET" action="<?php echo e(route('barang-masuk.index')); ?>" class="d-flex align-items-center" style="gap:8px;">
            <input type="search" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari nomor / nama / kategori" style="width:240px;">
            <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
            <?php if(request()->filled('q')): ?>
                <a href="<?php echo e(route('barang-masuk.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </form>

        <a href="<?php echo e(route('barang-masuk.create')); ?>" class="btn btn-primary btn-custom">
            <i class="bi bi-plus-circle"></i> Tambah Barang
        </a>
        <a href="<?php echo e(route('barang-masuk.exportPdf')); ?>" class="btn btn-danger btn-custom">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="<?php echo e(route('barang-masuk.importForm')); ?>" class="btn btn-secondary btn-custom">
            <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead style="background-color: #39506a; color: #fff;">
                <thead class="table-dark">
                        <tr style="background-color: #39506a; color: #fff;">
                            <th style="background-color: #39506a; color: #fff;">#</th>
                            
                            <th style="background-color: #39506a; color: #fff;">Serial Number</th>
                            <th style="background-color: #39506a; color: #fff;">Nama Barang</th>
                            <th style="background-color: #39506a; color: #fff;">Kategori</th>
                            <th style="background-color: #39506a; color: #fff;">Jumlah</th>
                            <th style="background-color: #39506a; color: #fff;">Satuan</th>
                            <th style="background-color: #39506a; color: #fff;">Sisa Stok</th>
                            <th style="background-color: #39506a; color: #fff;">Kepemilikan</th>
                            <th style="background-color: #39506a; color: #fff;">Status</th>
                            <th style="background-color: #39506a; color: #fff;">Posisi</th>
                            <th style="background-color: #39506a; color: #fff;">Tahun Pengadaan</th>
                            <th style="background-color: #39506a; color: #fff;">Keterangan</th>
                            <th style="background-color: #39506a; color: #fff;">Barang Masuk</th>
                            <th style="background-color: #39506a; color: #fff;">Barang Keluar</th>
                            <th style="background-color: #39506a; color: #fff;">Tanggal Masuk</th>
                            <th style="background-color: #39506a; color: #fff;">Dokumen</th>
                            <th style="background-color: #39506a; color: #fff;">Aksi</th>
                        </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $barangMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="align-middle">
                            <td class="text-center"><?php echo e($loop->iteration + ($barangMasuk->currentPage() - 1) * $barangMasuk->perPage()); ?></td>
                            <?php $q = request('q'); ?>
                            
                            <td>
                                <?php if($item->serialNumbers && count($item->serialNumbers)): ?>
                                    <?php $__currentLoopData = $item->serialNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $serial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-dark text-white">D<?php echo e($idx+1); ?>: <?php echo e($serial->serial_number); ?></span><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($item->nama_barang ?? $item->nama_perangkat ?? '-'); ?></td>
                            <td>
                                <?php
                                    $kategoriColors = [
                                        'Router' => 'bg-success',
                                        'Access Point' => 'bg-warning text-dark',
                                        'Modem' => 'bg-purple',
                                        'Switch' => 'bg-primary',
                                        'Kabel' => 'bg-danger',
                                    ];
                                    $badgeColor = $kategoriColors[$item->kategori ?? $item->merk_type ?? ''] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?php echo e($badgeColor); ?>"><?php echo e($item->kategori ?? $item->merk_type ?? '-'); ?></span>
                            </td>
                            <td class="text-center"><?php echo e($item->jumlah ?? $item->qty ?? '-'); ?></td>
                            <td class="text-center"><?php echo e($item->satuan ?? '-'); ?></td>
                            <td class="text-center"><?php echo e($item->sisa_stok ?? '-'); ?></td>
                            <td>
                                <?php if($item->kepemilikan): ?>
                                    <span class="badge bg-info text-dark"><?php echo e($item->kepemilikan); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($item->status): ?>
                                    <span class="badge <?php echo e($item->status == 'TERPASANG' ? 'bg-success' : 'bg-warning text-dark'); ?>"><?php echo e($item->status); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($item->posisi ?? '-'); ?></td>
                            <td class="text-center"><?php echo e($item->tahun_pengadaan ?? '-'); ?></td>
                            <td>
                                <?php if($item->keterangan): ?>
                                    <span title="<?php echo e($item->keterangan); ?>"><?php echo e(\Illuminate\Support\Str::limit($item->keterangan, 20)); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo e($item->barang_masuk ?? '-'); ?></td>
                            <td class="text-center"><?php echo e($item->barang_keluar ?? '-'); ?></td>
                            <td class="text-center"><?php echo e(isset($item->tanggal_masuk) ? (is_object($item->tanggal_masuk) ? $item->tanggal_masuk->format('d-m-Y') : $item->tanggal_masuk) : '-'); ?></td>
                            <td>
                                <a href="<?php echo e(route('barang-masuk.pdfLaporan', $item->id)); ?>" class="btn btn-sm btn-danger ms-1" title="Download PDF Laporan" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo e(route('barang-masuk.show', $item->id)); ?>" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('barang-masuk.edit', $item->id)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?php echo e(route('barang-masuk.destroy', $item->id)); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data barang masuk</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php echo e($barangMasuk->links('pagination::bootstrap-5')); ?>

            </ul>
        </nav>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/barang_masuk/index.blade.php ENDPATH**/ ?>