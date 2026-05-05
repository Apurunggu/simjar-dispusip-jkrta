@extends('layout')

@section('title', 'Perangkat Jaringan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-router"></i> Inventaris Perangkat Jaringan</h1>
    <a href="{{ route('perangkat-jaringan.create') }}" class="btn btn-primary btn-custom">
        <i class="bi bi-plus-circle"></i> Tambah Perangkat
    </a>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('perangkat-jaringan.index') }}" class="row g-3">
            <div class="col-md-6">
                <label for="lokasi" class="form-label">Filter Berdasarkan Lokasi</label>
                <select class="form-select" id="lokasi" name="lokasi">
                    <option value="">-- Semua Lokasi --</option>
                    @foreach($lokasi as $l)
                        <option value="{{ $l }}" @if(request('lokasi') == $l) selected @endif>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-custom">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('perangkat-jaringan.index') }}" class="btn btn-secondary btn-custom">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nomor Inventaris</th>
                        <th>Nama Perangkat</th>
                        <th>Tipe</th>
                        <th>Lokasi</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perangkat as $item)
                        <tr>
                            <td>{{ $loop->iteration + ($perangkat->currentPage() - 1) * $perangkat->perPage() }}</td>
                            <td><strong>{{ $item->nomor_inventaris }}</strong></td>
                            <td>{{ $item->nama_perangkat }}</td>
                            <td><span class="badge bg-secondary">{{ $item->tipe_perangkat }}</span></td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ $item->ip_address ?? '-' }}</td>
                            <td>
                                <span class="badge @if($item->status == 'aktif') badge-status-aktif @else badge-status-tidak-aktif @endif">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('perangkat-jaringan.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('perangkat-jaringan.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($item->status == 'aktif')
                                    <form action="{{ route('perangkat-jaringan.deactivate', $item->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menonaktifkan?')" title="Nonaktifkan">
                                            <i class="bi bi-power"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('perangkat-jaringan.activate', $item->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin mengaktifkan?')" title="Aktifkan">
                                            <i class="bi bi-play-circle"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('perangkat-jaringan.activity-log', $item->id) }}" class="btn btn-sm btn-primary" title="Log Aktivitas">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data perangkat jaringan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                {{ $perangkat->links('pagination::bootstrap-5') }}
            </ul>
        </nav>
    </div>
</div>
@endsection
