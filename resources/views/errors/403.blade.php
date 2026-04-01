@extends('layout')

@section('title', 'Akses Ditolak (403)')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Akses Ditolak</h4>
            </div>
            <div class="card-body text-center py-5">
                <h1 class="display-1 text-danger">403</h1>
                <p class="fs-5 text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                <p class="text-muted">Hubungi administrator jika Anda yakin ini adalah kesalahan.</p>
                
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-house"></i> Kembali ke Dashboard
                    </a>
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
