@extends('layout')

@section('title', 'Upload Dokumen Barang Pihak ke 2')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Upload Dokumen Barang Pihak ke 2</h4>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('dokumen-barang-pihak2.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nama_laporan" class="form-label">Nama Laporan</label>
                <input type="text" name="nama_laporan" id="nama_laporan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File Dokumen</label>
                <input type="file" name="file" id="file" class="form-control" required accept=".pdf,.doc,.docx">
            </div>
            <div class="mb-3">
                <label for="cabang_id" class="form-label">Cabang</label>
                <select name="cabang_id" id="cabang_id" class="form-control">
                    <option value="">- Pilih Cabang -</option>
                    @foreach($cabangs as $cabang)
                        <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Upload</button>
            <a href="{{ route('dokumen-barang-pihak2.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
