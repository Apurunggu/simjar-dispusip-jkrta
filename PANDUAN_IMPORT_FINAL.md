📋 CARA IMPORT BARANG MASUK - UPDATE TERBARU

## ✅ Perbaikan yang Sudah Dilakukan

1. **Header Normalisasi** - Mendukung kolom dengan spasi dan huruf besar:
   - ✓ "NAMA PERANGKAT" 
   - ✓ "SISA STOK"
   - ✓ "MERK/TYPE"
   - ✓ Kolom lainnya dengan format apapun

2. **Filter Cabang** - Duplikat check & nomor barang generation sekarang per-cabang

3. **Error Handling** - Pesan error yang lebih jelas

---

## 🗂️ Format File Excel yang Didukung

### Kolom WAJIB:
| Kolom | Format | Contoh |
|-------|--------|--------|
| NAMA PERANGKAT | Teks | Router TP-Link |
| QTY | Angka | 5 |

### Kolom OPSIONAL:
| Kolom | Format | Contoh |
|-------|--------|--------|
| MERK/TYPE | Teks | TP-Link TL-WR840N |
| NO | Teks | R001 (auto-generate jika kosong) |
| SISA STOK | Angka | 5 (default = QTY) |
| KETERANGAN | Teks | Stock baru |

**Kolom Ekstra** (akan diabaikan tapi tidak error):
- TAHUN PENGADAAN
- KEPEMILIKAN
- STATUS
- POSISI
- Kolom lainnya apapun

---

## 📥 Cara Import File Anda

### Langkah 1: Siapkan File Excel
- Buka file Excel Anda (seperti: `DATA DUKUNG APLIKASI STOK BARANG.xlsx`)
- Pastikan ada kolom "NAMA PERANGKAT" dan "QTY"
- Kolom lainnya boleh apa saja (akan diabaikan)

### Langkah 2: Upload ke Aplikasi
1. Buka aplikasi → Menu **Barang Masuk** → **Import**
2. Klik **"Pilih File Excel"**
3. Pilih file Excel Anda
4. Klik **"Import"**

### Langkah 3: Cek Hasil
- Jika berhasil → Lihat pesan "✅ Import selesai!"
- Buka **Daftar Barang Masuk** → Cek data sudah masuk

---

## ⚠️ Hal-hal Penting

### ✅ PASTI BERHASIL:
```
Baris 1 (Header): NO | NAMA PERANGKAT | MERK/TYPE | QTY | ... | KETERANGAN
Baris 2: 1 | Router TP-Link | TP-Link | 5 | ... | Stock baru
Baris 3: 2 | Switch Cisco | Cisco | 3 | ... | Donasi
```

### ❌ GAGAL (baris akan diabaikan):
```
- Nama barang kosong
- QTY bukan angka
- QTY ≤ 0
```

### 🔄 UPDATE (bukan CREATE):
```
Jika (nama + merk) SAMA dengan barang yang sudah ada:
→ Data akan diupdate, bukan ditambah baru
```

---

## 💡 Tips

### 1. Nomor Barang Auto-Generate
Jika kolom "NO" kosong, sistem akan membuat nomor otomatis:
- Format: `BRG-[3 huruf kategori]-[0001]`
- Contoh: `BRG-TP-0001` (dari merk "TP-Link")

### 2. Duplikat per-Cabang
Setiap cabang bisa punya barang sama dengan nomor berbeda

### 3. Kolom Ekstra Aman
Kolom seperti TAHUN PENGADAAN, STATUS, POSISI tidak akan error, tapi juga tidak disimpan ke barang_masuk (untuk sekarang)

---

## 🐛 Troubleshooting

### Error: "Kolom wajib tidak ditemukan"
**Penyebab:** File tidak punya kolom "NAMA PERANGKAT" atau "QTY"
**Solusi:** 
1. Cek nama kolom di baris pertama
2. Pastikan huruf besar: "NAMA PERANGKAT" (bukan "nama perangkat")

### Error: "Akun tidak memiliki cabang"
**Penyebab:** Akun Anda belum assign ke cabang
**Solusi:** Hubungi admin untuk set cabang akun Anda

### Import berhasil tapi data tidak muncul
**Penyebab:** 
- Data kosong atau QTY tidak valid
- Browser belum di-refresh
**Solusi:**
1. Refresh halaman (F5)
2. Cek apakah data sudah muncul
3. Cek file Excel, pastikan QTY adalah angka

### Data duplicate (terUpdate semua)
**Catatan:** Ini normal! Import mengecek:
- Nama barang SAMA + Merk SAMA + Cabang SAMA → UPDATE
- Jika ada salah satu berbeda → CREATE baru

**Cara hindari:**
- Ubah nama atau merk jika ingin data baru
- Atau gunakan nomor berbeda

---

## 📊 Test dengan File Anda

Kami sudah test dengan format file Excel Anda:
- ✓ 32+ baris data
- ✓ 11 kolom (NO, NAMA PERANGKAT, MERK/TYPE, QTY, Satuan, SISA STOK, KEPEMILIKAN, STATUS, POSISI, TAHUN PENGADAAN, KETERANGAN)
- ✓ Semua kolom terdeteksi dengan benar
- ✓ Data siap diimport

**File Anda PASTI bisa diimport!** ✅

---

## 🚀 Langsung Coba Sekarang!

1. Ambil file Excel Anda
2. Buka aplikasi → Barang Masuk → Import
3. Upload file
4. Lihat data muncul di daftar barang

Jika masih ada masalah, screenshot error message dan hubungi support.

---

**Update:** 22 April 2026
**Status:** ✅ READY TO USE
