@extends('layout')

@section('title', 'Tambah Barang Masuk')

@section('content')
<h1 class="mb-4"><i class="bi bi-plus-circle"></i> Tambah Barang Masuk</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('barang-masuk.store') }}" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="dokumen" class="form-label">Dokumen Barang (PDF/Word)</label>
                                            <input type="file" class="form-control @error('dokumen') is-invalid @enderror" id="dokumen" name="dokumen" accept=".pdf,.doc,.docx">
                                            @error('dokumen')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                    @csrf

                    <div class="mb-3">
                        <label for="nomor_barang" class="form-label">Nomor Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomor_barang') is-invalid @enderror" 
                               id="nomor_barang" name="nomor_barang" value="{{ old('nomor_barang') }}" required>
                        @error('nomor_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="serial_numbers[]" class="form-label">Serial Number</label>
                        <input type="text" class="form-control @error('serial_numbers.0') is-invalid @enderror" name="serial_numbers[]" value="{{ old('serial_numbers.0') }}" placeholder="Contoh: D1">
                        @error('serial_numbers.0')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Isi serial number barang jika ada. Tambahkan lebih dari satu dengan menambah input.</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                               id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" 
                                id="kategori" name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Router" @if(old('kategori') == 'Router') selected @endif>Router</option>
                            <option value="Switch" @if(old('kategori') == 'Switch') selected @endif>Switch</option>
                            <option value="Modem" @if(old('kategori') == 'Modem') selected @endif>Modem</option>
                            <option value="Access Point" @if(old('kategori') == 'Access Point') selected @endif>Access Point</option>
                            <option value="Kabel" @if(old('kategori') == 'Kabel') selected @endif>Kabel</option>
                            <option value="Connector" @if(old('kategori') == 'Connector') selected @endif>Connector</option>
                            <option value="Lainnya" @if(old('kategori') == 'Lainnya') selected @endif>Lainnya</option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                   id="jumlah" name="jumlah" value="{{ old('jumlah') }}" min="1" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                   id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="4">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(!empty($isSuper) && $isSuper)
                        <div class="mb-3">
                            <label for="cabang_id" class="form-label">Cabang</label>
                            <select name="cabang_id" id="cabang_id" class="form-select">
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($cabangs as $c)
                                    <option value="{{ $c->id }}" @if(old('cabang_id') == $c->id) selected @endif>{{ $c->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-custom">
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                        <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary btn-custom">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
