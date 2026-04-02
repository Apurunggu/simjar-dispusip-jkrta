@extends('layout')

@section('title', 'Activity Log Distribusi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Activity Log Distribusi
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>ID Distribusi:</strong> #{{ $distribusi->id }}</p>
                    <p><strong>Barang:</strong> {{ $distribusi->barang->nama_barang ?? '-' }} ({{ $distribusi->jumlah }} unit)</p>
                    <p><strong>Dari:</strong> {{ $distribusi->cabangAsal->nama_cabang ?? '-' }} → <strong>Ke:</strong> {{ $distribusi->cabangTujuan->nama_cabang ?? '-' }}</p>
                    <p><strong>Status Saat Ini:</strong> <span class="badge bg-{{ $distribusi->getStatusBadgeAttribute() }}">{{ ucfirst($distribusi->status) }}</span></p>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Perubahan Status</h5>
                </div>
                <div class="card-body">
                    @forelse($logs as $log)
                        <div class="timeline-item mb-4 pb-4" style="border-bottom: 1px solid #dee2e6;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $log->aktivitas }}</h6>
                                    <p class="mb-2">
                                        <strong>Perubahan Status:</strong>
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $log->status_awal)) }}</span>
                                        <i class="bi bi-arrow-right"></i>
                                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $log->status_baru)) }}</span>
                                    </p>
                                    @if($log->user)
                                        <p class="mb-2">
                                            <strong>Diubah oleh:</strong> {{ $log->user->name }} ({{ $log->user->email }})
                                        </p>
                                    @endif
                                    @if($log->catatan)
                                        <p class="mb-2">
                                            <strong>Catatan:</strong> {{ $log->catatan }}
                                        </p>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($log->tanggal_aktivitas)->format('d-m-Y H:i:s') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Tidak ada history untuk distribusi ini.</p>
                    @endforelse

                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                {{ $logs->links('pagination::bootstrap-5') }}
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Tab Navigasi -->
            <div class="card bg-light mb-3">
                <div class="card-header bg-secondary text-white">
                    <ul class="nav nav-tabs card-header-tabs" id="menuTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="dokumen-tab" data-bs-toggle="tab" data-bs-target="#dokumen" type="button" role="tab" aria-controls="dokumen" aria-selected="true">Dokumen Barang</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pihak1-tab" data-bs-toggle="tab" data-bs-target="#pihak1" type="button" role="tab" aria-controls="pihak1" aria-selected="false">Laporan Pihak 1</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pihak2-tab" data-bs-toggle="tab" data-bs-target="#pihak2" type="button" role="tab" aria-controls="pihak2" aria-selected="false">Laporan Pihak 2</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content">
                    <div class="tab-pane fade show active" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Surat Jalan Barang</li>
                            <li class="list-group-item">Bukti Pengiriman</li>
                            <!-- Tambahkan dokumen lain di sini -->
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="pihak1" role="tabpanel" aria-labelledby="pihak1-tab">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Laporan Serah Terima Pihak 1</li>
                            <li class="list-group-item">Berita Acara Pihak 1</li>
                            <!-- Tambahkan laporan pihak 1 di sini -->
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="pihak2" role="tabpanel" aria-labelledby="pihak2-tab">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Laporan Serah Terima Pihak 2</li>
                            <li class="list-group-item">Berita Acara Pihak 2</li>
                            <!-- Tambahkan laporan pihak 2 di sini -->
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Informasi Distribusi Tetap Ditampilkan di Bawah Tab -->
            <div class="card bg-light">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Informasi Distribusi</h6>
                </div>
                <div class="card-body">
                    <p>
                        <strong>ID:</strong> #{{ $distribusi->id }}
                    </p>
                    <p>
                        <strong>Barang:</strong><br>
                        {{ $distribusi->barang->nomor_barang ?? '-' }}<br>
                        {{ $distribusi->barang->nama_barang ?? '-' }}
                    </p>
                    <p>
                        <strong>Jumlah:</strong> {{ $distribusi->jumlah }} unit
                    </p>
                    <p>
                        <strong>Asal:</strong><br>
                        {{ $distribusi->cabangAsal->nama_cabang ?? '-' }}
                    </p>
                    <p>
                        <strong>Tujuan:</strong><br>
                        {{ $distribusi->cabangTujuan->nama_cabang ?? '-' }}
                    </p>
                    <p>
                        <strong>Tanggal Kirim:</strong><br>
                        {{ \Carbon\Carbon::parse($distribusi->tanggal_kirim)->format('d-m-Y') }}
                    </p>
                    @if($distribusi->tanggal_terima)
                        <p>
                            <strong>Tanggal Terima:</strong><br>
                            {{ \Carbon\Carbon::parse($distribusi->tanggal_terima)->format('d-m-Y') }}
                        </p>
                    @endif
                    <p>
                        <strong>Total Aktivitas:</strong> {{ $distribusi->activityLogs()->count() }}
                    </p>
                </div>
            </div>

            <a href="{{ route('distribusi.show', $distribusi->id) }}" class="btn btn-primary btn-sm w-100 mt-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var triggerTabList = [].slice.call(document.querySelectorAll('#menuTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault();
                var tabTrigger = new bootstrap.Tab(triggerEl);
                tabTrigger.show();
            });
        });
    });
</script>
@endsection
