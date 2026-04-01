@extends('layout')

@section('title', 'Edit Barang Masuk')

@section('content')
<h1 class="mb-4"><i class="bi bi-pencil"></i> Edit Barang Masuk</h1>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('barang-masuk.update', $barangMasuk->id) }}" method="POST" enctype="multipart/form-data">
                                                            <div class="mb-3">
                                                                <label for="serial_numbers[]" class="form-label">Serial Number</label>
                                                                @if($barangMasuk->serialNumbers && count($barangMasuk->serialNumbers))
                                                                    @foreach($barangMasuk->serialNumbers as $idx => $serial)
                                                                        <input type="text" class="form-control mb-1" name="serial_numbers[]" value="{{ old('serial_numbers.' . $idx, $serial->serial_number) }}" placeholder="D{{ $idx+1 }}">
                                                                    @endforeach
                                                                @else
                                                                    <input type="text" class="form-control mb-1" name="serial_numbers[]" value="{{ old('serial_numbers.0') }}" placeholder="D1">
                                                                @endif
                                                                <small class="text-muted">Edit atau tambahkan serial number barang. Kosongkan untuk menghapus.</small>
                                                            </div>
                    @csrf
                    @method('PUT')

                    <!-- Nomor Barang dihapus -->

                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                               id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barangMasuk->nama_barang) }}" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                        <div class="mb-3">
                            <label for="dokumen" class="form-label">Dokumen Barang (PDF/Word)</label>
                            <input type="file" class="form-control @error('dokumen') is-invalid @enderror" id="dokumen" name="dokumen" accept=".pdf,.doc,.docx">
                            @if($barangMasuk->dokumen)
                            @endif
                        </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                   id="jumlah" name="jumlah" value="{{ old('jumlah', $barangMasuk->jumlah) }}" min="1" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan', $barangMasuk->satuan) }}">
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="sisa_stok" class="form-label">Sisa Stok</label>
                            <input type="number" class="form-control @error('sisa_stok') is-invalid @enderror" id="sisa_stok" name="sisa_stok" value="{{ old('sisa_stok', $barangMasuk->sisa_stok) }}">
                            @error('sisa_stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="kepemilikan" class="form-label">Kepemilikan</label>
                            <input type="text" class="form-control @error('kepemilikan') is-invalid @enderror" id="kepemilikan" name="kepemilikan" value="{{ old('kepemilikan', $barangMasuk->kepemilikan) }}">
                            @error('kepemilikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status" value="{{ old('status', $barangMasuk->status) }}">
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="posisi" class="form-label">Posisi</label>
                            <input type="text" class="form-control @error('posisi') is-invalid @enderror" id="posisi" name="posisi" value="{{ old('posisi', $barangMasuk->posisi) }}">
                            @error('posisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tahun_pengadaan" class="form-label">Tahun Pengadaan</label>
                            <input type="text" class="form-control @error('tahun_pengadaan') is-invalid @enderror" id="tahun_pengadaan" name="tahun_pengadaan" value="{{ old('tahun_pengadaan', $barangMasuk->tahun_pengadaan) }}">
                            @error('tahun_pengadaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="barang_masuk" class="form-label">Barang Masuk</label>
                            <input type="text" class="form-control @error('barang_masuk') is-invalid @enderror" id="barang_masuk" name="barang_masuk" value="{{ old('barang_masuk', $barangMasuk->barang_masuk) }}">
                            @error('barang_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="barang_keluar" class="form-label">Barang Keluar</label>
                            <input type="text" class="form-control @error('barang_keluar') is-invalid @enderror" id="barang_keluar" name="barang_keluar" value="{{ old('barang_keluar', $barangMasuk->barang_keluar) }}">
                            @error('barang_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                   id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', $barangMasuk->tanggal_masuk ? $barangMasuk->tanggal_masuk->format('Y-m-d') : null) }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                            <input type="date" class="form-control @error('tanggal_keluar') is-invalid @enderror" 
                                   id="tanggal_keluar" name="tanggal_keluar" value="{{ old('tanggal_keluar', $barangMasuk->tanggal_keluar ? (is_object($barangMasuk->tanggal_keluar) ? $barangMasuk->tanggal_keluar->format('Y-m-d') : $barangMasuk->tanggal_keluar) : null) }}">
                            @error('tanggal_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="4">{{ old('keterangan', $barangMasuk->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-custom">
                            <i class="bi bi-check-circle"></i> Perbarui
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
