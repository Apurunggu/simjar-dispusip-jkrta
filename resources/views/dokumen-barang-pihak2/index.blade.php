
@extends('layout')

@section('title', 'Dokumen Barang Pihak ke 2')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center" style="gap:16px;">
    <div style="display: flex; align-items: center;">
        <h1 class="mb-0" style="font-size:2rem; line-height:1; display:inline-block; vertical-align:middle;"><i class="bi bi-file-earmark-text"></i> Dokumen Barang Pihak ke 2</h1>
    </div>
    <a href="{{ route('dokumen-barang-pihak2.create') }}" class="btn btn-primary btn-custom" style="height:40px; display:flex; align-items:center;">
        <i class="bi bi-upload"></i> Upload Laporan Baru
    </a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="mb-3 d-flex justify-content-between align-items-center">
    <form method="GET" action="" class="d-flex align-items-center" style="gap:8px;">
        <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari nama laporan / file dokumen..." style="width:260px;">
        <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
        @if(request()->filled('q'))
            <a href="{{ route('dokumen-barang-pihak2.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @endif
    </form>
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
                <a href="{{ route('dokumen-barang-pihak2.download', $laporan->id) }}" class="btn btn-sm btn-outline-primary">
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
@endsection
