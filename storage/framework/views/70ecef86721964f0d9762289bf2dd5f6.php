

<?php $__env->startSection('title', 'Barang Masuk'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h1 class="mb-0" style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-box-seam"></i> Data Barang Masuk</h1>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <form method="GET" action="<?php echo e(route('barang-masuk.index')); ?>" class="d-flex align-items-center gap-2 mb-0">
            <input type="search" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari nomor / nama / kategori" style="width:220px;">
            <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
            <?php if(request()->filled('q')): ?>
                <a href="<?php echo e(route('barang-masuk.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </form>
        <a href="<?php echo e(route('barang-masuk.create')); ?>" class="btn btn-sm btn-success">
            <i class="bi bi-plus-circle"></i> Tambah Barang
        </a>
        <a href="<?php echo e(route('barang-masuk.exportPdf')); ?>" class="btn btn-sm btn-danger">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="<?php echo e(route('barang-masuk.importForm')); ?>" class="btn btn-sm btn-secondary">
            <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark sticky-top">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Serial Number</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Sisa Stok</th>
                        <th>Kepemilikan</th>
                        <th>Status</th>
                        <th>Posisi</th>
                        <th class="text-center">Tahun Pengadaan</th>
                        <th>Keterangan</th>
                        <th class="text-center">Barang Masuk</th>
                        <th class="text-center">Barang Keluar</th>
                        <th class="text-center">Tanggal Masuk</th>
                        <th>Dokumen</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $barangMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center"><?php echo e($loop->iteration + ($barangMasuk->currentPage() - 1) * $barangMasuk->perPage()); ?></td>
                            <?php $q = request('q'); ?>
                            <td>
                                <?php if($item->serialNumbers && count($item->serialNumbers)): ?>
                                    <?php $__currentLoopData = $item->serialNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $serial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-dark text-white mb-1">D<?php echo e($idx+1); ?>: <?php echo e($serial->serial_number); ?></span><br>
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
                                <a href="<?php echo e(route('barang-masuk.pdfLaporan', $item->id)); ?>" class="btn btn-sm btn-outline-danger mb-1" title="Download PDF Laporan" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo e(route('barang-masuk.show', $item->id)); ?>" class="btn btn-sm btn-info mb-1" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo e(route('barang-masuk.edit', $item->id)); ?>" class="btn btn-sm btn-warning mb-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?php echo e(route('barang-masuk.destroy', $item->id)); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="17" class="text-center text-muted">Tidak ada data barang masuk</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (isset($component)) { $__componentOriginald2c13c0488e53309ee86563089ed1a17 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald2c13c0488e53309ee86563089ed1a17 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.custom-pagination','data' => ['paginator' => $barangMasuk]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('custom-pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($barangMasuk)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald2c13c0488e53309ee86563089ed1a17)): ?>
<?php $attributes = $__attributesOriginald2c13c0488e53309ee86563089ed1a17; ?>
<?php unset($__attributesOriginald2c13c0488e53309ee86563089ed1a17); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald2c13c0488e53309ee86563089ed1a17)): ?>
<?php $component = $__componentOriginald2c13c0488e53309ee86563089ed1a17; ?>
<?php unset($__componentOriginald2c13c0488e53309ee86563089ed1a17); ?>
<?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Custom Pagination Style */
    .pagination {
        gap: 4px;
    }
    .pagination .page-item {
        margin: 0;
    }
    .pagination .page-link {
        padding: 0.4rem 0.75rem;
        font-size: 1rem;
        border-radius: 6px;
        min-width: 36px;
        text-align: center;
    }
    .pagination .page-item.active .page-link {
        background-color: #39506a;
        border-color: #39506a;
        color: #fff;
    }
    .pagination .page-link:focus {
        box-shadow: 0 0 0 0.15rem #39506a33;
    }
    .table-hover tbody tr:hover {
        background-color: #f2f6fa;
    }
    .badge {
        font-size: 0.95em;
        padding: 0.45em 0.7em;
        border-radius: 0.5em;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/barang_masuk/index.blade.php ENDPATH**/ ?>