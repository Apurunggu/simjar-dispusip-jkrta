@extends('layout')

@section('title', 'Laporan Aktivitas Distribusi')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 text-center" style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">Laporan Aktivitas Distribusi</h2>
    <form method="GET" action="{{ route('distribusi.activity-report') }}" class="mb-3">
        <div class="row mb-2">
            <div class="col-md-3">
                <label style="color: #ffffff;">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
            </div>
            <!-- <div class="col-md-3">
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
            </div> -->
            <div class="col-md-3">
                <label style="color: #ffffff;">Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Semua --</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="dikirim" {{ request('status')=='dikirim'?'selected':'' }}>Dikirim</option>
                    <option value="diterima" {{ request('status')=='diterima'?'selected':'' }}>Diterima</option>
                    <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-8 text-end">
                <a href="{{ route('distribusi.activity-report.export.pdf', request()->all()) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
                <a href="{{ route('distribusi.activity-report.export.word', request()->all()) }}" class="btn btn-primary"><i class="bi bi-file-earmark-word"></i> Export Word</a>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis / Merk</th>
                    <th>Jumlah</th>
                    <th>Foto</th>
                    <th>Tanggal Distribusi</th>
                    <!-- <th>Tanggal Kembali</th> -->
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $i => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $i }}</td>
                        <td>{{ $log->user->name ?? '-' }}</td>
                        <td>{{ $log->distribusi && $log->distribusi->barang ? $log->distribusi->barang->nama_barang : '-' }}</td>
                        <td>{{ $log->distribusi ? $log->distribusi->jumlah : '-' }}</td>
                        <td>
                            @if($log->distribusi && $log->distribusi->foto)
                                <a href="{{ url('storage/' . $log->distribusi->foto) }}" target="_blank" class="btn btn-info btn-sm"><i class="bi bi-camera"></i> Lihat</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($log->distribusi && $log->distribusi->tanggal_kirim)
                                {{ \Carbon\Carbon::parse($log->distribusi->tanggal_kirim)->format('Y-m-d H:i:s') }}
                            @else
                                -
                            @endif
                        </td>
                        <!-- <td>{{ $log->distribusi ? $log->distribusi->tanggal_kembali : '-' }}</td> -->
                        <td>
                            <span class="badge 
                                @if($log->distribusi && $log->distribusi->status == 'pending') bg-warning
                                @elseif($log->distribusi && $log->distribusi->status == 'dikirim') bg-info
                                @elseif($log->distribusi && $log->distribusi->status == 'diterima') bg-success
                                @elseif($log->distribusi && $log->distribusi->status == 'ditolak') bg-danger
                                @else bg-secondary @endif">
                                {{ $log->distribusi ? ucfirst($log->distribusi->status) : 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @if($log->distribusi)
                                @if(auth()->user() && auth()->user()->hasRole('super_admin'))
                                    <form action="{{ route('distribusi.uploadFoto', $log->distribusi->id) }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                                        @csrf
                                        <label for="foto-{{ $log->distribusi->id }}" class="btn btn-warning btn-sm" title="Upload Foto"><i class="bi bi-camera"></i></label>
                                        <input type="file" name="foto" id="foto-{{ $log->distribusi->id }}" accept="image/*" style="display:none;" onchange="this.form.submit()">
                                    </form>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
