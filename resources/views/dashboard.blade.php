@extends('layout')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Dashboard</h1>
        <p class="lead">Selamat datang di SIMJAR - Sistem Inventory dan Distribusi Perangkat Jaringan</p>
        
        <div class="row mt-4">
            <!-- Barang Masuk -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-box-seam"></i> Total Barang Masuk</h5>
                        <h2>{{ $totalBarangMasuk ?? 0 }}</h2>
                        <small class="text-muted">Unit</small>
                        <div class="mt-2"><small class="text-secondary">Unik: {{ $totalUnikBarang ?? 0 }} jenis</small></div>
                    </div>
                </div>
            </div>

            <!-- Stok Tersedia -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-bag-check"></i> Stok Pusat</h5>
                        <h2 class="text-success">{{ $totalStok ?? 0 }}</h2>
                        <small class="text-muted">Unit tersedia</small>
                    </div>
                </div>
            </div>

            <!-- Terdistribusi -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-truck"></i> Terdistribusi</h5>
                        <h2 class="text-info">{{ $totalTerdistribusi ?? 0 }}</h2>
                        <small class="text-muted">Unit di cabang</small>
                    </div>
                </div>
            </div>

            <!-- Distribusi Pending -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-clock-history"></i> Pending</h5>
                        <h2 class="text-warning">{{ $distribusiPending ?? 0 }}</h2>
                        <small class="text-muted">Menunggu konfirmasi</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perangkat Jaringan -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-router"></i> Perangkat Aktif</h5>
                        <h2 class="text-success">{{ $totalPerangkatAktif ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-x-circle"></i> Perangkat Tidak Aktif</h5>
                        <h2 class="text-danger">{{ $totalPerangkatTidakAktif ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unik per Kategori -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-grid-3x3-gap-fill"></i> Unik per Kategori</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <canvas id="chart-unique-kategori" height="80"></canvas>
                            </div>
                        </div>
                        <div class="row mt-3">
                            @if(!empty($uniqueByKategori) && $uniqueByKategori->count())
                                @foreach($uniqueByKategori as $k)
                                    <div class="col-md-3 mb-2">
                                        <div class="p-2 border rounded text-center">
                                            <strong>{{ $k->kategori }}</strong>
                                            <div class="text-muted">{{ $k->unique_count }} jenis</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12 text-center text-muted">Tidak ada data kategori.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Cepat -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-lightning-fill"></i> Menu Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('barang-masuk.index') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-list"></i><br>Barang Masuk
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('distribusi.index') }}" class="btn btn-info w-100">
                                    <i class="bi bi-truck"></i><br>Distribusi
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('perangkat-jaringan.index') }}" class="btn btn-success w-100">
                                    <i class="bi bi-router"></i><br>Perangkat
                                </a>
                            </div>
                            <div class="col-md-3">
                                @if(auth()->user()->hasRole('super_admin'))
                                    <a href="{{ route('barang-masuk.create') }}" class="btn btn-warning w-100">
                                        <i class="bi bi-plus-circle"></i><br>+ Barang
                                    </a>
                                @else
                                    <a href="{{ route('distribusi.create') }}" class="btn btn-warning w-100">
                                        <i class="bi bi-plus-circle"></i><br>+ Distribusi
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function(){
        const data = {!! json_encode($uniqueByKategori ?? []) !!};
        if (!data || !data.length) return;
        const labels = data.map(d => d.kategori);
        const values = data.map(d => Number(d.unique_count));

        const ctx = document.getElementById('chart-unique-kategori');
        if (!ctx) return;

        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jenis Unik',
                    data: values,
                    backgroundColor: 'rgba(54,162,235,0.6)',
                    borderColor: 'rgba(54,162,235,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, precision:0 }
                }
            }
        });
    })();
</script>
@endpush

