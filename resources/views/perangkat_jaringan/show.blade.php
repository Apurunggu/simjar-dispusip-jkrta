@extends('layout')

@section('title', 'Detail Perangkat Jaringan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-info-circle"></i> Detail Perangkat Jaringan</h1>
    <div>
        <a href="{{ route('perangkat-jaringan.edit', $perangkatJaringan->id) }}" class="btn btn-warning btn-custom">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('perangkat-jaringan.activity-log', $perangkatJaringan->id) }}" class="btn btn-info btn-custom">
            <i class="bi bi-clock-history"></i> Log Aktivitas
        </a>
        <a href="{{ route('perangkat-jaringan.index') }}" class="btn btn-secondary btn-custom">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Perangkat</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nomor Inventaris:</strong>
                    </div>
                    <div class="col-md-8">
                        <strong>{{ $perangkatJaringan->nomor_inventaris }}</strong>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nama Perangkat:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $perangkatJaringan->nama_perangkat }}
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tipe Perangkat:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-secondary">{{ $perangkatJaringan->tipe_perangkat }}</span>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Lokasi:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $perangkatJaringan->lokasi }}
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>IP Address:</strong>
                    </div>
                    <div class="col-md-8">
                        <code>{{ $perangkatJaringan->ip_address ?? '-' }}</code>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>MAC Address:</strong>
                    </div>
                    <div class="col-md-8">
                        <code>{{ $perangkatJaringan->mac_address ?? '-' }}</code>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge @if($perangkatJaringan->status == 'aktif') badge-status-aktif @else badge-status-tidak-aktif @endif">
                            {{ ucfirst(str_replace('_', ' ', $perangkatJaringan->status)) }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tanggal Pemasangan:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $perangkatJaringan->tanggal_pemasangan->format('d F Y') }}
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Keterangan:</strong>
                    </div>
                    <div class="col-md-8">
                        {{ $perangkatJaringan->keterangan ?? 'Tidak ada keterangan' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-history"></i> Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                @forelse($logs->take(5) as $log)
                    <div class="mb-3">
                        <small class="text-muted">{{ $log->tanggal_aktivitas->format('d-m-Y H:i') }}</small>
                        <p class="mb-1"><strong>{{ $log->aktivitas }}</strong></p>
                        <small class="text-secondary">{{ $log->deskripsi }}</small>
                        <hr>
                    </div>
                @empty
                    <p class="text-muted text-center">Belum ada aktivitas</p>
                @endforelse
                
                @if($logs->count() > 5)
                    <a href="{{ route('perangkat-jaringan.activity-log', $perangkatJaringan) }}" class="btn btn-sm btn-info btn-custom w-100">
                        <i class="bi bi-arrow-right"></i> Lihat Semua
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
