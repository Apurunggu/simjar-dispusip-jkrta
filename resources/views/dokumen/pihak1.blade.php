@extends('layout')

@section('title', 'Dokumen Barang Pihak ke 1')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="card-title"><i class="bi bi-file-earmark-arrow-down"></i> Dokumen Barang Pihak ke 1</h2>
            <a href="{{ route('laporan-ttd.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-upload"></i> Upload Laporan Baru
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="GET" class="mb-3 d-flex gap-2">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama laporan / file dokumen...">
            <button type="submit" class="btn btn-outline-primary">Search</button>
            @if(request()->filled('q'))
                <a href="{{ route('laporan-ttd.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Laporan</th>
                        <th>Cabang</th>
                        <th>Uploader</th>
                        <th>Tanggal Upload</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $laporan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @php $q = request('q'); @endphp
                            @if($q)
                                {!! preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark>$1</mark>', e($laporan->nama_laporan)) !!}
                            @else
                                {{ $laporan->nama_laporan }}
                            @endif
                        </td>
                        <td>{{ $laporan->cabang->nama_cabang ?? '-' }}</td>
                        <td>{{ $laporan->uploader->name ?? '-' }}</td>
                        <td>{{ $laporan->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <a href="{{ route('laporan-ttd.download', $laporan->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada laporan diunggah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
