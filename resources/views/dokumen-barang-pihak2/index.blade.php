
@extends('layout')

@section('title', 'Dokumen Barang Pihak ke 2')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center" style="gap:16px;">
    <div style="display: flex; align-items: center;">
        <h1 class="mb-0" style="font-size:2rem; line-height:1; display:inline-block; vertical-align:middle; color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);\"><i class="bi bi-file-earmark-text"></i> Dokumen Barang Pihak ke 2</h1>
    </div>
    <a href="{{ route('dokumen-barang-pihak2.create') }}" class="btn btn-primary btn-custom" style="height:40px; display:flex; align-items:center;">
        <i class="bi bi-upload"></i> Upload Laporan Baru
    </a>
</div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="mb-4 d-flex justify-content-between align-items-center">
    <form method="GET" action="" class="d-flex align-items-center" style="gap:8px;">
        <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari nama laporan / file dokumen..." style="width:260px;">
        <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
        @if(request()->filled('q'))
            <a href="{{ route('dokumen-barang-pihak2.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="row g-3">
    @forelse($laporans as $laporan)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <i class="bi bi-file-earmark-text" style="font-size:2.5rem; color:#0d6efd;"></i>
                </div>
                <h5 class="card-title mb-2">
                    @php $q = request('q'); @endphp
                    @if($q)
                        {!! preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark>$1</mark>', e($laporan->nama_laporan)) !!}
                    @else
                        {{ $laporan->nama_laporan }}
                    @endif
                </h5>
                <div class="mb-3 text-muted" style="font-size:0.9rem;">
                    <div class="mb-2">
                        <small><strong>Cabang:</strong> {{ $laporan->cabang->nama_cabang ?? '-' }}</small>
                    </div>
                    <div class="mb-2">
                        <small><strong>Uploader:</strong> {{ $laporan->uploader->name ?? '-' }}</small>
                    </div>
                    <div>
                        <small><strong>Upload:</strong> {{ $laporan->created_at->format('d-m-Y H:i') }}</small>
                    </div>
                </div>
                <div class="mt-auto">
                    <a href="{{ route('dokumen-barang-pihak2.download', $laporan->id) }}" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center" role="alert">
            <i class="bi bi-info-circle"></i> Belum ada laporan diunggah.
        </div>
    </div>
    @endforelse
</div>
@endsection
