@extends('layout')

@section('title', 'Barang Masuk')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h1 class="mb-0" style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-box-seam"></i> Data Barang Masuk</h1>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <form method="GET" action="{{ route('barang-masuk.index') }}" class="d-flex align-items-center gap-2 mb-0">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari nomor / nama / kategori" style="width:220px;">
            <button type="submit" class="btn btn-sm btn-outline-primary">Search</button>
            @if(request()->filled('q'))
                <a href="{{ route('barang-masuk.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
        <a href="{{ route('barang-masuk.create') }}" class="btn btn-sm btn-success">
            <i class="bi bi-plus-circle"></i> Tambah Barang
        </a>
        <a href="{{ route('barang-masuk.exportPdf') }}" class="btn btn-sm btn-danger">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('barang-masuk.importForm') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-file-earmark-arrow-up"></i> Import Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="table-dark sticky-top">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Serial Number</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Sisa Stok</th>
                        <th>Kepemilikan</th>
                        <th>Status</th>
                        <th>Posisi</th>
                        <th class="text-center">Tahun Pengadaan</th>
                        <th>Keterangan</th>
                        <th class="text-center">Barang Masuk</th>
                        <th class="text-center">Barang Keluar</th>
                        <th class="text-center">Tanggal Masuk</th>
                        <th>Dokumen</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangMasuk as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration + ($barangMasuk->currentPage() - 1) * $barangMasuk->perPage() }}</td>
                            @php $q = request('q'); @endphp
                            <td>
                                @if($item->serialNumbers && count($item->serialNumbers))
                                    @foreach($item->serialNumbers as $idx => $serial)
                                        <span class="badge bg-dark text-white mb-1">D{{ $idx+1 }}: {{ $serial->serial_number }}</span><br>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->nama_barang ?? $item->nama_perangkat ?? '-' }}</td>
                            <td>
                                @php
                                    $kategoriColors = [
                                        'Router' => 'bg-success',
                                        'Access Point' => 'bg-warning text-dark',
                                        'Modem' => 'bg-purple',
                                        'Switch' => 'bg-primary',
                                        'Kabel' => 'bg-danger',
                                    ];
                                    $badgeColor = $kategoriColors[$item->kategori ?? $item->merk_type ?? ''] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeColor }}">{{ $item->kategori ?? $item->merk_type ?? '-' }}</span>
                            </td>
                            <td class="text-center">{{ $item->jumlah ?? $item->qty ?? '-' }}</td>
                            <td class="text-center">{{ $item->satuan ?? '-' }}</td>
                            <td class="text-center">{{ $item->sisa_stok ?? '-' }}</td>
                            <td>
                                @if($item->kepemilikan)
                                    <span class="badge bg-info text-dark">{{ $item->kepemilikan }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status)
                                    <span class="badge {{ $item->status == 'TERPASANG' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $item->status }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->posisi ?? '-' }}</td>
                            <td class="text-center">{{ $item->tahun_pengadaan ?? '-' }}</td>
                            <td>
                                @if($item->keterangan)
                                    <span title="{{ $item->keterangan }}">{{ \Illuminate\Support\Str::limit($item->keterangan, 20) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->barang_masuk ?? '-' }}</td>
                            <td class="text-center">{{ $item->barang_keluar ?? '-' }}</td>
                            <td class="text-center">{{ isset($item->tanggal_masuk) ? (is_object($item->tanggal_masuk) ? $item->tanggal_masuk->format('d-m-Y') : $item->tanggal_masuk) : '-' }}</td>
                            <td>
                                <a href="{{ route('barang-masuk.pdfLaporan', $item->id) }}" class="btn btn-sm btn-outline-danger mb-1" title="Download PDF Laporan" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('barang-masuk.show', $item->id) }}" class="btn btn-sm btn-info mb-1" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('barang-masuk.edit', $item->id) }}" class="btn btn-sm btn-warning mb-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('barang-masuk.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="text-center text-muted">Tidak ada data barang masuk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-custom-pagination :paginator="$barangMasuk" />
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom Pagination Style */
    .pagination {
        gap: 4px;
    }
    .pagination .page-item {
        margin: 0;
    }
    .pagination .page-link {
        padding: 0.4rem 0.75rem;
        font-size: 1rem;
        border-radius: 6px;
        min-width: 36px;
        text-align: center;
    }
    .pagination .page-item.active .page-link {
        background-color: #39506a;
        border-color: #39506a;
        color: #fff;
    }
    .pagination .page-link:focus {
        box-shadow: 0 0 0 0.15rem #39506a33;
    }
    .table-hover tbody tr:hover {
        background-color: #f2f6fa;
    }
    .badge {
        font-size: 0.95em;
        padding: 0.45em 0.7em;
        border-radius: 0.5em;
    }
</style>
@endpush
