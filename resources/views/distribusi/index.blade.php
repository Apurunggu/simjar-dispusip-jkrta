@extends('layout')

@section('title', 'Distribusi Barang')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-truck"></i> Distribusi Barang</h1>
        <div class="mb-3">
            <a href="{{ route('distribusi.exportPdf', ['type' => 'serah-terima']) }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF Serah Terima
            </a>
            <a href="{{ route('distribusi.exportPdf', ['type' => 'pinjam']) }}" class="btn btn-warning">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF Pinjam
            </a>
            <a href="{{ route('distribusi.exportWord') }}" class="btn btn-primary">
                <i class="bi bi-file-earmark-word"></i> Export Word
            </a>
        </div>
        
        @if(auth()->user()->hasAnyRole(['super_admin', 'admin_cabang', 'staff']))
            <a href="{{ route('distribusi.create') }}" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Buat Distribusi Baru
            </a>
        @endif

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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-table"></i> Daftar Distribusi</h5>
            </div>
            <div class="card-body">
                @if($distribusi->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Dari Cabang</th>
                                    <th>Ke Cabang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Status</th>
                                    <th>Terpasang?</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($distribusi as $d)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->barang->nama_barang ?? '-' }}</td>
                                        <td>{{ $d->cabangAsal->nama_cabang ?? '-' }}</td>
                                        <td>{{ $d->cabangTujuan->nama_cabang ?? '-' }}</td>
                                        <td><strong>{{ $d->jumlah }}</strong></td>
                                        <td>{{ $d->tanggal_kirim->format('d-m-Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $d->getStatusBadgeAttribute() }}">
                                                {{ ucfirst($d->status) }}
                                            </span>
                                        </td>
                                        <td>
                                                <form action="{{ route('distribusi.distribusi.updateStatus', $d) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                <div class="btn-group" role="group">
                                                    <button type="submit" name="is_terpasang" value="terpasang" class="btn btn-sm {{ $d->is_terpasang == 'terpasang' ? 'btn-success active' : 'btn-outline-success' }}">Terpasang</button>
                                                    <button type="submit" name="is_terpasang" value="tidak_terpasang" class="btn btn-sm {{ $d->is_terpasang == 'tidak_terpasang' ? 'btn-danger active' : 'btn-outline-danger' }}">Tidak Terpasang</button>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('distribusi.show', $d) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if($d->status === 'pending' && auth()->user()->hasAnyRole(['super_admin', 'admin_cabang', 'staff']))
                                                <form action="{{ route('distribusi.destroy', $d) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus distribusi ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Belum ada distribusi barang
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                        <!-- Tabel Kode Barang -->
                    {{ $distribusi->links() }}
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada data distribusi barang</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
