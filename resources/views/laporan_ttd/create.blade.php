@extends('layout')

@section('title', 'Upload Laporan TTD')

@section('content')
<h1 class="mb-4"><i class="bi bi-upload"></i> Upload Laporan TTD</h1>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laporan-ttd.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_laporan" class="form-label">Nama Laporan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_laporan') is-invalid @enderror" id="nama_laporan" name="nama_laporan" value="{{ old('nama_laporan') }}" required>
                        @error('nama_laporan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File Laporan (PDF/Word) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf,.doc,.docx" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if(auth()->user()->hasRole('super_admin'))
                    <div class="mb-3">
                        <label for="cabang_id" class="form-label">Cabang</label>
                        <select name="cabang_id" id="cabang_id" class="form-select">
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($cabangs as $c)
                                <option value="{{ $c->id }}">{{ $c->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-custom">
                            <i class="bi bi-check-circle"></i> Upload
                        </button>
                        <a href="{{ route('laporan-ttd.index') }}" class="btn btn-secondary btn-custom">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
