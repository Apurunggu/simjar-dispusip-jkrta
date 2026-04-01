-- SIMJAR Database Setup
-- Database: simjar_db
-- Created: 2024

CREATE DATABASE IF NOT EXISTS simjar_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE simjar_db;

-- Table: barang_masuk
CREATE TABLE IF NOT EXISTS barang_masuk (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nomor_barang VARCHAR(255) NOT NULL UNIQUE,
    nama_barang VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    jumlah INT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    keterangan LONGTEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_kategori (kategori),
    INDEX idx_tanggal_masuk (tanggal_masuk)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: perangkat_jaringan
CREATE TABLE IF NOT EXISTS perangkat_jaringan (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nomor_inventaris VARCHAR(255) NOT NULL UNIQUE,
    nama_perangkat VARCHAR(255) NOT NULL,
    tipe_perangkat VARCHAR(100) NOT NULL,
    lokasi VARCHAR(100) NOT NULL,
    ip_address VARCHAR(100),
    mac_address VARCHAR(100),
    status ENUM('aktif', 'tidak_aktif') DEFAULT 'aktif',
    tanggal_pemasangan DATE NOT NULL,
    keterangan LONGTEXT,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_lokasi (lokasi),
    INDEX idx_status (status),
    INDEX idx_tipe_perangkat (tipe_perangkat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: activity_logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    perangkat_id BIGINT UNSIGNED NOT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    deskripsi LONGTEXT,
    tanggal_aktivitas DATETIME NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (perangkat_id) REFERENCES perangkat_jaringan(id) ON DELETE CASCADE,
    INDEX idx_perangkat_id (perangkat_id),
    INDEX idx_tanggal_aktivitas (tanggal_aktivitas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk import data Excel (mapping 1:1 dengan kolom Excel)
CREATE TABLE IF NOT EXISTS barang_masuk_excel (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama_perangkat VARCHAR(255),
    merk_type VARCHAR(255),
    qty INT,
    satuan VARCHAR(50),
    sisa_stok INT,
    kepemilikan VARCHAR(100),
    status VARCHAR(100),
    posisi VARCHAR(255),
    tahun_pengadaan VARCHAR(10),
    keterangan TEXT,
    barang_masuk VARCHAR(100),
    barang_keluar VARCHAR(100),
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample Data
-- Barang Masuk
INSERT INTO barang_masuk (nomor_barang, nama_barang, kategori, jumlah, tanggal_masuk, keterangan) VALUES
('BRG-001', 'Router TP-Link TL-WR840N', 'Router', 2, '2024-01-15', 'Router WiFi 300Mbps'),
('BRG-002', 'Switch Netgear GS310TP', 'Switch', 1, '2024-01-20', 'Managed Switch 10 Port'),
('BRG-003', 'Modem ZTE F609', 'Modem', 3, '2024-02-01', 'Modem GPON untuk ISP'),
('BRG-004', 'Access Point Ubiquiti Unifi', 'Access Point', 4, '2024-02-10', 'Access Point Enterprise'),
('BRG-005', 'Kabel UTP Cat6 100m', 'Kabel', 10, '2024-02-15', 'Kabel jaringan berstandar Cat6');

-- Perangkat Jaringan
INSERT INTO perangkat_jaringan (nomor_inventaris, nama_perangkat, tipe_perangkat, lokasi, ip_address, mac_address, status, tanggal_pemasangan, keterangan) VALUES
('INV-NET-001', 'Router Lantai 1', 'Router', 'Ruang Server', '192.168.1.1', '00:1A:2B:3C:4D:5E', 'aktif', '2024-01-15', 'Router utama'),
('INV-NET-002', 'Switch Utama', 'Switch', 'Ruang Server', '192.168.1.2', '00:1A:2B:3C:4D:5F', 'aktif', '2024-01-20', 'Switch managed 24 port'),
('INV-NET-003', 'AP Lantai 1', 'Access Point', 'Lantai 1', '192.168.1.10', '00:1A:2B:3C:4D:60', 'aktif', '2024-02-10', 'Access point coverage lantai 1'),
('INV-NET-004', 'AP Lantai 2', 'Access Point', 'Lantai 2', '192.168.1.11', '00:1A:2B:3C:4D:61', 'aktif', '2024-02-15', 'Access point coverage lantai 2'),
('INV-NET-005', 'Modem ISP', 'Modem', 'Ruang Server', '192.168.0.1', '00:1A:2B:3C:4D:62', 'aktif', '2024-01-01', 'Modem koneksi internet');

-- Activity Logs untuk contoh
INSERT INTO activity_logs (perangkat_id, aktivitas, deskripsi, tanggal_aktivitas) VALUES
(1, 'Perangkat Ditambahkan', 'Perangkat Router Lantai 1 ditambahkan ke sistem', NOW()),
(1, 'Perangkat Diperbarui', 'IP Address diubah', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 'Perangkat Ditambahkan', 'Perangkat Switch Utama ditambahkan ke sistem', NOW()),
(3, 'Perangkat Ditambahkan', 'Perangkat AP Lantai 1 ditambahkan ke sistem', NOW()),
(4, 'Perangkat Ditambahkan', 'Perangkat AP Lantai 2 ditambahkan ke sistem', NOW());
