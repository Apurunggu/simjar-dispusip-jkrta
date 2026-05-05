# 📥 PANDUAN LENGKAP IMPORT EXCEL BARANG MASUK

## ✅ Status: Import Sudah Diperbaiki & Berfungsi 100%

---

## 🔧 Masalah yang Sudah Diperbaiki

### 1. **Error: Kolom nomor_barang tidak boleh kosong (NULL)**
**Masalah**: Saat import, jika user tidak memberikan nomor barang, akan error
**Penyebab**: Kolom `nomor_barang` memiliki constraint NOT NULL di database
**Solusi yang diterapkan**: 
- ✅ System sekarang auto-generate nomor_barang dengan format: `BRG-[KATEGORI]-[NOMOR]`
- Contoh: `BRG-NET-0001`, `BRG-ZTE-0015`, `BRG-CIS-0023`
- User tidak perlu mengisi nomor barang

### 2. **Error: Kolom kategori dan stok tidak boleh kosong**
**Masalah**: Jika user tidak memberikan kategori/stok, import gagal
**Solusi yang diterapkan**:
- ✅ Kategori otomatis di-set ke "Uncategorized" jika kosong
- ✅ Stok otomatis di-set sama dengan Jumlah jika kosong
- Ini logic yang masuk akal: stok awal = jumlah barang masuk

### 3. **Problem: Duplikat dihasilkan saat import berulang**
**Masalah**: Import data yang sama 2x menghasilkan 2 record terpisah
**Solusi yang diterapkan**:
- ✅ System sekarang deteksi duplikat berdasarkan: `nama_barang + kategori`
- ✅ Jika sudah ada, akan **UPDATE** bukan INSERT BARU
- ✅ Data lama akan diupdate dengan data baru

---

## 📋 FORMAT KOLOM EXCEL YANG BENAR (UPDATED)

| No | Kolom Excel | Wajib | Jenis | Contoh |
|-------|-----------|-------|-------|---------|
| 1 | `namaperangkat` | ✓ **YA** | Text | Router TP-Link TL-WR840N |
| 2 | `qty` | ✓ **YA** | Angka | 5 |
| 3 | `no` | ✗ Tidak | Text | R001 |
| 4 | `merk/type` | ✗ Tidak | Text | TP-Link |
| 5 | `sisastok` | ✗ Tidak | Angka | 5 |
| 6 | `keterangan` | ✗ Tidak | Text | Stock baru |

### ⚠️ PERHATIAN PENTING:
```
✓ Hanya 2 KOLOM WAJIB: namaperangkat dan qty
✓ Kolom lain OPTIONAL - system akan auto-fill
✓ Nomor barang otomatis di-generate
✓ Stok otomatis = Jumlah jika tidak diberikan
✓ Kategori otomatis = "Uncategorized" jika tidak diberikan
✓ Deteksi duplikat berdasarkan nama_barang + kategori
✓ Import ulang akan UPDATE, bukan INSERT BARU
```

---

## 🚀 LANGKAH-LANGKAH IMPORT (UPDATED)

### Step 1: Siapkan File Excel
Opsi A - Minimal (hanya nama + qty):
```
namaperangkat | qty
---|---
Router TP-Link | 10
Switch Netgear | 5
Modem ZTE | 3
```

Opsi B - Lengkap (semua data):
```
no    | namaperangkat        | merk/type | qty | sisastok | keterangan
------|----------------------|-----------|-----|----------|------------------
R001  | Router TP-Link       | TP-Link   | 10  | 10       | Stock baru
S001  | Switch Netgear       | Netgear   | 5   | 5        | Pembelian
M001  | Modem ZTE           | ZTE       | 3   | 3        | Replacement
```

### Step 2: Login & Buka Import
1. Buka aplikasi SIMJAR
2. Login dengan akun Anda
3. Navigasi ke: **Barang Masuk** → **Import**
4. Klik **"Download Template Excel"** untuk referensi format

### Step 3: Upload File
1. Klik **"Pilih file Excel"**
2. Pilih file Excel yang sudah diisi data
3. Klik tombol **"Import"**
4. Tunggu proses selesai

### Step 4: Verifikasi Hasil
1. Jika berhasil, akan muncul pesan: **"Import selesai. X data berhasil diimport"**
2. Klik **"Barang Masuk"** untuk melihat data
3. Cari item yang baru diimport
4. Sistem akan auto-generate:
   - **Nomor Barang**: BRG-[KATEGORI]-[NOMOR]
   - **Stok**: Sama dengan Jumlah
   - **Tanggal Masuk**: Hari ini
   - **Kategori**: Dari Excel atau "Uncategorized"

---

## ✅ CHECKLIST SEBELUM IMPORT

- [ ] File dalam format .xlsx atau .xls
- [ ] Kolom `namaperangkat` ada (wajib)
- [ ] Kolom `qty` ada (wajib)
- [ ] Kolom `namaperangkat` tidak ada yang kosong
- [ ] Kolom `qty` berisi angka
- [ ] Minimal 2 kolom (namaperangkat + qty), maksimal 6 kolom
- [ ] Data mulai dari baris ke-2 (baris 1 adalah header)

---

## 💡 CONTOH SKENARIO

### Skenario 1: Import Baru
```
File: import_januari.xlsx
- Router Cisco (qty 5)
- Switch Netgear (qty 3)

Hasil:
✓ 2 records dibuat
✓ Nomor otomatis: BRG-CIS-0001, BRG-NET-0001
✓ Stok otomatis: 5 dan 3
```

### Skenario 2: Update (Re-import Sama)
```
File: import_januari.xlsx (diimport lagi)
- Router Cisco (qty 5)  <- SAMA
- Switch Netgear (qty 3) <- SAMA

Hasil:
✓ Tidak ada duplikat
✓ Data sudah ada tetap 1 record
✓ Hanya update waktu last_modified
```

### Skenario 3: Update dengan Data Baru
```
File: import_januari_update.xlsx
- Router Cisco (qty 10) <- QTY BERUBAH dari 5 jadi 10
- Switch Netgear (qty 3) <- SAMA

Hasil:
✓ Router Cisco di-update: qty menjadi 10, stok menjadi 10
✓ Switch Netgear tetap: qty 3, stok 3
```

---

## 🔍 TROUBLESHOOTING

### ❌ Error: "Kolom wajib tidak ditemukan"
**Solusi**:
1. Pastikan ada kolom: `namaperangkat` dan `qty` (huruf kecil)
2. Download template untuk format yang benar
3. Copy-paste nama kolom dari template

###  ❌ Error: "Tidak ada data yang berhasil diimport"
**Solusi**:
1. Pastikan ada minimal 1 data di baris ke-2
2. Kolom `namaperangkat` tidak boleh kosong
3. Kolom `qty` harus berisi angka (bukan teks)

### ❌ File tidak bisa diupload
**Solusi**:
1. Format file: .xlsx, .xls, atau .csv
2. Ukuran file maksimal: 5MB
3. Jangan rename file dengan karakter khusus

### ❓ Nomor barang tidak sesuai yang saya mau
**Penjelasan**:
- Nomor barang auto-generated dengan format `BRG-[KATEGORI]-[NOMOR]`
- Jika ingin custom nomor, isi kolom `no` di Excel
- Contoh: isi kolom `no` dengan: `R001`, `S001`, `M001`

### ✅ Data berhasil import, tapi ingin update
**Cara**:
1. Edit file Excel dengan data baru
2. Jaga `namaperangkat` dan `kategori` sama
3. Import lagi dengan file yang sudah diupdate
4. System otomatis akan UPDATE record yang ada

---

## 📥 TEKNOLOGI & SPESIFIKASI

- **Library**: PhpOffice\PhpSpreadsheet ^5.4
- **Supported Formats**: .xlsx (Excel 2007+), .xls (Excel 97-2003), .csv
- **Max File Size**: 5MB
- **Authentication**: Required (login dahulu)
- **Multi-user**: Safe (setiap user import ke cabang mereka)
- **Duplicate Detection**: Berdasarkan nama_barang + kategori
- **Auto-Generation**: nomor_barang, stok, kategori

---

## 🎯 FITUR YANG SUDAH DIPERBAIKI

| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Upload Excel | ✅ | Mendukung .xlsx, .xls, .csv |
| Validasi Kolom | ✅ | Cek kolom wajib otomatis |
| Auto Nomor | ✅ | Format: BRG-[KATEGORI]-[NOMOR] |
| Auto Stok | ✅ | Set to Jumlah jika kosong |
| Auto Kategori | ✅ | Set to "Uncategorized" jika kosong |
| Duplicate Detection | ✅ | Berdasarkan nama + kategori |
| Update Logic | ✅ | Tidak membuat duplikat |
| Error Handling | ✅ | Pesan error yang jelas |
| Template Download | ✅ | Tombol download template |
| Instruksi Lengkap | ✅ | UI menunjukkan format yang benar |

---

## 💬 KESIMPULAN

Import Excel sekarang **FULLY FUNCTIONAL** dengan:
- ✅ Auto-fill kolom yang missing
- ✅ Smart duplicate detection
- ✅ Automatic nomor generation
- ✅ Intelligent update logic
- ✅ User-friendly error messages
- ✅ Complete documentation

**Cukup upload file Excel dengan nama barang + quantity, sistem akan handle sisanya!**

---

**Last Updated**: 22 April 2026  
**Version**: 2.0 (Updated & Fully Working)  
**Status**: ✅ READY FOR PRODUCTION



---

## 🔧 Masalah yang Mungkin Dihadapi

### 1. **Kolom Header Tidak Sesuai**
**Masalah**: Import gagal karena kolom Excel tidak sesuai format
**Solusi**: Gunakan nama kolom yang **TEPAT** dan **HURUF KECIL**

### 2. **Validasi Kolom Wajib**
**Masalah**: "Kolom wajib tidak ditemukan"
**Penyebab**: Kolom `namaperangkat` atau `qty` tidak ada
**Solusi**: Pastikan ada kolom dengan nama **TEPAT**:
- `namaperangkat` (nama barang)
- `qty` (jumlah)

### 3. **Format File Salah**
**Masalah**: File tidak bisa diunggah
**Penyebab**: File bukan .xlsx, .xls, atau .csv
**Solusi**: Gunakan format yang didukung

---

## 📋 FORMAT KOLOM EXCEL YANG BENAR

| No | Kolom Excel | Wajib | Jenis | Contoh |
|-------|-----------|-------|-------|---------|
| 1 | `namaperangkat` | ✓ **YA** | Text | Router TP-Link TL-WR840N |
| 2 | `qty` | ✓ **YA** | Angka | 5 |
| 3 | `no` | ✗ Tidak | Text | R001 |
| 4 | `merk/type` | ✗ Tidak | Text | TP-Link |
| 5 | `sisastok` | ✗ Tidak | Angka | 5 |
| 6 | `keterangan` | ✗ Tidak | Text | Stock baru |

### ⚠️ PERHATIAN PENTING:
```
✓ Gunakan HURUF KECIL untuk nama kolom
✓ Spasi OTOMATIS dihilangkan (boleh ada spasi di Excel, akan dihilangkan)
✓ Hanya 2 kolom yang WAJIB: namaperangkat dan qty
✓ Baris kosong DIABAIKAN
✓ Duplicate nomor_barang = UPDATE, bukan INSERT BARU
```

---

## 🚀 LANGKAH-LANGKAH IMPORT

### Step 1: Download Template
1. Buka aplikasi SIMJAR
2. Login dengan akun Anda
3. Navigasi ke: **Barang Masuk** → **Import**
4. Klik tombol **"Download Template Excel"**
5. File `sample_import_barang.xlsx` akan terdownload

### Step 2: Isi Data di Excel
1. Buka file template dengan Microsoft Excel, Google Sheets, atau LibreOffice
2. Isi data sesuai format:
   ```
   Baris 1 (Header): namaperangkat, qty, no, merk/type, sisastok, keterangan
   Baris 2+: Data produk Anda
   ```

### Step 3: Simpan File
1. Simpan dengan format **XLSX** atau **XLS**
   - **Jangan gunakan CSV** (Excel mengubah format kolom)
   - **Jangan ubah nama kolom**

### Step 4: Upload File
1. Buka kembali halaman **Barang Masuk** → **Import**
2. Klik **"Pilih file Excel"**
3. Pilih file yang sudah diisi data
4. Klik tombol **"Import"**
5. Tunggu proses selesai

### Step 5: Verifikasi Hasil
1. Jika berhasil, akan muncul pesan: "Import selesai. X data berhasil diimport"
2. Klik "Barang Masuk" untuk melihat data yang baru diimport
3. Cari item dengan nama yang Anda import

---

## 📝 CONTOH FILE EXCEL YANG BENAR

### Template Minimal (hanya 2 kolom wajib):
```
namaperangkat          | qty
---|---
Router TP-Link         | 5
Switch Netgear         | 3
Modem ZTE             | 2
```

### Template Lengkap (semua kolom):
```
no    | namaperangkat              | merk/type | qty | sisastok | keterangan
------|---------------------------|-----------|-----|----------|------------------
R001  | Router TP-Link TL-WR840N  | TP-Link   | 5   | 5        | Stock baru
S001  | Switch Netgear GS310TP    | Netgear   | 3   | 3        | Pembelian
M001  | Modem ZTE F609            | ZTE       | 2   | 2        | Replacement
K001  | Kabel UTP Cat5 100m       | Generic   | 10  | 10       | Bulk purchase
AP001 | Access Point Ubiquiti     | Ubiquiti  | 2   | 2        | Warranty included
```

---

## ✅ CHECKLIST SEBELUM IMPORT

- [ ] File dalam format .xlsx atau .xls
- [ ] Header baris pertama: `namaperangkat`, `qty` (dan kolom lain jika ada)
- [ ] Semua nama kolom huruf kecil (lowercase)
- [ ] Kolom `namaperangkat` tidak ada yang kosong
- [ ] Kolom `qty` berisi angka (tidak ada teks)
- [ ] Data mulai dari baris ke-2 (baris 1 adalah header)
- [ ] Tidak ada baris kosong di tengah data (boleh ada di akhir)

---

## 🔍 TROUBLESHOOTING

### Error: "Kolom wajib tidak ditemukan"
**Solusi**:
1. Check nama kolom Excel - harus `namaperangkat` dan `qty` (huruf kecil)
2. Download template untuk format yang benar
3. Copy-paste nama kolom dari template

### Error: "Tidak ada data yang berhasil diimport"
**Solusi**:
1. Pastikan minimal ada 1 data di baris ke-2
2. Kolom `namaperangkat` tidak boleh kosong
3. Kolom `qty` harus berisi angka

### File tidak bisa diupload
**Solusi**:
1. Pastikan format file: .xlsx, .xls, atau .csv
2. Ukuran file maksimal: 5MB
3. Jangan rename file dengan karakter khusus

### Data tidak tampil setelah import
**Solusi**:
1. Refresh halaman (Ctrl+F5)
2. Lihat di daftar Barang Masuk
3. Cari dengan nama yang Anda import

### Duplikat diupdate, bukan ditambah
**Info**: Ini normal! Jika `nomor_barang` sudah ada, data akan diupdate
- Jika ingin data baru, gunakan `nomor_barang` yang berbeda
- Jika ingin menimpa data lama, gunakan `nomor_barang` yang sama

---

## 📥 TEKNOLOGI YANG DIGUNAKAN

- **Library**: PhpOffice\PhpSpreadsheet ^5.4
- **Supported Formats**: .xlsx (Excel 2007+), .xls (Excel 97-2003), .csv
- **Max File Size**: 5MB
- **Authentication**: Required (login dahulu)
- **Multi-user**: Safe (setiap user import ke cabang mereka sendiri)

---

## 💡 TIPS & TRIK

1. **Batch Import**: Bisa import puluhan/ratusan item sekali
2. **Update Massal**: Gunakan nomor_barang yang sama untuk update data
3. **Copy-Paste dari Template**: Lebih aman daripada buat manual
4. **Validate di Excel**: Pastikan tidak ada typo sebelum import
5. **Backup Data**: Simpan copy Excel sebelum import

---

## 📞 JIKA MASIH BERMASALAH

1. Download template dari halaman import
2. Isi data sesuai contoh yang ada di template
3. Gunakan nama kolom TEPAT seperti di template
4. Jika masih error, hubungi admin dengan file Excel yang gagal

---

**Status**: ✅ READY FOR USE  
**Last Updated**: 21 April 2026  
**Version**: 1.0

