@extends('layout')

@section('title', 'Dashboard - Debug')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>✓ Dashboard Debug View</h1>
        <p class="lead">View template is rendering correctly!</p>
        
        <div class="alert alert-info">
            <h5>Data Received:</h5>
            <ul>
                <li>Total Barang Masuk: <strong>{{ $totalBarangMasuk ?? 'NULL' }}</strong></li>
                <li>Total Stok: <strong>{{ $totalStok ?? 'NULL' }}</strong></li>
                <li>Total Unik Barang: <strong>{{ $totalUnikBarang ?? 'NULL' }}</strong></li>
                <li>Perangkat Aktif: <strong>{{ $totalPerangkatAktif ?? 'NULL' }}</strong></li>
                <li>Perangkat Tidak Aktif: <strong>{{ $totalPerangkatTidakAktif ?? 'NULL' }}</strong></li>
                <li>Distribusi Pending: <strong>{{ $distribusiPending ?? 'NULL' }}</strong></li>
                <li>Total Terdistribusi: <strong>{{ $totalTerdistribusi ?? 'NULL' }}</strong></li>
            </ul>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $totalBarangMasuk ?? 0 }}</h3>
                        <p>Total Barang Masuk</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h3 class="text-success">{{ $totalStok ?? 0 }}</h3>
                        <p>Stok Pusat</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <h3 class="text-info">{{ $totalTerdistribusi ?? 0 }}</h3>
                        <p>Terdistribusi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h3 class="text-warning">{{ $distribusiPending ?? 0 }}</h3>
                        <p>Pending</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
