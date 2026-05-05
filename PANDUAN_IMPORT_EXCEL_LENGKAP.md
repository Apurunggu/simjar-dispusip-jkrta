# PANDUAN LENGKAP IMPORT BARANG MASUK

## Apa yang sudah diperbaiki

### 1. ✅ Bug pada normalisasi header Excel
**Masalah:** Spasi dan karakter khusus di nama kolom Excel tidak diproses dengan benar
**Solusi:** Memperbaiki string replacement untuk menggunakan escape sequence yang benar (`\t`, `\n`, `\r` bukan `'\t'`, `'\n'`, `'\r'`)

### 2. ✅ Template Excel yang benar
File template tersedia di: `/public/sample_import_barang.xlsx`

**Kolom yang WAJIB:**
- `namaperangkat` - Nama barang/perangkat
- `qty` - Jumlah barang

**Kolom yang OPSIONAL:**
- `merk/type` - Merek atau tipe (default: "Uncategorized")
- `no` - Nomor barang (auto-generate jika kosong: BRG-[3 huruf merk]-[nomor])
- `sisastok` - Sisa stok (default: = qty)
- `keterangan` - Catatan/keterangan

---

## Cara Import Data yang Benar

### Langkah 1: Download Template
1. Buka menu **Import Barang Masuk** di aplikasi
2. Klik tombol **"Download Template Excel"**
3. Buka file `sample_import_barang.xlsx` yang diunduh

### Langkah 2: Isi Data
- Gunakan sheet **"Import Template"** untuk menambah data
- Jangan ubah nama kolom header di baris pertama
- Isi minimal: `namaperangkat` dan `qty`
- Contoh data sudah disediakan, bisa diganti atau ditambah

### Langkah 3: Upload File
1. Kembali ke halaman **Import Barang Masuk**
2. Klik **"Pilih file Excel"** dan pilih file yang sudah diisi
3. Klik tombol **"Import"**
4. Tunggu proses selesai

---

## Tips & Trik

### ✅ Format Kolom yang Benar
```
namaperangkat    | qty | merk/type        | no      | sisastok | keterangan
Router TP-Link   | 5   | TP-Link          | R001    | 5        | Stock baru
Switch Cisco     | 3   | Cisco            | S001    | 3        | 
Access Point     | 2   | Ubiquiti         |         | 2        | Donasi
```

### ⚠️ Hal-hal yang PENTING
1. **Qty HARUS angka** - Contoh: `5`, `10`, bukan `lima` atau `10 pcs`
2. **Nama barang jangan kosong** - Baris dengan nama kosong akan diabaikan
3. **Duplikat akan di-UPDATE** - Jika barang (nama+merk) sudah ada, data akan diupdate bukan ditambah baru
4. **Pastikan memiliki CABANG** - Akun Anda harus memiliki cabang yang valid

### ❌ Contoh Kesalahan
```
SALAH:
- namaperangkat kosong (baris akan diabaikan)
- qty = "lima" (bukan angka)
- qty = 0 atau negatif (baris akan diabaikan)

BENAR:
- namaperangkat = "Router TP-Link"
- qty = 5
- qty bisa negatif tidak apa2 (akan diabaikan)
```

---

## Troubleshooting

### ❌ Error: "Kolom wajib tidak ditemukan"
**Penyebab:** Header kolom tidak sesuai dengan yang diharapkan
**Solusi:**
- Gunakan template dari tombol Download
- Pastikan: kolom 1 = `namaperangkat`, kolom 2 = `qty`
- Jangan ubah nama kolom

### ❌ Error: "File terlalu besar"
**Penyebab:** File > 5MB
**Solusi:** Kurangi jumlah data atau hapus sheet yang tidak perlu

### ❌ Import berhasil tapi data tidak muncul
**Penyebab:** 
- Akun Anda tidak punya cabang
- Data tidak sesuai kriteria validasi
**Solusi:**
- Hubungi admin untuk set cabang
- Cek lagi format data (qty harus angka, nama tidak kosong)

### ❌ Data diupdate semua, padahal mau tambah baru
**Catatan:** Import mengecek duplikat berdasarkan nama + merk
- Jika (nama + merk) sudah ada → UPDATE
- Jika (nama + merk) baru → CREATE

**Jika ingin tambah data berbeda:** Ubah nama atau merk-nya

---

## File yang sudah diperbaiki

```
✅ app/Http/Controllers/BarangMasukController.php
   - Perbaiki normalisasi header Excel
   - Import logic tetap sama (update + create)

✅ public/sample_import_barang.xlsx
   - Template dengan contoh data
   - Sheet Petunjuk dengan panduan lengkap
```

---

## Testing

Untuk memverifikasi import berfungsi dengan benar:

1. Download template dari halaman import
2. Isi dengan data contoh:
   ```
   namaperangkat = "Test Router"
   qty = 3
   merk/type = "Test Merk"
   ```
3. Upload dan cek apakah muncul di daftar Barang Masuk

---

## Kontak Support

Jika masih ada masalah:
1. Pastikan sudah ikuti langkah di atas dengan benar
2. Cek message error yang ditampilkan aplikasi
3. Hubungi administrator jika akun belum punya cabang

---

**Update terakhir:** April 22, 2026
