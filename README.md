# SIMJAR - Sistem Informasi Manajemen Jaringan

Aplikasi manajemen jaringan berbasis web yang dibangun dengan Laravel 10, MySQL, dan Bootstrap.

## Fitur Utama

### 1. Dashboard
- Total barang masuk
- Total perangkat aktif
- Total perangkat tidak aktif
- Grafik perangkat per bulan

### 2. Modul Barang Masuk
- ✓ Tambah data barang
- ✓ Edit data barang
- ✓ Hapus data barang
- ✓ Lihat detail barang
- ✓ Export PDF

### 3. Modul Inventaris Jaringan
- ✓ Tambah perangkat jaringan
- ✓ Edit data perangkat
- ✓ Nonaktifkan perangkat
- ✓ Log aktivitas perangkat
- ✓ Filter berdasarkan lokasi

## Teknologi yang Digunakan

- **Framework**: Laravel 10
- **Database**: MySQL
- **Frontend**: Bootstrap 5
- **Chart**: Chart.js
- **PDF Export**: DomPDF
- **Server**: XAMPP (Apache + MySQL + PHP)

## Instalasi

### Prasyarat
- XAMPP dengan PHP 8.1+
- Composer
- MySQL/MariaDB

### Langkah-langkah Setup

#### 1. Download Composer Dependencies
```bash
cd c:\xampp\htdocs\Simjar_dispusip
composer install
```

#### 2. Setup Database

**Buat database baru:**
```bash
mysql -u root
CREATE DATABASE simjar_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### 3. Generate APP Key
```bash
php artisan key:generate
```

#### 4. Run Migrations
```bash
php artisan migrate
```

Perintah ini akan membuat tabel-tabel berikut:
- `barang_masuk` - Data barang yang masuk
- `perangkat_jaringan` - Data inventaris perangkat
- `activity_logs` - Log aktivitas perangkat

#### 5. Run Seeder (Opsional - untuk sample data)
```bash
php artisan db:seed
```

#### 6. Link Storage
```bash
php artisan storage:link
```

### Akses Aplikasi

Buka browser dan akses:
```
http://localhost/Simjar_dispusip/public
```

Atau jika sudah dikonfigurasi di Apache virtual host:
```
http://simjar.local
```

## Struktur Folder

```
Simjar_dispusip/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── DashboardController.php
│   │       ├── BarangMasukController.php
│   │       └── PerangkatJaringanController.php
│   └── Models/
│       ├── BarangMasuk.php
│       ├── PerangkatJaringan.php
│       └── ActivityLog.php
├── config/
│   ├── database.php
│   ├── filesystems.php
│   └── logging.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       ├── layout.blade.php
│       ├── dashboard.blade.php
│       ├── barang_masuk/
│       └── perangkat_jaringan/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
├── storage/
│   ├── logs/
│   └── framework/
├── bootstrap/
│   └── app.php
└── public/
    └── index.php
```

## Database Schema

### Tabel: barang_masuk
```sql
- id (Primary Key)
- nomor_barang (Unique)
- nama_barang
- kategori
- jumlah
- tanggal_masuk
- keterangan
- timestamps
```

### Tabel: perangkat_jaringan
```sql
- id (Primary Key)
- nomor_inventaris (Unique)
- nama_perangkat
- tipe_perangkat
- lokasi
- ip_address
- mac_address
- status (aktif/tidak_aktif)
- tanggal_pemasangan
- keterangan
- timestamps
```

### Tabel: activity_logs
```sql
- id (Primary Key)
- perangkat_id (Foreign Key)
- aktivitas
- deskripsi
- tanggal_aktivitas
- timestamps
```

## Routes

### Dashboard
- `GET /` - Tampilan dashboard utama

### Barang Masuk
- `GET /barang-masuk` - Daftar barang masuk
- `GET /barang-masuk/create` - Form tambah barang
- `POST /barang-masuk` - Simpan barang baru
- `GET /barang-masuk/{id}` - Detail barang
- `GET /barang-masuk/{id}/edit` - Form edit barang
- `PUT /barang-masuk/{id}` - Update barang
- `DELETE /barang-masuk/{id}` - Hapus barang
- `GET /barang-masuk/export/pdf` - Export PDF

### Perangkat Jaringan
- `GET /perangkat-jaringan` - Daftar perangkat
- `GET /perangkat-jaringan/create` - Form tambah perangkat
- `POST /perangkat-jaringan` - Simpan perangkat baru
- `GET /perangkat-jaringan/{id}` - Detail perangkat
- `GET /perangkat-jaringan/{id}/edit` - Form edit perangkat
- `PUT /perangkat-jaringan/{id}` - Update perangkat
- `POST /perangkat-jaringan/{id}/deactivate` - Nonaktifkan perangkat
- `GET /perangkat-jaringan/{id}/activity-log` - Log aktivitas perangkat

## Fitur Utama Aplikasi

### 1. Dashboard
- Menampilkan statistik ringkas
- Grafik perangkat yang dipasang per bulan
- Akses cepat ke modul-modul

### 2. Barang Masuk
- CRUD lengkap (Create, Read, Update, Delete)
- Export data ke PDF
- Kategori barang (Router, Switch, Modem, Access Point, Kabel, Connector, dll)

### 3. Inventaris Jaringan
- Manajemen perangkat jaringan
- Tracking status perangkat (aktif/tidak aktif)
- Fitur filter berdasarkan lokasi
- Log aktivitas otomatis setiap perubahan
- Tracking IP dan MAC Address

### 4. Log Aktivitas
- Catat setiap perubahan pada perangkat
- Timestamp otomatis
- Deskripsi aktivitas detail

## Kustomisasi

### Mengubah Styling
Edit file `resources/views/layout.blade.php` untuk mengubah warna dan styling Bootstrap.

### Menambah Kategori Barang
Edit di controller atau tambahkan di migration untuk kolom kategori.

### Menambah Tipe Perangkat
Modifikasi select options di view `perangkat_jaringan/create.blade.php` dan `edit.blade.php`.

## Troubleshooting

### Error: "No database selected"
- Pastikan database `simjar_db` sudah dibuat
- Check konfigurasi `.env` file

### Error: "SQLSTATE[HY000]: General error"
- Run migration ulang: `php artisan migrate:refresh`
- Check migrations files

### CSS/JS tidak loading
- Pastikan public folder dapat diakses
- Run: `php artisan storage:link`

### Export PDF tidak bekerja
- Install package: `composer require barryvdh/laravel-dompdf`
- Clear cache: `php artisan cache:clear`

## Support & Bantuan

Untuk bantuan lebih lanjut, silakan hubungi tim IT.

## License

Copyright © 2024 SIMJAR. All rights reserved.
