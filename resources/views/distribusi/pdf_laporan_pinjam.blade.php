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
            <img src="{{ public_path('images/jakarta.jpg') }}" width="80" height="80">
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
        NOMOR : {{ $nomor }}<br>
    </div>
    <div class="mt-20">
        Pada hari ini, {{ $hari }}, tanggal {{ $tanggal }}, bulan {{ $bulan }}, tahun {{ $tahun }}, yang bertanda tangan di bawah ini:
        <br><br>
        1. Nama : {{ $pihak_pertama['nama'] }}<br>
        &nbsp;&nbsp;NIP : {{ $pihak_pertama['nip'] }}<br>
        &nbsp;&nbsp;Pangkat : {{ $pihak_pertama['pangkat'] }}<br>
        &nbsp;&nbsp;Jabatan : {{ $pihak_pertama['jabatan'] }}<br>
        Disebut sebagai <span class="bold">PIHAK PERTAMA</span><br><br>
        2. Nama : {{ $pihak_kedua['nama'] }}<br>
        &nbsp;&nbsp;NIP : {{ $pihak_kedua['nip'] }}<br>
        &nbsp;&nbsp;Jabatan : {{ $pihak_kedua['jabatan'] }}<br>
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
        @foreach($barang as $index => $item)
        <tr>
            <td>{{ $index+1 }}</td>
            <td>{{ $item['nama'] }}</td>
            <td>{{ $item['jumlah'] }}</td>
            <td>{{ $item['keterangan'] }}</td>
        </tr>
        @endforeach
    </table>
    <div class="mt-20">
        Demikian berita acara serah terima pinjam pakai barang ini dibuat untuk dipergunakan sebagaimana mestinya.
    </div>
    <table class="signature">
        <tr>
            <td>PIHAK PERTAMA</td>
            <td>PIHAK KEDUA</td>
            <td>Mengetahui</td>
        </tr>
        <tr>
            <td style="height: 60px;"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>{{ $pihak_pertama['nama'] }}<br>NIP: {{ $pihak_pertama['nip'] }}</td>
            <td>{{ $pihak_kedua['nama'] }}<br>NIP: {{ $pihak_kedua['nip'] }}</td>
            <td>{{ $mengetahui['nama'] }}<br>NIP: {{ $mengetahui['nip'] }}</td>
        </tr>
    </table>
</body>
</html>
