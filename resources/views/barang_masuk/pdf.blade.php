
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Masuk</title>
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
        <span class="bold">LAPORAN DATA BARANG MASUK</span><br>
        Tanggal: {{ date('d F Y') }}<br>
    </div>
    <div class="mt-20"></div>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Tanggal Masuk</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangMasuk as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nomor_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->tanggal_masuk->format('d-m-Y') }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="signature">
        <table style="width:100%; margin-top:40px;">
            <tr>
                <td style="width:50%; text-align:center;">Mengetahui,<br>Kepala Bidang Teknologi Informasi<br><br><br><br><u>koihuk</u><br>NIP. 98789</td>
                <td style="width:50%; text-align:center;">Petugas,<br><br><br><br><u>{{ auth()->user()->name ?? '-' }}</u><br>NIP. {{ auth()->user()->nip ?? '-' }}</td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 20px; text-align: right;">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
