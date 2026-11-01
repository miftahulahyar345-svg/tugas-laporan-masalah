<?php
include 'koneksi.php';

$id = (int) ($_GET['id'] ?? $_POST['id_laporan'] ?? 0);
if ($id <= 0) {
    header("Location: tampilLaporan.php");
    exit;
}

$error = null;

// Proses update
if (isset($_POST['submit'])) {
    $tanggal   = trim($_POST['tanggal'] ?? '');
    $kelas     = trim($_POST['kelas'] ?? '');
    $kategori  = $_POST['kategori'] ?? '';
    $judul     = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $pelapor   = trim($_POST['pelapor'] ?? '');
    $status    = $_POST['status'] ?? '';

    if ($tanggal === '' || $kelas === '' || $kategori === '' || $judul === '' || $deskripsi === '' || $pelapor === '' || $status === '') {
        $error = "Semua kolom wajib diisi!";
    } elseif (!in_array($kategori, $daftar_kategori, true) || !in_array($status, $daftar_status, true)) {
        $error = "Kategori atau status tidak valid.";
    } else {
        $query = "UPDATE tb_laporan SET tanggal=?, kelas=?, kategori=?, judul=?, deskripsi=?, pelapor=?, status=? WHERE id_laporan=?";
        $stmt = mysqli_prepare($koneksi, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssssi", $tanggal, $kelas, $kategori, $judul, $deskripsi, $pelapor, $status, $id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: tampilLaporan.php?pesan=edit");
                exit;
            }
            $error = "Gagal memperbarui data: " . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $error = "Prepare gagal: " . mysqli_error($koneksi);
        }
    }
}

// Ambil data lama untuk ditampilkan di form
$stmt = mysqli_prepare($koneksi, "SELECT * FROM tb_laporan WHERE id_laporan = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$lama = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$lama) {
    header("Location: tampilLaporan.php");
    exit;
}

// Jika form dikirim tapi gagal validasi, pertahankan input terakhir
$v = isset($_POST['submit']) ? array_merge($lama, $_POST) : $lama;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan Masalah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4 py-8 max-w-xl">
        <h1 class="text-2xl font-bold mb-6">Edit Laporan</h1>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm" role="alert"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="bg-white shadow-md rounded-lg p-6 space-y-4">
            <input type="hidden" name="id_laporan" value="<?= $id; ?>">
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="<?= htmlspecialchars($v['tanggal']); ?>" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kelas</label>
                <input type="text" name="kelas" value="<?= htmlspecialchars($v['kelas']); ?>" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="kategori" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php foreach ($daftar_kategori as $k): ?>
                        <option value="<?= $k; ?>" <?= $v['kategori'] === $k ? 'selected' : ''; ?>><?= ucfirst($k); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Judul Masalah</label>
                <input type="text" name="judul" maxlength="100" value="<?= htmlspecialchars($v['judul']); ?>" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi Lengkap</label>
                <textarea name="deskripsi" rows="3" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($v['deskripsi']); ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama Pelapor</label>
                <input type="text" name="pelapor" value="<?= htmlspecialchars($v['pelapor']); ?>" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php foreach ($daftar_status as $s): ?>
                        <option value="<?= $s; ?>" <?= $v['status'] === $s ? 'selected' : ''; ?>><?= ucfirst($s); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-end space-x-2 pt-4">
                <a href="tampilLaporan.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm transition">Kembali</a>
                <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>
