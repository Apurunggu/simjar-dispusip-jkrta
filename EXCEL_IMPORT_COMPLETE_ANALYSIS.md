# SIMJAR - Excel Import Functionality - Complete Analysis

## 1. DEPENDENCIES & REQUIREMENTS

### Composer Dependencies
**File**: [composer.json](composer.json)

```json
{
  "phpoffice/phpspreadsheet": "^5.4",      // Main Excel library
  "phpoffice/phpword": "^1.4",              // Word/Document support
  "barryvdh/laravel-dompdf": "^2.1"         // PDF export
}
```

**Key Library**: `PhpOffice\PhpSpreadsheet\IOFactory` - Used for reading Excel files (.xlsx, .xls, .csv)

---

## 2. ROUTES FOR IMPORT

### Location
**File**: [routes/web.php](routes/web.php#L62-L63)

```php
Route::prefix('barang-masuk')->name('barang-masuk.')->group(function () {
    // Import routes
    Route::get('/import', [BarangMasukController::class, 'importForm'])->name('importForm');
    Route::post('/import', [BarangMasukController::class, 'import'])->name('import');
    
    // Other routes...
    Route::get('/', [BarangMasukController::class, 'index'])->name('index');
    Route::get('/create', [BarangMasukController::class, 'create'])->name('create');
    Route::post('/', [BarangMasukController::class, 'store'])->name('store');
    Route::get('/export/pdf', [BarangMasukController::class, 'exportPdf'])->name('exportPdf');
    Route::get('/{id}', [BarangMasukController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [BarangMasukController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BarangMasukController::class, 'update'])->name('update');
    Route::delete('/{id}', [BarangMasukController::class, 'destroy'])->name('destroy');
});
```

### Route Details
| Route | Method | Handler | Purpose |
|-------|--------|---------|---------|
| `/barang-masuk/import` | GET | `BarangMasukController@importForm` | Display import form UI |
| `/barang-masuk/import` | POST | `BarangMasukController@import` | Process Excel file upload |

---

## 3. CONTROLLER - IMPORT IMPLEMENTATION

### Location
**File**: [app/Http/Controllers/BarangMasukController.php](app/Http/Controllers/BarangMasukController.php#L233-L320)

### Method 1: `importForm()`
```php
public function importForm(): View
{
    return view('barang_masuk.import');
}
```
**Purpose**: Returns the import form view

---

### Method 2: `import()`
```php
public function import(Request $request): RedirectResponse
{
    // 1. FILE VALIDATION
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    // 2. READ EXCEL FILE
    $file = $request->file('file');
    $spreadsheet = IOFactory::load($file->getPathname());
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // 3. NORMALIZE HEADERS
    $header = array_map(function($h) {
        return strtolower(str_replace([' ', '\t', '\n', '\r'], '', trim($h)));
    }, $rows[0] ?? []);

    $imported = 0;
    $errors = [];
    $userCabangId = auth()->user()->cabang_id ?? null;
    
    if (!$userCabangId) {
        return redirect()->back()->with('error', 'User tidak memiliki cabang yang valid. Hubungi admin.');
    }

    // 4. COLUMN MAPPING (Excel headers → Database fields)
    $mapField = [
        'no' => 'nomor_barang',
        'namaperangkat' => 'nama_barang',
        'merk/type' => 'kategori',
        'qty' => 'jumlah',
        'sisastok' => 'stok',
        'keterangan' => 'keterangan',
    ];

    // 5. REQUIRED COLUMNS VALIDATION
    $requiredCols = ['namaperangkat', 'qty'];
    foreach ($requiredCols as $col) {
        if (!in_array($col, $header)) {
            return redirect()->back()->with('error', 'Kolom wajib tidak ditemukan di file Excel: ' . $col);
        }
    }

    // 6. PROCESS ROWS
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        if (empty(array_filter($row))) continue;  // Skip empty rows

        $data = [];
        try {
            // Map Excel columns to database fields
            $rowAssoc = [];
            foreach ($header as $idx => $col) {
                $rowAssoc[$col] = $row[$idx] ?? null;
            }
            
            // Apply mapping
            foreach ($mapField as $excelCol => $dbCol) {
                $data[$dbCol] = isset($rowAssoc[$excelCol]) ? trim($rowAssoc[$excelCol]) : null;
            }
            
            // Set defaults
            $data['tanggal_masuk'] = date('Y-m-d');
            $data['cabang_id'] = $userCabangId;

            // Data validation
            if (empty($data['nama_barang']) || empty($data['jumlah'])) continue;

            // 7. INSERT OR UPDATE
            BarangMasuk::updateOrCreate(
                ['nomor_barang' => $data['nomor_barang']],
                $data
            );
            $imported++;
        } catch (\Throwable $e) {
            $errors[] = 'Baris ' . ($i+1) . ': ' . $e->getMessage();
        }
    }

    // 8. RESPONSE
    if ($imported === 0) {
        $msg = 'Tidak ada data yang berhasil diimport.';
        if ($errors) $msg .= ' Error: ' . implode(' | ', $errors);
        return redirect()->back()->with('error', $msg);
    }

    $msg = 'Import selesai. ' . $imported . ' data berhasil diimport.';
    if ($errors) $msg .= ' Beberapa baris gagal: ' . implode(' | ', $errors);
    return redirect()->route('barang-masuk.index')->with('success', $msg);
}
```

**Key Features**:
- Uses `IOFactory::load()` to read Excel/CSV files
- Normalizes Excel headers (removes spaces, converts to lowercase)
- Flexible column mapping from Excel to database
- Uses `updateOrCreate()` for upsert logic (update if exists, create if not)
- Sets `tanggal_masuk` to today's date automatically
- Assigns to user's cabang (branch) automatically
- Collects and reports all errors
- Returns success/error messages

---

## 4. DATABASE SCHEMA

### Location
**Files**: [database/migrations/](database/migrations/)

### Base Table Structure
**File**: [2024_01_01_000001_create_barang_masuk_table.php](database/migrations/2024_01_01_000001_create_barang_masuk_table.php)

```sql
CREATE TABLE barang_masuk (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nomor_barang VARCHAR(255) UNIQUE,
    nama_barang VARCHAR(255),
    kategori VARCHAR(255),
    jumlah INTEGER,
    tanggal_masuk DATE,
    keterangan TEXT NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Additional Fields (Migration: 2026_02_15_000005)
```sql
ALTER TABLE barang_masuk ADD COLUMN stok INTEGER DEFAULT 0;
```

### Branch/Cabang Association (Migration: 2026_02_15_000006)
```sql
ALTER TABLE barang_masuk ADD COLUMN cabang_id BIGINT UNSIGNED NULLABLE;
ALTER TABLE barang_masuk ADD FOREIGN KEY (cabang_id) REFERENCES cabangs(id) ON DELETE SET NULL;
```

### Document Support (Migration: 2026_02_24_000007)
```sql
ALTER TABLE barang_masuk ADD COLUMN dokumen VARCHAR(255) NULLABLE;
```

### Extended Fields (Migration: 2026_03_04_000008)
```sql
ALTER TABLE barang_masuk ADD COLUMN (
    satuan VARCHAR(50) NULLABLE,
    sisa_stok INTEGER NULLABLE,
    kepemilikan VARCHAR(100) NULLABLE,
    status VARCHAR(100) NULLABLE,
    posisi VARCHAR(255) NULLABLE,
    tahun_pengadaan VARCHAR(10) NULLABLE,
    barang_masuk VARCHAR(100) NULLABLE,
    barang_keluar VARCHAR(100) NULLABLE
);
```

### Date Tracking (Migration: 2026_03_04_000009)
```sql
ALTER TABLE barang_masuk ADD COLUMN tanggal_keluar DATE NULLABLE;
```

### Complete Table Schema
| Field | Type | Properties | Notes |
|-------|------|-----------|-------|
| id | BIGINT UNSIGNED | PK, AI | Primary key |
| nomor_barang | VARCHAR(255) | UNIQUE | Item reference number |
| nama_barang | VARCHAR(255) | Required | Item name |
| kategori | VARCHAR(255) | Required | Category/Type |
| jumlah | INTEGER | Required | Quantity received |
| stok | INTEGER | DEFAULT 0 | Current stock |
| cabang_id | BIGINT UNSIGNED | FK, Nullable | Branch association |
| tanggal_masuk | DATE | Required | Receipt date |
| tanggal_keluar | DATE | Nullable | Exit date |
| satuan | VARCHAR(50) | Nullable | Unit of measurement |
| sisa_stok | INTEGER | Nullable | Remaining stock |
| kepemilikan | VARCHAR(100) | Nullable | Ownership |
| status | VARCHAR(100) | Nullable | Status |
| posisi | VARCHAR(255) | Nullable | Location/Position |
| tahun_pengadaan | VARCHAR(10) | Nullable | Procurement year |
| barang_masuk | VARCHAR(100) | Nullable | Inbound reference |
| barang_keluar | VARCHAR(100) | Nullable | Outbound reference |
| dokumen | VARCHAR(255) | Nullable | Document file (pdf/doc) |
| keterangan | TEXT | Nullable | Notes/Description |
| created_at | TIMESTAMP | | System timestamp |
| updated_at | TIMESTAMP | | System timestamp |

---

## 5. MODEL

### Location
**File**: [app/Models/BarangMasuk.php](app/Models/BarangMasuk.php)

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    
    protected $fillable = [
        'nomor_barang',
        'nama_barang',
        'kategori',
        'jumlah',
        'stok',
        'cabang_id',
        'tanggal_masuk',
        'keterangan',
        'dokumen',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    // Relationships
    public function distribusi()
    {
        return $this->hasMany(DistribusiBarang::class, 'barang_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class, 'barang_masuk_id');
    }
}
```

---

## 6. VIEW/FORM FOR IMPORT

### Location
**File**: [resources/views/barang_masuk/import.blade.php](resources/views/barang_masuk/import.blade.php)

```blade
@extends('layout')

@section('title', 'Import Barang Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-file-earmark-arrow-up"></i> Import Barang Masuk</h1>
    <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary btn-custom">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('barang-masuk.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Pilih file Excel (xlsx, xls, csv)</label>
                <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" required>
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Format kolom: nomor_barang, nama_barang, kategori, jumlah, tanggal_masuk, keterangan (header optional)</div>
            </div>

            <button type="submit" class="btn btn-primary btn-custom">
                <i class="bi bi-upload"></i> Import
            </button>
        </form>
    </div>
</div>
@endsection
```

**Supported Formats**: .xlsx, .xls, .csv

---

## 7. IMPORT FIELDS & VALIDATION

### Excel Column Mapping
| Excel Header (Normalized) | Database Field | Required | Type | Notes |
|---------------------------|----------------|----------|------|-------|
| `no` | nomor_barang | No | string | Item number |
| `namaperangkat` | nama_barang | ✓ YES | string | Item name (REQUIRED) |
| `merk/type` | kategori | No | string | Category/Type |
| `qty` | jumlah | ✓ YES | integer | Quantity (REQUIRED) |
| `sisastok` | stok | No | integer | Stock balance |
| `keterangan` | keterangan | No | text | Notes |

### Validation Rules

**File Validation**:
```php
$request->validate([
    'file' => 'required|file|mimes:xlsx,xls,csv',
]);
```

**Row Validation** (during import):
```php
// Required fields must not be empty
if (empty($data['nama_barang']) || empty($data['jumlah'])) continue;
```

**Auto-filled Fields**:
- `tanggal_masuk` - Set to today (date('Y-m-d'))
- `cabang_id` - Set to authenticated user's cabang

**Deduplication**:
- Uses `updateOrCreate()` based on `nomor_barang` (unique constraint)
- If `nomor_barang` exists, the record is updated instead of duplicated

---

## 8. IMPORT WORKFLOW

```
┌─────────────────────────────────┐
│ 1. User accesses /barang-masuk  │
│    /import (GET)                 │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 2. Display import form           │
│    (Blade template)              │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 3. User selects Excel file and  │
│    submits (POST)                │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 4. Controller validates file     │
│    (mimes: xlsx, xls, csv)       │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 5. Load Excel with PhpSpreadsheet│
│    Read all rows and headers     │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 6. Normalize headers             │
│    (lowercase, remove spaces)    │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 7. Validate required columns     │
│    (namaperangkat, qty required) │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 8. Loop through data rows        │
│    Map Excel → DB fields         │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 9. Validate each row             │
│    (nama_barang & jumlah not null)
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 10. Insert/Update via            │
│     updateOrCreate()             │
└──────────────┬──────────────────┘
               │
               ▼
┌─────────────────────────────────┐
│ 11. Redirect with summary        │
│     (success/error message)      │
└─────────────────────────────────┘
```

---

## 9. ERROR HANDLING

**Error Messages**:
- File validation errors: "File must be xlsx, xls, or csv"
- Missing cabang: "User tidak memiliki cabang yang valid. Hubungi admin."
- Missing required columns: "Kolom wajib tidak ditemukan di file Excel: [column]"
- No data imported: "Tidak ada data yang berhasil diimport."
- Row-level errors: "Baris [X]: [Exception message]"

**Error Tracking**:
- All row-level errors are collected in `$errors[]` array
- Error messages include row number and exception details
- Final message shows total imported count and any failures

---

## 10. TOOL SCRIPTS FOR IMPORT

### Script 1: Direct Excel Import (Advanced)
**File**: [tools/import_barang_masuk.php](tools/import_barang_masuk.php)

**Purpose**: CLI script to import Excel directly (bypassing web UI)

```php
// Configuration
$excelFile = __DIR__ . '/../public/storage/distribusi/data.xlsx';
$dbHost = 'localhost';
$dbName = 'simjar_db';
$dbUser = 'root';
$dbPass = '';

// Usage: php tools/import_barang_masuk.php
```

**Mapping**:
- Column B: nama_barang (NAMA PERANGKAT)
- Column C: kategori (MERK/TYPE)
- Column D: jumlah (QTY)
- Column F: stok (SISA STOK)
- Column K: keterangan (KETERANGAN)

---

### Script 2: Extended Excel Import (with more fields)
**File**: [tools/import_barang_masuk_excel.php](tools/import_barang_masuk_excel.php)

**Purpose**: Import Excel with extended fields (12 columns)

```php
// Mapped Fields:
// B: nama_perangkat
// C: merk_type
// D: qty
// E: satuan
// F: sisa_stok
// G: kepemilikan
// H: status
// I: posisi
// J: tahun_pengadaan
// K: keterangan
// L: barang_masuk
// M: barang_keluar
```

---

### Script 3: Data Migration from Staging Table
**File**: [tools/migrate_excel_to_barang_masuk.php](tools/migrate_excel_to_barang_masuk.php)

**Purpose**: Migrate data from `barang_masuk_excel` staging table to `barang_masuk`

**Workflow**:
1. Reads all rows from `barang_masuk_excel`
2. Maps fields to `barang_masuk` schema
3. Generates unique `nomor_barang` (prefix: 'BRG-')
4. Sets `tanggal_masuk` to today
5. Inserts complete record with all extended fields

---

## 11. IMPORT WORKFLOW STEP-BY-STEP

### Step 1: Access Import Form
```
URL: GET /barang-masuk/import
Returns: Import form view (select Excel file)
```

### Step 2: Submit File
```
URL: POST /barang-masuk/import
Method: multipart/form-data
Payload: file (Excel file)
```

### Step 3: File Processing
1. Validates file format (xlsx/xls/csv)
2. Loads spreadsheet with `IOFactory::load()`
3. Extracts all rows and normalizes headers

### Step 4: Column Validation
1. Normalizes header names (lowercase, remove spaces)
2. Checks required columns exist: `namaperangkat`, `qty`

### Step 5: Data Mapping
```php
'no' → nomor_barang
'namaperangkat' → nama_barang
'merk/type' → kategori
'qty' → jumlah
'sisastok' → stok
'keterangan' → keterangan
```

### Step 6: Row Processing
For each data row:
1. Skip if completely empty
2. Map Excel columns to database fields
3. Trim whitespace from values
4. Set auto-fields (tanggal_masuk, cabang_id)
5. Validate (nama_barang & jumlah not empty)
6. Insert/Update using `updateOrCreate()`

### Step 7: Results
- Counter incremented for successful imports
- Errors collected per row
- Redirect with message: "Import selesai. [X] data berhasil diimport."

---

## 12. SUMMARY OF IMPORT FILES

| File | Type | Purpose |
|------|------|---------|
| [routes/web.php](routes/web.php#L62-L63) | Route | Import routes (GET/POST) |
| [app/Http/Controllers/BarangMasukController.php](app/Http/Controllers/BarangMasukController.php#L233-L320) | Controller | Import logic & processing |
| [resources/views/barang_masuk/import.blade.php](resources/views/barang_masuk/import.blade.php) | View | Import form UI |
| [app/Models/BarangMasuk.php](app/Models/BarangMasuk.php) | Model | Data model & relationships |
| [database/migrations/2024_01_01_000001_create_barang_masuk_table.php](database/migrations/2024_01_01_000001_create_barang_masuk_table.php) | Migration | Base table schema |
| [database/migrations/2026_02_15_000005_add_stok_to_barang_masuk.php](database/migrations/2026_02_15_000005_add_stok_to_barang_masuk.php) | Migration | Add stock field |
| [database/migrations/2026_02_15_000006_add_cabang_id_to_barang_masuk.php](database/migrations/2026_02_15_000006_add_cabang_id_to_barang_masuk.php) | Migration | Add branch association |
| [database/migrations/2026_02_24_000007_add_dokumen_to_barang_masuk_table.php](database/migrations/2026_02_24_000007_add_dokumen_to_barang_masuk_table.php) | Migration | Add document support |
| [database/migrations/2026_03_04_000008_add_excel_fields_to_barang_masuk.php](database/migrations/2026_03_04_000008_add_excel_fields_to_barang_masuk.php) | Migration | Add extended fields |
| [database/migrations/2026_03_04_000009_add_tanggal_keluar_to_barang_masuk.php](database/migrations/2026_03_04_000009_add_tanggal_keluar_to_barang_masuk.php) | Migration | Add exit date tracking |
| [tools/import_barang_masuk.php](tools/import_barang_masuk.php) | Tool | CLI import script (basic) |
| [tools/import_barang_masuk_excel.php](tools/import_barang_masuk_excel.php) | Tool | CLI import script (extended) |
| [tools/migrate_excel_to_barang_masuk.php](tools/migrate_excel_to_barang_masuk.php) | Tool | Staging table migration |
| [composer.json](composer.json) | Config | Excel library: phpoffice/phpspreadsheet |

---

## 13. QUICK REFERENCE: IMPORT REQUIREMENTS

### User Requirements
- Must be authenticated
- Must have a valid `cabang_id` assigned
- Role: Any authenticated user can access import (no specific role restriction)

### File Requirements
- Format: .xlsx, .xls, or .csv
- Must contain at least columns: `namaperangkat`, `qty`
- Header row expected but header names are flexible (normalized)

### Data Requirements
- **nama_barang**: Required (from Excel column: namaperangkat)
- **jumlah**: Required (from Excel column: qty)
- **nomor_barang**: Optional (from Excel column: no)
- **kategori**: Optional (from Excel column: merk/type)
- **stok**: Optional (from Excel column: sisastok)
- **keterangan**: Optional

### Automatic Values During Import
- `tanggal_masuk`: Today's date
- `cabang_id`: User's branch
- `created_at`, `updated_at`: Current timestamp

---

## 14. DEDUPLICATION & UPSERT LOGIC

The import uses Laravel's `updateOrCreate()` method:

```php
BarangMasuk::updateOrCreate(
    ['nomor_barang' => $data['nomor_barang']],  // Search criterion
    $data                                        // Data to insert/update
);
```

**Behavior**:
- If `nomor_barang` exists: Updates all fields
- If `nomor_barang` doesn't exist: Creates new record
- Prevents duplicate `nomor_barang` values
- Allows re-importing with updated data

---

## 15. INTEGRATION WITH OTHER MODULES

### Related Models
- **DistribusiBarang** - References BarangMasuk.id via `barang_id`
- **SerialNumber** - References BarangMasuk.id via `barang_masuk_id`
- **Cabang** - Referenced by BarangMasuk.cabang_id

### Related Routes
- `/barang-masuk` - List view with search
- `/barang-masuk/create` - Manual entry form
- `/barang-masuk/{id}` - View details
- `/barang-masuk/{id}/edit` - Edit item
- `/barang-masuk/{id}/pdf-laporan` - Export report
- `/distribusi-barang` - Distribution module (uses imported items)

---

## 16. TESTING & DEBUG FILES

The project includes several test files in [routes/](routes/):
- `test-check.php`
- `test-sum.php`
- `test-setup.php`
- `test-dashboard-final.php`
- `debug.php`
- `test-dashboard.php`
- `debug-barang.php`

These test routes are loaded for development purposes in [routes/web.php](routes/web.php).

---

## Summary

The SIMJAR import functionality provides:
✓ Web UI for Excel import  
✓ Flexible column mapping  
✓ Automatic data normalization  
✓ Comprehensive error handling  
✓ Upsert (update/create) logic  
✓ CLI scripts for bulk operations  
✓ Full audit trail (created_at, updated_at)  
✓ Multi-branch support  
✓ Extended field support (satuan, status, posisi, etc.)
