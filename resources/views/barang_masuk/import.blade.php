@extends('layout')

@section('title', 'Import Barang Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-file-earmark-arrow-up"></i> Import Barang Masuk</h1>
    <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary btn-custom">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('barang-masuk.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Pilih file Excel (xlsx, xls, csv)</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Format kolom: nomor_barang, nama_barang, kategori, jumlah, tanggal_masuk, keterangan (header optional)</div>
            </div>

            <button type="submit" class="btn btn-primary btn-custom">
                <i class="bi bi-upload"></i> Import
            </button>
        </form>
    </div>
</div>
@endsection
