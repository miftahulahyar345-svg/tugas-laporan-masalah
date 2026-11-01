CREATE DATABASE IF NOT EXISTS db_laporan_kelas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_laporan_kelas;

CREATE TABLE IF NOT EXISTS tb_laporan (
    id_laporan INT AUTO_INCREMENT PRIMARY KEY,
    tanggal    DATE NOT NULL,
    kelas      VARCHAR(30) NOT NULL,
    kategori   ENUM('fasilitas','kebersihan','jaringan') NOT NULL,
    judul      VARCHAR(100) NOT NULL,
    deskripsi  TEXT NOT NULL,
    pelapor    VARCHAR(60) NOT NULL,
    status     ENUM('dilaporkan','diproses','selesai') NOT NULL DEFAULT 'dilaporkan'
);

INSERT INTO tb_laporan (tanggal, kelas, kategori, judul, deskripsi, pelapor, status) VALUES
('2026-10-12', 'XII IPA 1', 'fasilitas',  'Kipas angin rusak',   'Kipas di pojok kiri belakang tidak menyala.', 'Andi',  'dilaporkan'),
('2026-10-13', 'XI RPL 2',  'jaringan',   'WiFi sering putus',   'Sinyal WiFi hilang timbul saat praktik.',      'Siti',  'diproses'),
('2026-10-14', 'X TKJ 1',   'kebersihan', 'Tempat sampah penuh', 'Tempat sampah belum diangkut dua hari.',       'Budi',  'selesai');
