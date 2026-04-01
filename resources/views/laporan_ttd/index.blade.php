@extends('layout')

@section('title', 'Daftar Laporan Pihak ke 1')

@section('content')
<h1 class="mb-4"><i class="bi bi-file-earmark-arrow-down"></i> Daftar Laporan Pihak ke 1</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="mb-3 d-flex justify-content-between align-items-center">
    <form method="GET" action="" class="d-flex align-items-center" style="gap:8px;">
        <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari nama laporan / file dokumen..." style="width:260px;">
        <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
        @if(request()->filled('q'))
            <a href="{{ route('laporan-ttd.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @endif
    </form>
    <a href="{{ route('laporan-ttd.create') }}" class="btn btn-primary btn-custom">
        <i class="bi bi-upload"></i> Upload Laporan Baru
    </a>
</div>
<table class="table table-bordered table-striped">
    <thead>
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
                @php $q = request('q'); @endphp
                <a href="{{ route('laporan-ttd.download', $laporan->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-download"></i>
                    @if($q)
                        {!! preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark>$1</mark>', e($laporan->file)) !!}
                    @else
                        Download
                    @endif
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
<div class="mb-3 d-flex justify-content-end">
    <a href="{{ route('dokumen-barang-pihak2.create') }}" class="btn btn-primary btn-custom">
        <i class="bi bi-upload"></i> Upload Laporan Baru
    </a>
</div>
<hr class="my-4">
<h2 class="mb-3"><i class="bi bi-file-earmark-text"></i> Dokumen Barang Pihak ke 2</h2>
<div class="mb-3 d-flex justify-content-between align-items-center">
    <form method="GET" action="" class="d-flex align-items-center" style="gap:8px;">
        <input type="search" name="q2" value="{{ request('q2') }}" class="form-control form-control-sm" placeholder="Cari nama dokumen / file..." style="width:260px;">
        <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
        @if(request()->filled('q2'))
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @endif
    </form>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Dokumen</th>
            <th>Cabang</th>
            <th>Uploader</th>
            <th>Tanggal Upload</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
        @forelse($laporanPihak2 as $dok)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $dok->nama_laporan }}</td>
            <td>{{ $dok->cabang->nama_cabang ?? '-' }}</td>
            <td>{{ $dok->uploader->name ?? '-' }}</td>
            <td>{{ $dok->created_at->format('d-m-Y H:i') }}</td>
            <td>
                <a href="{{ route('dokumen-barang-pihak2.download', $dok->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-download"></i> Download
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Belum ada dokumen pihak ke 2.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
