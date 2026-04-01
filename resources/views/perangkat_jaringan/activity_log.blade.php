@extends('layout')

@section('title', 'Log Aktivitas Perangkat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-clock-history"></i> Log Aktivitas Perangkat</h1>
    <a href="{{ route('perangkat-jaringan.show', $perangkatJaringan->id) }}" class="btn btn-secondary btn-custom">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <strong>Perangkat:</strong> {{ $perangkatJaringan->nama_perangkat }}<br>
                <strong>Nomor Inventaris:</strong> {{ $perangkatJaringan->nomor_inventaris }}
            </div>
            <div class="col-md-6 text-end">
                <strong>Total Aktivitas:</strong> {{ $logs->total() }}<br>
                <strong>Status:</strong> 
                <span class="badge @if($perangkatJaringan->status == 'aktif') badge-status-aktif @else badge-status-tidak-aktif @endif">
                    {{ ucfirst(str_replace('_', ' ', $perangkatJaringan->status)) }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tanggal & Waktu</th>
                        <th>Aktivitas</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                            <td>
                                <strong>{{ $log->tanggal_aktivitas->format('d-m-Y') }}</strong><br>
                                <small class="text-muted">{{ $log->tanggal_aktivitas->format('H:i:s') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $log->aktivitas }}</span>
                            </td>
                            <td>{{ $log->deskripsi ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada log aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                {{ $logs->links('pagination::bootstrap-5') }}
            </ul>
        </nav>
    </div>
</div>
@endsection
