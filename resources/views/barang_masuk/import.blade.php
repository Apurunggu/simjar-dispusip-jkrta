@extends('layout')

@section('title', 'Import Barang Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 style="color: #FFFFFF; font-weight: 800; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><i class="bi bi-file-earmark-arrow-up"></i> Import Barang Masuk</h1>
    <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary btn-custom">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<!-- Download Template -->
<div class="card mb-4 border-success">
    <div class="card-body text-center">
        <h5 class="mb-3">Belum punya template?</h5>
        <a href="{{ asset('sample_import_barang.xlsx') }}" class="btn btn-success btn-lg">
            <i class="bi bi-download"></i> Download Template Excel
        </a>
        <p class="text-muted mt-2">File ini sudah berisi format yang benar dengan contoh data</p>
    </div>
</div>

<!-- Import Form -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-upload"></i> Upload File</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('barang-masuk.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Pilih file Excel</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" 
                    accept=".xlsx,.xls,.csv" required onchange="validateFile(this)">
                @error('file')
                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                @enderror
                <div class="form-text" id="fileInfo"></div>
            </div>

            <button type="submit" class="btn btn-primary btn-custom" id="submitBtn">
                <i class="bi bi-upload"></i> Import
            </button>
            <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary btn-custom">
                <i class="bi bi-x-circle"></i> Batal
            </a>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function validateFile(input) {
    const fileInfo = document.getElementById('fileInfo');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!input.files || !input.files[0]) {
        fileInfo.innerHTML = '';
        submitBtn.disabled = false;
        return;
    }
    
    const file = input.files[0];
    const maxSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                         'application/vnd.ms-excel',
                         'text/csv',
                         'application/vnd.ms-excel.sheet.binary.macroEnabled.12'];
    
    // Check file size
    if (file.size > maxSize) {
        fileInfo.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-circle"></i> File terlalu besar (max 5MB)</span>';
        submitBtn.disabled = true;
        input.value = '';
        return;
    }
    
    // Check file type
    if (!allowedTypes.includes(file.type)) {
        fileInfo.innerHTML = '<span class="text-warning"><i class="bi bi-info-circle"></i> Format mungkin tidak valid. File akan dicek saat upload.</span>';
    } else {
        fileInfo.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> ' + file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)</span>';
    }
    
    submitBtn.disabled = false;
}
</script>
@endpush
