<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Serah Terima Pinjam Pakai Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 10px; }
        .logo { float: left; width: 80px; }
        .kop { text-align: center; }
        .line { border-top: 2px solid #000; margin: 10px 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 4px; text-align: left; }
        .signature { margin-top: 40px; width: 100%; }
        .signature td { text-align: center; vertical-align: top; }
        .bold { font-weight: bold; }
        .mt-20 { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="<?php echo e(public_path('images/jakarta.jpg')); ?>" width="80" height="80">
        </div>
        <div class="kop">
            <span class="bold">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</span><br>
            <span class="bold">DINAS PERPUSTAKAAN DAN KEARSIPAN</span><br>
            Jalan Perintis Kemerdekaan No. 1 Pulogadung Jakarta Timur<br>
            Telp. 021.47860095 Fax. 021 47865922 Website dispusip.jakarta.go.id<br>
            <span class="bold">JAKARTA</span> &nbsp;&nbsp;&nbsp;&nbsp; Kode Pos 13260
        </div>
    </div>
    <div class="line"></div>
    <div style="text-align:center;">
        <span class="bold">BERITA ACARA<br>SERAH TERIMA PINJAM PAKAI BARANG</span><br>
        NOMOR : <?php echo e($nomor); ?><br>
    </div>
    <div class="mt-20">
        Pada hari ini, <?php echo e($hari); ?>, tanggal <?php echo e($tanggal); ?>, bulan <?php echo e($bulan); ?>, tahun <?php echo e($tahun); ?>, yang bertanda tangan di bawah ini:
        <br><br>
        1. Nama : <?php echo e($pihak_pertama['nama']); ?><br>
        &nbsp;&nbsp;NIP : <?php echo e($pihak_pertama['nip']); ?><br>
        &nbsp;&nbsp;Pangkat : <?php echo e($pihak_pertama['pangkat']); ?><br>
        &nbsp;&nbsp;Jabatan : <?php echo e($pihak_pertama['jabatan']); ?><br>
        Disebut sebagai <span class="bold">PIHAK PERTAMA</span><br><br>
        2. Nama : <?php echo e($pihak_kedua['nama']); ?><br>
        &nbsp;&nbsp;NIP : <?php echo e($pihak_kedua['nip']); ?><br>
        &nbsp;&nbsp;Jabatan : <?php echo e($pihak_kedua['jabatan']); ?><br>
        Disebut sebagai <span class="bold">PIHAK KEDUA</span><br><br>
        <span class="bold">PIHAK PERTAMA</span> menyerahkan barang kepada <span class="bold">PIHAK KEDUA</span> untuk kegiatan di Wilayah Sudin Pusip Jakarta Timur dan <span class="bold">PIHAK KEDUA</span> menyatakan telah menerima barang pinjam pakai dari <span class="bold">PIHAK PERTAMA</span> berupa daftar terlampir :
    </div>
    <table class="table">
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>
        <?php $__currentLoopData = $barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($index+1); ?></td>
            <td><?php echo e($item['nama']); ?></td>
            <td><?php echo e($item['jumlah']); ?></td>
            <td><?php echo e($item['keterangan']); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
    <div class="mt-20">
        Demikian berita acara serah terima barang ini dibuat oleh kedua belah pihak, adapun barang tersebut dalam keadaan baik sejak penandatanganan berita acara ini, maka barang tersebut, menjadi tanggung jawab <span class="bold">PIHAK KEDUA</span>.
    </div>
    <table class="signature mt-20">
        <tr>
            <td>
                Yang menyerahkan :<br>
                <span class="bold">PIHAK PERTAMA</span><br>
                <?php echo e($pihak_pertama['jabatan']); ?><br><br><br><br><br>
                <?php echo e($pihak_pertama['nama']); ?><br>
                NIP. <?php echo e($pihak_pertama['nip']); ?><br>
            </td>
            <td>
                Yang menerima :<br>
                <span class="bold">PIHAK KEDUA</span><br>
                <?php echo e($pihak_kedua['jabatan']); ?><br><br><br><br><br>
                <?php echo e($pihak_kedua['nama']); ?><br>
                NIP. <?php echo e($pihak_kedua['nip']); ?>

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                Mengetahui/Menyetujui<br>
                Kepala Bidang Teknologi Informasi<br>
                Dinas Perpustakaan dan Kearsipan<br><br><br><br><br><br><br>
                <?php echo e($mengetahui['nama']); ?><br>
                NIP. <?php echo e($mengetahui['nip']); ?>

            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Simjar_dispusip\resources\views/distribusi/pdf_laporan.blade.php ENDPATH**/ ?>