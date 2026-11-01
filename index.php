<?php
include 'koneksi.php';

// Hitung jumlah laporan per status
$jumlah = ['dilaporkan' => 0, 'diproses' => 0, 'selesai' => 0];
$hasil = mysqli_query($koneksi, "SELECT status, COUNT(*) AS total FROM tb_laporan GROUP BY status");
while ($row = mysqli_fetch_assoc($hasil)) {
    $jumlah[$row['status']] = (int) $row['total'];
}
$total = array_sum($jumlah);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelaporan Masalah Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-2xl font-bold mb-1">Pelaporan Masalah Kelas</h1>
        <p class="text-sm text-gray-600 mb-6">Catat dan pantau masalah fasilitas, kebersihan, dan jaringan di kelas.</p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <div class="text-2xl font-bold"><?= $total; ?></div>
                <div class="text-xs text-gray-500">Total</div>
            </div>
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-600"><?= $jumlah['dilaporkan']; ?></div>
                <div class="text-xs text-gray-500">Dilaporkan</div>
            </div>
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600"><?= $jumlah['diproses']; ?></div>
                <div class="text-xs text-gray-500">Diproses</div>
            </div>
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600"><?= $jumlah['selesai']; ?></div>
                <div class="text-xs text-gray-500">Selesai</div>
            </div>
        </div>

        <div class="flex space-x-2">
            <a href="tampilLaporan.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm transition">Lihat Semua Laporan</a>
            <a href="tambahLaporan.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition">Tambah Laporan</a>
        </div>
    </div>
</body>
</html>
