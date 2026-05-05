@extends('layout')

@section('title', 'Tambah Perangkat Jaringan')

@section('content')
<h1 class="mb-4" style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-plus-circle"></i> Tambah Perangkat Jaringan</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('perangkat-jaringan.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nomor_inventaris" class="form-label">Nomor Inventaris <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomor_inventaris') is-invalid @enderror" 
                               id="nomor_inventaris" name="nomor_inventaris" value="{{ old('nomor_inventaris') }}" required 
                               placeholder="Contoh: INV-NET-001">
                        @error('nomor_inventaris')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_perangkat" class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_perangkat') is-invalid @enderror" 
                               id="nama_perangkat" name="nama_perangkat" value="{{ old('nama_perangkat') }}" required>
                        @error('nama_perangkat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tipe_perangkat" class="form-label">Tipe Perangkat <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipe_perangkat') is-invalid @enderror" 
                                id="tipe_perangkat" name="tipe_perangkat" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Router" @if(old('tipe_perangkat') == 'Router') selected @endif>Router</option>
                            <option value="Switch" @if(old('tipe_perangkat') == 'Switch') selected @endif>Switch</option>
                            <option value="Modem" @if(old('tipe_perangkat') == 'Modem') selected @endif>Modem</option>
                            <option value="Access Point" @if(old('tipe_perangkat') == 'Access Point') selected @endif>Access Point</option>
                            <option value="Firewall" @if(old('tipe_perangkat') == 'Firewall') selected @endif>Firewall</option>
                            <option value="Server" @if(old('tipe_perangkat') == 'Server') selected @endif>Server</option>
                            <option value="Lainnya" @if(old('tipe_perangkat') == 'Lainnya') selected @endif>Lainnya</option>
                        </select>
                        @error('tipe_perangkat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                               id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required 
                               placeholder="Contoh: Ruang Server, Lantai 2">
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ip_address" class="form-label">IP Address</label>
                            <input type="text" class="form-control @error('ip_address') is-invalid @enderror" 
                                   id="ip_address" name="ip_address" value="{{ old('ip_address') }}" 
                                   placeholder="192.168.1.1">
                            @error('ip_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mac_address" class="form-label">MAC Address</label>
                            <input type="text" class="form-control @error('mac_address') is-invalid @enderror" 
                                   id="mac_address" name="mac_address" value="{{ old('mac_address') }}" 
                                   placeholder="00:1A:2B:3C:4D:5E">
                            @error('mac_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pemasangan" class="form-label">Tanggal Pemasangan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_pemasangan') is-invalid @enderror" 
                               id="tanggal_pemasangan" name="tanggal_pemasangan" value="{{ old('tanggal_pemasangan') }}" required>
                        @error('tanggal_pemasangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="4">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-custom">
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                        <a href="{{ route('perangkat-jaringan.index') }}" class="btn btn-secondary btn-custom">
                            <i class="bi bi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
