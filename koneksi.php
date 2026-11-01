<?php
// Pengaturan koneksi database (sesuaikan dengan AWebServer Anda)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_laporan_kelas";

// Matikan exception bawaan agar error bisa ditangani manual
mysqli_report(MYSQLI_REPORT_OFF);

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");

// Daftar nilai yang diperbolehkan (dipakai di banyak file)
$daftar_kategori = ['fasilitas', 'kebersihan', 'jaringan'];
$daftar_status   = ['dilaporkan', 'diproses', 'selesai'];
