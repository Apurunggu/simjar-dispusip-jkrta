@extends('layout')

@section('title', 'Buat Distribusi Barang')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-truck"></i> Buat Distribusi Barang</h1>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi Kesalahan!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('distribusi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="barang_id" class="form-label">Barang <span class="text-danger">*</span></label>
                        <select name="barang_id" id="barang_id" class="form-select select2 @error('barang_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangMasuk as $barang)
                                <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('#barang_id').select2({
                                placeholder: '-- Pilih Barang --',
                                allowClear: true
                            });
                        });
                    </script>

                    <div id="info-barang" class="mb-3" style="display:none;">
                        <div class="card card-body bg-light border">
                            <div class="row g-2">
                                <div class="col-md-4"><strong>Satuan:</strong> <span id="info-satuan">-</span></div>
                                <div class="col-md-4"><strong>Stok:</strong> <span id="info-stok">-</span></div>
                                <div class="col-md-4"><strong>Kategori:</strong> <span id="info-kategori">-</span></div>
                                <div class="col-md-4"><strong>Posisi:</strong> <span id="info-posisi">-</span></div>
                                <div class="col-md-4"><strong>Status:</strong> <span id="info-status">-</span></div>
                                <div class="col-md-4"><strong>Tahun Pengadaan:</strong> <span id="info-tahun">-</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cabang_asal_id" class="form-label">Cabang Asal <span class="text-danger">*</span></label>
                        <select name="cabang_asal_id" id="cabang_asal_id" class="form-select @error('cabang_asal_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Cabang Asal --</option>
                            @foreach($cabangAsal as $cabang)
                                <option value="{{ $cabang->id }}" {{ old('cabang_asal_id') == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        @error('cabang_asal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cabang_tujuan_id" class="form-label">Cabang Tujuan <span class="text-danger">*</span></label>
                        <select name="cabang_tujuan_id" id="cabang_tujuan_id" class="form-select @error('cabang_tujuan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Cabang Tujuan --</option>
                            @foreach($cabangTujuan as $cabang)
                                <option value="{{ $cabang->id }}" {{ old('cabang_tujuan_id') == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                        @error('cabang_tujuan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                               min="1" value="{{ old('jumlah') }}" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!--
                    <div class="mb-3">
                        <label for="tanggal_kirim" class="form-label">Tanggal Kirim <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kirim" id="tanggal_kirim" class="form-control @error('tanggal_kirim') is-invalid @enderror" 
                               value="{{ old('tanggal_kirim', date('Y-m-d')) }}" required>
                        @error('tanggal_kirim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kirim</label>
                        <input type="text" class="form-control" value="Otomatis saat submit" disabled>
                        <div class="form-text">Tanggal kirim akan diisi otomatis saat distribusi dibuat.</div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Distribusi
                        </button>
                        <a href="{{ route('distribusi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Informasi</h6>
                <p class="small text-muted mb-2">
                    <strong>Alur Distribusi:</strong>
                </p>
                <ol class="small">
                    <li>Pilih barang dari stok pusat</li>
                    <li>Tentukan cabang tujuan</li>
                    <li>Masukkan jumlah yang didistribusikan</li>
                    <li>Stok pusat otomatis berkurang</li>
                    <li>Admin cabang tujuan menerima barang</li>
                </ol>
                <div class="alert alert-info small mt-3 mb-0">
                    <i class="bi bi-info-circle"></i> Stok barang akan otomatis berkurang setelah distribusi dibuat
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barang_id');
    const infoBox = document.getElementById('info-barang');
    const satuan = document.getElementById('info-satuan');
    const stok = document.getElementById('info-stok');
    const kategori = document.getElementById('info-kategori');
    const posisi = document.getElementById('info-posisi');
    const status = document.getElementById('info-status');
    const tahun = document.getElementById('info-tahun');

    barangSelect.addEventListener('change', function() {
        const id = this.value;
        if (!id) {
            infoBox.style.display = 'none';
            satuan.textContent = '-';
            stok.textContent = '-';
            kategori.textContent = '-';
            posisi.textContent = '-';
            status.textContent = '-';
            tahun.textContent = '-';
            return;
        }
        fetch(`/distribusi-barang/info/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    infoBox.style.display = 'none';
                } else {
                    satuan.textContent = data.satuan ?? '-';
                    stok.textContent = data.stok ?? '-';
                    kategori.textContent = data.kategori ?? '-';
                    posisi.textContent = data.posisi ?? '-';
                    status.textContent = data.status ?? '-';
                    tahun.textContent = data.tahun_pengadaan ?? '-';
                    infoBox.style.display = '';
                }
            });
    });
});
</script>
@endpush
@endsection
