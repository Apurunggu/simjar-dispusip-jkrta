# STRUKTUR FOLDER & PENJELASAN FILE

## 📁 Struktur Lengkap Project

```
Simjar_dispusip/
│
├── 📂 app/
│   ├── 📂 Exceptions/
│   │   └── Handler.php                      → Exception handling
│   │
│   ├── 📂 Http/
│   │   └── 📂 Controllers/
│   │       ├── DashboardController.php      → Dashboard logic
│   │       ├── BarangMasukController.php    → Barang masuk CRUD
│   │       └── PerangkatJaringanController.php → Network device CRUD
│   │
│   ├── 📂 Models/
│   │   ├── BarangMasuk.php                  → Model Barang Masuk
│   │   ├── PerangkatJaringan.php            → Model Network Device
│   │   └── ActivityLog.php                  → Model Activity Log
│   │
│   └── 📂 Providers/
│       └── AppServiceProvider.php           → Service provider
│
├── 📂 bootstrap/
│   ├── app.php                              → Application bootstrap
│   └── 📂 cache/                            → Cache directory
│
├── 📂 config/
│   ├── database.php                         → Database config
│   ├── filesystems.php                      → Filesystem config
│   └── logging.php                          → Logging config
│
├── 📂 database/
│   ├── 📂 migrations/
│   │   ├── 2024_01_01_000001_create_barang_masuk_table.php
│   │   ├── 2024_01_01_000002_create_perangkat_jaringan_table.php
│   │   └── 2024_01_01_000003_create_activity_logs_table.php
│   │
│   ├── 📂 seeders/
│   │   └── DatabaseSeeder.php               → Sample data
│   │
│   └── simjar_db.sql                        → SQL dump
│
├── 📂 public/
│   ├── index.php                            → Entry point aplikasi
│   └── .htaccess                            → URL rewriting rules
│
├── 📂 resources/
│   ├── 📂 views/
│   │   ├── layout.blade.php                 → Main layout template
│   │   ├── dashboard.blade.php              → Dashboard view
│   │   │
│   │   ├── 📂 barang_masuk/
│   │   │   ├── index.blade.php              → List barang
│   │   │   ├── create.blade.php             → Form tambah barang
│   │   │   ├── edit.blade.php               → Form edit barang
│   │   │   ├── show.blade.php               → Detail barang
│   │   │   └── pdf.blade.php                → PDF template
│   │   │
│   │   └── 📂 perangkat_jaringan/
│   │       ├── index.blade.php              → List perangkat
│   │       ├── create.blade.php             → Form tambah perangkat
│   │       ├── edit.blade.php               → Form edit perangkat
│   │       ├── show.blade.php               → Detail perangkat
│   │       └── activity_log.blade.php       → Activity log view
│   │
│   ├── 📂 css/
│   │   └── (CSS files akan ada di sini)
│   │
│   └── 📂 js/
│       └── (JavaScript files akan ada di sini)
│
├── 📂 routes/
│   ├── web.php                              → Web routes (Main)
│   ├── api.php                              → API routes
│   └── console.php                          → Console commands
│
├── 📂 storage/
│   ├── 📂 logs/
│   │   └── laravel.log                      → Application logs
│   │
│   └── 📂 framework/
│       ├── (Cache files)
│       ├── (Session files)
│       └── (Views cache)
│
├── 📄 .env                                  → Environment variables (Development)
├── 📄 .env.example                          → Environment template
├── 📄 .htaccess                             → Root .htaccess (redirect to public)
├── 📄 .gitignore                            → Git ignore patterns
├── 📄 artisan                               → Artisan CLI
├── 📄 composer.json                         → PHP dependencies
├── 📄 composer.lock                         → Locked dependencies
├── 📄 package.json                          → NPM dependencies
│
├── 📄 README.md                             → Documentation (English & Indonesian)
├── 📄 README_IND.txt                        → Documentation (Indonesian) - User friendly
├── 📄 START_HERE.txt                        → Quick start guide
├── 📄 QUICK_START.md                        → Quick start (3 steps)
├── 📄 INSTALLATION.md                       → Installation guide + troubleshooting
├── 📄 PROJECT_SUMMARY.md                    → Project summary & features
└── 📄 DEPLOYMENT_CHECKLIST.md               → Pre-launch checklist

```

---

## 📖 PENJELASAN SETIAP FOLDER

### 📂 app/
**Folder utama aplikasi yang berisi business logic**

- **Exceptions/**: Handle error dan exception handling
- **Http/Controllers/**: Semua controller yang handle request
- **Models/**: Model Eloquent untuk database interaction
- **Providers/**: Service provider untuk aplikasi services

### 📂 bootstrap/
**Folder untuk bootstrap aplikasi**

- **app.php**: File bootstrap utama yang di-load pertama kali
- **cache/**: Cache directory untuk aplikasi

### 📂 config/
**Konfigurasi aplikasi**

- **database.php**: Konfigurasi database connection
- **filesystems.php**: Konfigurasi storage
- **logging.php**: Konfigurasi logging system

### 📂 database/
**Semua yang berhubungan dengan database**

- **migrations/**: Migration files untuk membuat tabel
- **seeders/**: Seeder files untuk insert sample data
- **simjar_db.sql**: SQL dump untuk backup/restore database

### 📂 public/
**Folder yang accessible dari web browser**

- **index.php**: Entry point aplikasi (yang di-akses pertama)
- **.htaccess**: File untuk URL rewriting (clean URLs)

### 📂 resources/views/
**Semua template HTML (Blade templates)**

- **layout.blade.php**: Master layout yang di-inherit oleh views lain
- **dashboard.blade.php**: Halaman dashboard
- **barang_masuk/**: Views untuk modul barang masuk
- **perangkat_jaringan/**: Views untuk modul perangkat jaringan

### 📂 routes/
**Definisi routes/URL aplikasi**

- **web.php**: Routes untuk web interface (yang digunakan)
- **api.php**: Routes untuk API (opsional)
- **console.php**: Routes untuk command line

### 📂 storage/
**Folder untuk menyimpan files**

- **logs/**: Application logs
- **framework/**: Cache dan session files

---

## 📄 PENJELASAN SETIAP FILE

### 🔧 Konfigurasi & Setup
| File | Deskripsi |
|------|-----------|
| `.env` | Environment variables (development) |
| `.env.example` | Template .env |
| `composer.json` | PHP package dependencies |
| `package.json` | NPM package dependencies |
| `.gitignore` | Git ignore patterns |
| `artisan` | Command line tool untuk Laravel |

### 📚 Dokumentasi
| File | Deskripsi |
|------|-----------|
| `README.md` | Dokumentasi lengkap |
| `START_HERE.txt` | Panduan untuk mulai |
| `QUICK_START.md` | Setup cepat 3 langkah |
| `INSTALLATION.md` | Panduan instalasi lengkap + troubleshooting |
| `PROJECT_SUMMARY.md` | Ringkasan project dan fitur |
| `DEPLOYMENT_CHECKLIST.md` | Checklist sebelum launch |

---

## 🎯 ALUR KERJA REQUEST

```
User Request (Browser)
         ↓
    public/index.php (Entry Point)
         ↓
    bootstrap/app.php (Initialize App)
         ↓
    routes/web.php (Route matching)
         ↓
    app/Http/Controllers/* (Handle request)
         ↓
    app/Models/* (Query database)
         ↓
    resources/views/* (Render template)
         ↓
    Response ke Browser
```

---

## 🗄️ DATABASE RELATIONSHIPS

```
barang_masuk (Table)
    ├── id (PK)
    ├── nomor_barang (UNIQUE)
    ├── nama_barang
    ├── kategori
    ├── jumlah
    ├── tanggal_masuk
    └── keterangan

perangkat_jaringan (Table)
    ├── id (PK)
    ├── nomor_inventaris (UNIQUE)
    ├── nama_perangkat
    ├── tipe_perangkat
    ├── lokasi
    ├── ip_address
    ├── mac_address
    ├── status (aktif/tidak_aktif)
    ├── tanggal_pemasangan
    └── keterangan
         ↓
    activity_logs (Table) ← Foreign Key relationship
        ├── id (PK)
        ├── perangkat_id (FK)
        ├── aktivitas
        ├── deskripsi
        └── tanggal_aktivitas
```

---

## 🔌 CARA MENAMBAH FITUR BARU

### 1. Tambah Route di `routes/web.php`
```php
Route::get('/feature', [FeatureController::class, 'index'])->name('feature.index');
```

### 2. Buat Controller
```bash
php artisan make:controller FeatureController
```

### 3. Buat Model (jika perlu)
```bash
php artisan make:model Feature -m
```

### 4. Buat Migration (jika perlu)
```bash
php artisan make:migration create_features_table
php artisan migrate
```

### 5. Buat View di `resources/views/`
```php
@extends('layout')
@section('content')
    <!-- View content -->
@endsection
```

---

## 📊 STRUKTUR MIGRATION

Setiap migration file berisi:
- **up()**: Kode untuk membuat tabel/kolom
- **down()**: Kode untuk rollback/menghapus

Contoh:
```php
Schema::create('barang_masuk', function (Blueprint $table) {
    $table->id();
    $table->string('nomor_barang')->unique();
    $table->timestamps();
});
```

---

## 🎨 STRUKTUR BLADE TEMPLATE

File `layout.blade.php` adalah master template yang di-inherit oleh view lain.

Contoh inheritance:
```php
@extends('layout')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
@endsection

@section('scripts')
    <script>
        // JavaScript code
    </script>
@endsection
```

---

## ⚙️ KONFIGURASI PENTING

### File: .env
```
DB_DATABASE=simjar_db
DB_USERNAME=root
DB_PASSWORD=
DB_HOST=127.0.0.1
APP_DEBUG=true
APP_URL=http://localhost/Simjar_dispusip
```

### File: config/database.php
- Mengatur koneksi database
- Biasanya tidak perlu di-edit, gunakan .env saja

---

## 📝 NAMING CONVENTIONS

Dalam Laravel, ada beberapa konvensi naming:

| Item | Format | Contoh |
|------|--------|---------|
| Table | plural | barang_masuk, perangkat_jaringan |
| Model | singular, PascalCase | BarangMasuk, PerangkatJaringan |
| Controller | PascalCase + Controller | DashboardController |
| Migration | snake_case | create_barang_masuk_table |
| Route name | snake_case | barang-masuk.index |
| Variable | camelCase | $barangMasuk, $perangkatJaringan |

---

## 🔍 DEBUGGING

### Melihat Logs
```bash
tail -f storage/logs/laravel.log
```

### Menggunakan Tinker (Interactive Shell)
```bash
php artisan tinker

>>> App\Models\BarangMasuk::all();
>>> App\Models\PerangkatJaringan::count();
```

### Debug di View
```php
{{ dump($variable) }}
{{ dd($variable) }} // dump dan die
```

---

## 📦 COMPOSER COMMANDS

```bash
composer install                # Install dependencies
composer update                 # Update packages
composer require package/name   # Add new package
composer remove package/name    # Remove package
```

---

## 🚀 LARAVEL COMMANDS

```bash
php artisan list                           # List all commands
php artisan make:controller Name           # Create controller
php artisan make:model Name -m             # Create model + migration
php artisan make:migration create_table    # Create migration
php artisan migrate                        # Run migrations
php artisan migrate:refresh                # Reset migrations
php artisan db:seed                        # Run seeders
php artisan cache:clear                    # Clear cache
php artisan route:clear                    # Clear route cache
php artisan view:clear                     # Clear view cache
php artisan tinker                         # Interactive shell
php artisan serve                          # Start dev server
```

---

## 📚 RESOURCE LINKS

- **Laravel Documentation**: https://laravel.com/docs
- **Blade Templates**: https://laravel.com/docs/10.x/blade
- **Eloquent ORM**: https://laravel.com/docs/10.x/eloquent
- **Controllers**: https://laravel.com/docs/10.x/controllers
- **Routing**: https://laravel.com/docs/10.x/routing
- **Bootstrap 5**: https://getbootstrap.com/docs/5.0
- **Chart.js**: https://www.chartjs.org

---

## ✨ PROJECT COMPLETE

Semua file dan folder sudah tersedia dan siap digunakan!

Selamat coding! 🚀
