@extends('layout')

@section('title', 'Upload Draft Dokumen Distribusi')

@section('content')
<h1 class="mb-4" style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-upload"></i> Upload Draft Dokumen Distribusi</h1>
<div class="card">
    <div class="card-body">
        <form action="{{ route('draft-dokumen-distribusi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="distribusi_id" class="form-label">Pilih Distribusi</label>
                <select name="distribusi_id" id="distribusi_id" class="form-select @error('distribusi_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Distribusi --</option>
                    @foreach($distribusi as $d)
                        <option value="{{ $d->id }}">{{ $d->barang->nama_barang ?? '-' }} ke {{ $d->cabangTujuan->nama_cabang ?? '-' }} ({{ $d->tanggal_kirim ? $d->tanggal_kirim->format('d-m-Y') : '-' }})</option>
                    @endforeach
                </select>
                @error('distribusi_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                
            <div class="mb-3">
                <label for="dokumen_pdf" class="form-label">File PDF yang sudah ditandatangani</label>
                <input type="file" name="dokumen_pdf" id="dokumen_pdf" class="form-control @error('dokumen_pdf') is-invalid @enderror" accept="application/pdf" required>
                @error('dokumen_pdf')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload</button>
            <a href="{{ route('draft-dokumen-distribusi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
