# Panduan Instalasi SIMJAR - Sistem Informasi Manajemen Jaringan

## Persyaratan Sistem

- XAMPP (Apache + MySQL + PHP 8.1+)
- Composer
- Browser modern (Chrome, Firefox, Edge, Safari)

## Langkah-Langkah Instalasi

### LANGKAH 1: Persiapan XAMPP

1. **Pastikan XAMPP sudah terinstall**
   - Download dari https://www.apachefriends.org/
   - Pilih versi dengan PHP 8.1 atau lebih tinggi

2. **Jalankan XAMPP Control Panel**
   - Klik tombol "Start" untuk Apache
   - Klik tombol "Start" untuk MySQL

3. **Verifikasi instalasi**
   - Buka browser: `http://localhost`
   - Pastikan halaman XAMPP Welcome tampil

### LANGKAH 2: Setup Database

1. **Buka phpMyAdmin**
   - Akses: `http://localhost/phpmyadmin`

2. **Buat Database Baru**
   - Klik tab "Databases"
   - Di kolom "Create database", ketik: `simjar_db`
   - Pilih "utf8mb4_unicode_ci" sebagai collation
   - Klik "Create"

3. **Import Sample Data (Opsional)**
   - Pilih database `simjar_db`
   - Klik tab "Import"
   - Pilih file: `database/simjar_db.sql`
   - Klik "Import"

### LANGKAH 3: Download & Setup Project

1. **Project sudah berada di:**
   ```
   c:\xampp\htdocs\Simjar_dispusip
   ```

2. **Buka Command Prompt/PowerShell**
   - Tekan `Win + R`
   - Ketik: `cmd`
   - Enter

3. **Navigasi ke folder project**
   ```bash
   cd c:\xampp\htdocs\Simjar_dispusip
   ```

### LANGKAH 4: Install Dependencies

1. **Install Composer packages**
   ```bash
   composer install
   ```
   
   Tunggu sampai selesai. Ini akan mendownload semua library yang diperlukan.

2. **Jika ada error, coba:**
   ```bash
   composer update
   ```

### LANGKAH 5: Konfigurasi Environment

1. **Cek file .env**
   - File sudah ada: `.env`
   - Verify database configuration:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=simjar_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

### LANGKAH 6: Database Migration

1. **Jalankan migration untuk membuat tabel**
   ```bash
   php artisan migrate
   ```

   Output yang diharapkan:
   ```
   Migration table created successfully.
   Migrating: 2024_01_01_000001_create_barang_masuk_table
   Migrated: 2024_01_01_000001_create_barang_masuk_table
   Migrating: 2024_01_01_000002_create_perangkat_jaringan_table
   Migrated: 2024_01_01_000002_create_perangkat_jaringan_table
   Migrating: 2024_01_01_000003_create_activity_logs_table
   Migrated: 2024_01_01_000003_create_activity_logs_table
   ```

2. **Jika ingin sample data**
   ```bash
   php artisan db:seed
   ```

### LANGKAH 7: Link Storage

```bash
php artisan storage:link
```

### LANGKAH 8: Setup Virtual Host (Opsional tapi direkomendasikan)

1. **Edit file hosts**
   - Buka: `C:\Windows\System32\drivers\etc\hosts`
   - Dengan administrator privileges
   - Tambahkan baris:
   ```
   127.0.0.1       simjar.local
   ```
   - Simpan

2. **Edit httpd-vhosts.conf**
   - Buka: `c:\xampp\apache\conf\extra\httpd-vhosts.conf`
   - Tambahkan di akhir:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "c:\xampp\htdocs\Simjar_dispusip\public"
       ServerName simjar.local
       <Directory "c:\xampp\htdocs\Simjar_dispusip\public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. **Restart Apache**
   - Buka XAMPP Control Panel
   - Klik "Stop" untuk Apache
   - Tunggu beberapa detik
   - Klik "Start" untuk Apache

### LANGKAH 9: Akses Aplikasi

**OPSI 1: Tanpa Virtual Host**
```
http://localhost/Simjar_dispusip/public
```

**OPSI 2: Dengan Virtual Host**
```
http://simjar.local
```

Jika berhasil, Anda akan melihat halaman Dashboard SIMJAR.

---

## Verifikasi Instalasi

1. **Cek Database**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Pilih database `simjar_db`
   - Pastikan ada 3 tabel: `barang_masuk`, `perangkat_jaringan`, `activity_logs`

2. **Cek Aplikasi**
   - Akses halaman Dashboard
   - Coba navigasi ke menu Barang Masuk
   - Coba navigasi ke menu Perangkat Jaringan

3. **Test Fungsi**
   - Tambahkan data barang masuk
   - Tambahkan perangkat jaringan
   - Filter perangkat berdasarkan lokasi

---

## Troubleshooting

### Problem: "Database connection refused"

**Solusi:**
1. Pastikan MySQL sudah running di XAMPP
2. Check `.env` file konfigurasi database
3. Buat ulang database di phpMyAdmin
4. Run migration ulang:
   ```bash
   php artisan migrate:refresh
   ```

### Problem: "Class not found" atau "No such file"

**Solusi:**
1. Run composer install lagi:
   ```bash
   composer install --no-interaction --no-plugins --no-scripts --no-dev
   ```
2. Clear cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

### Problem: "Permission denied" di storage folder

**Solusi:**
1. Klik kanan folder `storage` → Properties
2. Security → Edit → Users → Full Control
3. Apply → OK

### Problem: CSS/JS tidak loading

**Solusi:**
1. Jalankan:
   ```bash
   php artisan storage:link
   ```
2. Clear browser cache (Ctrl + Shift + Delete)
3. Buka kembali aplikasi

### Problem: Export PDF tidak bekerja

**Solusi:**
1. Install package DomPDF:
   ```bash
   composer require barryvdh/laravel-dompdf
   ```
2. Clear cache:
   ```bash
   php artisan cache:clear
   ```

### Problem: Route "barang-masuk.index" does not exist

**Solusi:**
1. Clear route cache:
   ```bash
   php artisan route:clear
   ```
2. Check file routes/web.php ada dan lengkap

---

## Command Penting

```bash
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Database operations
php artisan migrate              # Run migrations
php artisan migrate:refresh      # Reset dan re-run migrations
php artisan migrate:rollback     # Rollback migrations

# Generate key
php artisan key:generate

# Link storage
php artisan storage:link

# Tinker (Interactive Shell)
php artisan tinker
```

---

## Struktur Folder Penting

```
Simjar_dispusip/
├── app/                          # Aplikasi code
│   ├── Http/Controllers/         # Controller files
│   └── Models/                   # Model files
├── bootstrap/                    # Bootstrap files
├── config/                       # Konfigurasi aplikasi
├── database/
│   ├── migrations/              # Migration files
│   └── simjar_db.sql            # SQL dump
├── public/                       # Public folder (akses dari web)
│   └── index.php                # Entry point
├── resources/
│   └── views/                   # Blade templates
│       ├── layout.blade.php     # Layout utama
│       ├── dashboard.blade.php
│       ├── barang_masuk/
│       └── perangkat_jaringan/
├── routes/                       # Route definitions
│   ├── web.php                  # Web routes
│   └── api.php                  # API routes
├── storage/                      # File storage
├── .env                         # Environment variables
├── .env.example                 # Environment example
├── composer.json                # Composer configuration
└── README.md                    # Documentation
```

---

## Default Credentials

Aplikasi ini tidak memiliki login/authentication (bisa ditambahkan nanti).
Semua halaman dapat diakses tanpa login.

---

## Dukungan & Kontribusi

Untuk pertanyaan atau masalah, silakan hubungi:
- Tim IT: [contact info]
- Email: support@simjar.local

---

## Next Steps (Opsional untuk Development)

1. **Tambah Authentication:**
   ```bash
   php artisan make:auth
   ```

2. **Tambah Authorization (Roles & Permissions):**
   ```bash
   composer require spatie/laravel-permission
   ```

3. **Setup API dengan Sanctum:**
   ```bash
   php artisan install:api
   ```

4. **Tambah Testing:**
   ```bash
   php artisan make:test
   ```

---

**Selamat! Aplikasi SIMJAR siap digunakan! 🎉**
