@extends('layout')

@section('title', 'Terjadi Kesalahan Server (500)')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="bi bi-exclamation-circle"></i> Terjadi Kesalahan Server</h4>
            </div>
            <div class="card-body text-center py-5">
                <h1 class="display-1 text-danger">500</h1>
                <p class="fs-5 text-muted">Maaf, terjadi kesalahan pada server kami.</p>
                <p class="text-muted">Tim kami sedang bekerja untuk memperbaiki masalah ini.</p>
                
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-house"></i> Kembali ke Dashboard
                    </a>
                    <a href="javascript:location.reload()" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Coba Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
