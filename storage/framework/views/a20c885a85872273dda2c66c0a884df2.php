<div style="text-align:center;">
    <span style="font-size:16px;font-weight:bold;">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</span><br>
    <span style="font-size:16px;font-weight:bold;">DINAS PERPUSTAKAAN DAN KEARSIPAN</span><br>
    Jalan Perintis Kemerdekaan No. 1 Pulogadung Jakarta Timur<br>
    Telp. 021.47860095 Fax. 021 47865922 Website dispusip.jakarta.go.id<br>
    <span style="font-weight:bold;">JAKARTA</span> &nbsp;&nbsp; Kode Pos 13260
</div>
<h2 style="text-align:center;">BERITA ACARA<br>SERAH TERIMA PINJAM PAKAI BARANG</h2>
<p style="text-align:center;">NOMOR : <?php echo e($nomor); ?></p>
<p>Pada hari ini, <?php echo e($hari); ?>, tanggal <?php echo e($tanggal); ?>, bulan <?php echo e($bulan); ?>, tahun <?php echo e($tahun); ?>, yang bertanda tangan di bawah ini:</p>
<ol style="margin-left:0;padding-left:20px;">
    <li>
        Nama : <?php echo e($pihak_pertama['nama']); ?><br>
        NIP : <?php echo e($pihak_pertama['nip']); ?><br>
        Pangkat : <?php echo e($pihak_pertama['pangkat'] ?? '-'); ?><br>
        Jabatan : <?php echo e($pihak_pertama['jabatan'] ?? '-'); ?><br>
        Disebut sebagai <strong>PIHAK PERTAMA</strong>
    </li>
    <li>
        Nama : <?php echo e($pihak_kedua['nama']); ?><br>
        NIP : <?php echo e($pihak_kedua['nip']); ?><br>
        Jabatan : <?php echo e($pihak_kedua['jabatan']); ?><br>
        Disebut sebagai <strong>PIHAK KEDUA</strong>
    </li>
</ol>
<p><strong>PIHAK PERTAMA</strong> menyerahkan barang kepada <strong>PIHAK KEDUA</strong> untuk kegiatan di Wilayah Sudin Pusip Jakarta Timur dan <strong>PIHAK KEDUA</strong> menyatakan telah menerima barang pinjam pakai dari <strong>PIHAK PERTAMA</strong> berupa daftar terlampir :</p>
<table border="1" cellpadding="4" cellspacing="0" width="100%" style="border-collapse:collapse;">
    <tr style="background:#f2f2f2;">
        <th style="width:5%;">No</th>
        <th>Nama Barang</th>
        <th style="width:10%;">Jumlah</th>
        <th style="width:20%;">Keterangan</th>
    </tr>
    <?php $__currentLoopData = $barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td style="text-align:center;"><?php echo e($index+1); ?></td>
        <td><?php echo e($item['nama']); ?></td>
        <td style="text-align:center;"><?php echo e($item['jumlah']); ?></td>
        <td style="text-align:center;"><?php echo e($item['keterangan']); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<p>Demikian berita acara serah terima barang ini dibuat oleh kedua belah pihak, adapun barang tersebut dalam keadaan baik sejak penandatanganan berita acara ini, maka barang tersebut, menjadi tanggung jawab <strong>PIHAK KEDUA</strong>.</p>
<br>
<table width="100%" style="margin-top:20px;">
    <tr>
        <td style="text-align:center;width:50%;">
            Yang menyerahkan :<br>
            <strong>PIHAK PERTAMA</strong><br><br><br><br>
            <?php echo e($pihak_pertama['nama']); ?><br>
            NIP. <?php echo e($pihak_pertama['nip']); ?><br>
        </td>
        <td style="text-align:center;width:50%;">
            Yang menerima :<br>
            <strong>PIHAK KEDUA</strong><br>
            <?php echo e($pihak_kedua['jabatan']); ?><br><br><br><br>
            <?php echo e($pihak_kedua['nama']); ?><br>
            NIP. <?php echo e($pihak_kedua['nip']); ?>

        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center;padding-top:30px;">
            Mengetahui/Menyetujui<br>
            Kepala Bidang Teknologi Informasi<br>
            Dinas Perpustakaan dan Kearsipan<br><br><br><br>
            <?php echo e($mengetahui['nama']); ?><br>
            NIP. <?php echo e($mengetahui['nip']); ?>

        </td>
    </tr>
</table>
<?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/distribusi/word_laporan.blade.php ENDPATH**/ ?>