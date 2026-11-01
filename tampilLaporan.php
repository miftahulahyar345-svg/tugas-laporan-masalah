<?php
include 'koneksi.php';

// Filter status (opsional), hanya nilai yang valid yang diterima
$filter = $_GET['status'] ?? '';
if (!in_array($filter, $daftar_status, true)) {
    $filter = '';
}

if ($filter !== '') {
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM tb_laporan WHERE status = ? ORDER BY tanggal DESC, id_laporan DESC");
    mysqli_stmt_bind_param($stmt, "s", $filter);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM tb_laporan ORDER BY tanggal DESC, id_laporan DESC");
}

// Pesan setelah aksi
$pesan_map = [
    'tambah' => 'Laporan berhasil ditambahkan.',
    'edit'   => 'Laporan berhasil diperbarui.',
    'hapus'  => 'Laporan berhasil dihapus.',
];
$pesan = $pesan_map[$_GET['pesan'] ?? ''] ?? null;

$warna_status = [
    'dilaporkan' => 'bg-red-100 text-red-700',
    'diproses'   => 'bg-yellow-100 text-yellow-700',
    'selesai'    => 'bg-green-100 text-green-700',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h1 class="text-2xl font-bold">Daftar Laporan Masalah</h1>
            <div class="flex space-x-2">
                <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm transition">Beranda</a>
                <a href="tambahLaporan.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition">Tambah</a>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm" role="alert"><?= htmlspecialchars($pesan); ?></div>
        <?php endif; ?>

        <!-- Filter status -->
        <form method="GET" class="flex items-center space-x-2 mb-4">
            <label for="status" class="text-sm font-medium">Filter status:</label>
            <select name="status" id="status" class="border rounded p-2 text-sm bg-white">
                <option value="">Semua</option>
                <?php foreach ($daftar_status as $s): ?>
                    <option value="<?= $s; ?>" <?= $filter === $s ? 'selected' : ''; ?>><?= ucfirst($s); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm transition">Terapkan</button>
        </form>

        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Pelapor</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr class="border-t">
                        <td class="p-3"><?= $no++; ?></td>
                        <td class="p-3 whitespace-nowrap"><?= htmlspecialchars($row['tanggal']); ?></td>
                        <td class="p-3 whitespace-nowrap"><?= htmlspecialchars($row['kelas']); ?></td>
                        <td class="p-3"><?= htmlspecialchars(ucfirst($row['kategori'])); ?></td>
                        <td class="p-3">
                            <div class="font-medium"><?= htmlspecialchars($row['judul']); ?></div>
                            <div class="text-gray-500 text-xs"><?= htmlspecialchars($row['deskripsi']); ?></div>
                        </td>
                        <td class="p-3"><?= htmlspecialchars($row['pelapor']); ?></td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs <?= $warna_status[$row['status']] ?? 'bg-gray-100 text-gray-700'; ?>">
                                <?= htmlspecialchars(ucfirst($row['status'])); ?>
                            </span>
                        </td>
                        <td class="p-3 whitespace-nowrap">
                            <a href="editLaporan.php?id=<?= (int) $row['id_laporan']; ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs transition">Edit</a>
                            <a href="hapusLaporan.php?id=<?= (int) $row['id_laporan']; ?>" onclick="return confirm('Yakin hapus laporan ini?');" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($no === 1): ?>
                    <tr><td colspan="8" class="p-6 text-center text-gray-500">Belum ada laporan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
