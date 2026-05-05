@extends('layout')

@section('title', 'Draft Dokumen Distribusi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-file-earmark-pdf"></i> Draft Dokumen Distribusi</h1>
    <a href="{{ route('draft-dokumen-distribusi.create') }}" class="btn btn-primary">
        <i class="bi bi-upload"></i> Upload Draft Baru
    </a>
</div>
<div class="mb-3">
    <form method="GET" action="" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari barang, kode, cabang..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Serial Number</th>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th>Cabang Tujuan</th>
                        <th>Tanggal Distribusi</th>
                        <th>Jam</th>
                        <th>Dokumen PDF</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drafts as $i => $draft)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                @if($draft->barang && $draft->barang->serialNumbers && $draft->barang->serialNumbers->count())
                                    @foreach($draft->barang->serialNumbers as $sn)
                                        <span class="badge bg-dark text-white">{{ $sn->serial_number }}</span><br>
                                    @endforeach
                                @elseif($draft->barang && $draft->barang->nomor_barang)
                                    <span class="badge bg-dark text-white">{{ $draft->barang->nomor_barang }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $draft->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $draft->barang->kategori ?? '-' }}</td>
                            <td>{{ $draft->cabangTujuan->nama_cabang ?? '-' }}</td>
                            <td>{{ $draft->tanggal_kirim ? $draft->tanggal_kirim->format('d-m-Y') : '-' }}</td>
                            <td>{{ $draft->created_at ? $draft->created_at->format('H:i') : '-' }}</td>
                            <td>
                                @if($draft->dokumen_pdf)
                                    <a href="{{ route('draft-dokumen-distribusi.download', $draft->id) }}" class="btn btn-success btn-sm" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i> Download
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="d-flex gap-1">
                                <a href="{{ route('draft-dokumen-distribusi.show', $draft->id) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> Detail</a>
                                @canany(['super_admin', 'admin_cabang'])
                                    <form action="{{ route('draft-dokumen-distribusi.destroy', $draft->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endcanany
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada draft dokumen distribusi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
