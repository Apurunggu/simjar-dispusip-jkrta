<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Distribusi Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Distribusi Barang</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Dari Cabang</th>
                <th>Ke Cabang</th>
                <th>Jumlah</th>
                <th>Tanggal Kirim</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($distribusi as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->barang->nama_barang ?? '-' }}</td>
                <td>{{ $d->cabangAsal->nama_cabang ?? '-' }}</td>
                <td>{{ $d->cabangTujuan->nama_cabang ?? '-' }}</td>
                <td>{{ $d->jumlah }}</td>
                <td>{{ $d->tanggal_kirim->format('d-m-Y') }}</td>
                <td>{{ ucfirst($d->status) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
