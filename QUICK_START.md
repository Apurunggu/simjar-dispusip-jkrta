# QUICK START - SIMJAR

## 3 Langkah Cepat untuk Jalankan Aplikasi

### 1️⃣ Buka Command Prompt dan Navigasi ke Project
```bash
cd c:\xampp\htdocs\Simjar_dispusip
```

### 2️⃣ Install Dependencies
```bash
composer install
```

### 3️⃣ Setup Database & Jalankan

#### 3a. Via Command (Rekomendasi)
```bash
php artisan migrate --seed
```

#### 3b. Via phpMyAdmin (Manual)
1. Buka: http://localhost/phpmyadmin
2. Klik "Databases"
3. Buat database: `simjar_db`
4. Import file: `database/simjar_db.sql`

---

## ✅ Akses Aplikasi

Buka browser dan akses salah satu:

**Pilihan 1: Dengan Artisan Server**
```bash
php artisan serve
```
Akses: http://127.0.0.1:8000

**Pilihan 2: Dengan XAMPP (Recommended)**
- Pastikan Apache & MySQL running
- Akses: http://localhost/Simjar_dispusip/public

**Pilihan 3: Dengan Virtual Host**
- Akses: http://simjar.local
- (Setup di `INSTALLATION.md`)

---

## 🎯 Fitur yang Bisa Langsung Dicoba

### Dashboard
- Lihat statistik barang dan perangkat
- Grafik perangkat per bulan

### Barang Masuk
- ➕ Tambah barang baru
- ✏️ Edit barang
- 🗑️ Hapus barang
- 📄 Export ke PDF
- 👁️ Lihat detail barang

### Perangkat Jaringan
- ➕ Tambah perangkat baru
- ✏️ Edit perangkat
- 🔴 Nonaktifkan perangkat
- 📍 Filter berdasarkan lokasi
- 📜 Lihat log aktivitas

---

## 📝 Tips

1. **Jika error "Database not found":**
   ```bash
   php artisan migrate --seed
   ```

2. **Jika CSS/JS tidak muncul:**
   ```bash
   php artisan storage:link
   ```

3. **Clear cache jika ada masalah:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

4. **Development server untuk testing cepat:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

---

## 📚 Dokumentasi Lengkap

- Instalasi detail: lihat `INSTALLATION.md`
- Daftar fitur: lihat `README.md`
- Database schema: lihat `database/simjar_db.sql`

---

## 🆘 Butuh Bantuan?

Lihat `INSTALLATION.md` bagian **Troubleshooting** untuk solusi masalah umum.

---

**Enjoy! 🚀**
