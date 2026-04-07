@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Cabang</h1>
    <form action="{{ route('cabang.update', $cabang->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_cabang" class="form-label">Nama Cabang</label>
            <input type="text" class="form-control @error('nama_cabang') is-invalid @enderror" id="nama_cabang" name="nama_cabang" value="{{ old('nama_cabang', $cabang->nama_cabang) }}" required>
            @error('nama_cabang')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat', $cabang->alamat) }}">
        </div>
        <div class="mb-3">
            <label for="kota" class="form-label">Kota</label>
            <input type="text" class="form-control" id="kota" name="kota" value="{{ old('kota', $cabang->kota) }}">
        </div>
        <div class="mb-3">
            <label for="provinsi" class="form-label">Provinsi</label>
            <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ old('provinsi', $cabang->provinsi) }}">
        </div>
        <div class="mb-3">
            <label for="kode_cabang" class="form-label">Kode Cabang</label>
            <input type="text" class="form-control" id="kode_cabang" name="kode_cabang" value="{{ old('kode_cabang', $cabang->kode_cabang) }}">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('cabang.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
