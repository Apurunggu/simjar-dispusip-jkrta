@extends('layout')

@section('title', 'Halaman Tidak Ditemukan (404)')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card border-warning">
            <div class="card-header bg-warning">
                <h4 class="mb-0"><i class="bi bi-question-circle"></i> Halaman Tidak Ditemukan</h4>
            </div>
            <div class="card-body text-center py-5">
                <h1 class="display-1 text-warning">404</h1>
                <p class="fs-5 text-muted">Halaman yang Anda cari tidak ditemukan.</p>
                <p class="text-muted">Mungkin URL sudah berubah atau halaman telah dihapus.</p>
                
                <div class="mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-house"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
