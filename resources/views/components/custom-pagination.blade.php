@props(['paginator', 'perPageOptions' => [10, 25, 50, 100]])
@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $firstItem = $paginator->firstItem();
    $lastItem = $paginator->lastItem();
    $total = $paginator->total();
    $perPage = $paginator->perPage();
    $query = request()->query();
    $baseUrl = url()->current();
    $window = 2; // how many pages to show around current
    $showFirst = 1;
    $showLast = $lastPage;
    $pages = [];
    if ($lastPage <= 7) {
        for ($i = 1; $i <= $lastPage; $i++) $pages[] = $i;
    } else {
        $pages[] = 1;
        if ($currentPage > ($window + 2)) $pages[] = '...';
        for ($i = max(2, $currentPage - $window); $i <= min($lastPage - 1, $currentPage + $window); $i++) $pages[] = $i;
        if ($currentPage < $lastPage - ($window + 1)) $pages[] = '...';
        $pages[] = $lastPage;
    }
    $queryNoPage = array_filter($query, fn($k) => $k !== 'page', ARRAY_FILTER_USE_KEY);
    $queryNoPerPage = array_filter($query, fn($k) => $k !== 'per_page', ARRAY_FILTER_USE_KEY);
@endphp
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
    <div class="small text-muted">
        Menampilkan {{ $firstItem }}–{{ $lastItem }} dari {{ $total }} data
    </div>
</div>
<nav aria-label="Custom pagination">
    <ul class="pagination justify-content-center flex-wrap mb-0">
        <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
            <a class="page-link rounded" href="{{ $currentPage == 1 ? '#' : $paginator->url($currentPage - 1) }}{{ http_build_query($queryNoPage) ? '&'.http_build_query($queryNoPage) : '' }}" tabindex="-1">&laquo; Previous</a>
        </li>
        @foreach($pages as $page)
            @if($page === '...')
                <li class="page-item disabled"><span class="page-link bg-light border-0">...</span></li>
            @else
                <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                    <a class="page-link rounded {{ $currentPage == $page ? 'bg-primary text-white border-primary' : '' }}" href="{{ $paginator->url($page) }}{{ http_build_query($queryNoPage) ? '&'.http_build_query($queryNoPage) : '' }}">{{ $page }}</a>
                </li>
            @endif
        @endforeach
        <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
            <a class="page-link rounded" href="{{ $currentPage == $lastPage ? '#' : $paginator->url($currentPage + 1) }}{{ http_build_query($queryNoPage) ? '&'.http_build_query($queryNoPage) : '' }}">Next &raquo;</a>
        </li>
    </ul>
</nav>
<div class="d-flex justify-content-center mt-2">
    <form method="GET" class="d-flex align-items-center gap-2">
        @foreach($queryNoPage as $k => $v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach
        <label class="me-1 small">Ke halaman</label>
        <input type="number" min="1" max="{{ $lastPage }}" name="page" class="form-control form-control-sm w-auto" style="width:70px;" value="{{ $currentPage }}">
        <button type="submit" class="btn btn-sm btn-outline-primary rounded">Go</button>
    </form>
</div>
@push('styles')
<style>
    .pagination .page-link {
        border-radius: 0.5rem;
        transition: background 0.2s, color 0.2s;
    }
    .pagination .page-link:hover {
        background: #e9ecef;
        color: #0d6efd;
    }
    .pagination .active .page-link,
    .pagination .page-link.bg-primary {
        background: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
    .pagination .page-item.disabled .page-link {
        color: #adb5bd;
        background: #f8f9fa;
        border-color: #dee2e6;
    }
    @media (max-width: 576px) {
        .pagination {
            font-size: 0.95em;
        }
        .pagination .page-link {
            padding: 0.3rem 0.6rem;
        }
    }
</style>
@endpush
