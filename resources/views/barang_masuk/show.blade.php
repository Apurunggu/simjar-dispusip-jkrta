@extends('layout')

@section('title', 'Detail Barang Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-info-circle"></i> Detail Barang Masuk</h1>
    <div>
        <a href="{{ route('barang-masuk.edit', ['id' => $barangMasuk->id]) }}" class="btn btn-warning btn-custom">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary btn-custom">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Barang</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nomor Barang:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $barangMasuk->nomor_barang }}
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nama Barang:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $barangMasuk->nama_barang }}
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Kategori:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-info">{{ $barangMasuk->kategori }}</span>
                    </div>
                </div>

                <hr>


                <div class="row mb-3">
                    <div class="col-md-4"><strong>Jumlah:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->jumlah }} {{ $barangMasuk->satuan ?? '' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Sisa Stok:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->sisa_stok ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Kepemilikan:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->kepemilikan ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Status:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->status ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Posisi:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->posisi ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Tahun Pengadaan:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->tahun_pengadaan ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Barang Masuk:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->barang_masuk ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Barang Keluar:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->barang_keluar ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Tanggal Masuk:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->tanggal_masuk ? (is_object($barangMasuk->tanggal_masuk) ? $barangMasuk->tanggal_masuk->format('d F Y') : $barangMasuk->tanggal_masuk) : '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Tanggal Keluar:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->tanggal_keluar ? (is_object($barangMasuk->tanggal_keluar) ? $barangMasuk->tanggal_keluar->format('d F Y') : $barangMasuk->tanggal_keluar) : '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Keterangan:</strong></div>
                    <div class="col-md-8">{{ $barangMasuk->keterangan ?? 'Tidak ada keterangan' }}</div>
                </div>
                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tanggal Dibuat:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $barangMasuk->created_at->format('d F Y H:i:s') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Terakhir Diperbarui:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $barangMasuk->updated_at->format('d F Y H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
