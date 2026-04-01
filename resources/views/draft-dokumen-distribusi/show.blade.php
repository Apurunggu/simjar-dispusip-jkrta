@extends('layout')

@section('title', 'Detail Draft Dokumen Distribusi')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class="bi bi-eye"></i> Detail Draft Dokumen Distribusi</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Kode Barang</th>
                <td>{{ $draft->barang->nomor_barang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <td>{{ $draft->barang->nama_barang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Cabang Tujuan</th>
                <td>{{ $draft->cabangTujuan->nama_cabang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal Distribusi</th>
                <td>{{ $draft->tanggal_kirim ? $draft->tanggal_kirim->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Jam Distribusi</th>
                <td>{{ $draft->tanggal_kirim ? $draft->tanggal_kirim->format('H:i') : '-' }}</td>
            </tr>
            <tr>
                <th>Dokumen PDF</th>
                <td>
                    @if($draft->dokumen_pdf)
                        <a href="{{ route('draft-dokumen-distribusi.download', $draft->id) }}" class="btn btn-success btn-sm" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Download
                        </a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
        <a href="{{ route('draft-dokumen-distribusi.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection
