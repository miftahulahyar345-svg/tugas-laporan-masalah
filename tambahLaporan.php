<?php
include 'koneksi.php';

$error = null;

if (isset($_POST['submit'])) {
    $tanggal   = trim($_POST['tanggal'] ?? '');
    $kelas     = trim($_POST['kelas'] ?? '');
    $kategori  = $_POST['kategori'] ?? '';
    $judul     = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $pelapor   = trim($_POST['pelapor'] ?? '');
    $status    = 'dilaporkan'; // laporan baru selalu dimulai dari "dilaporkan"

    if ($tanggal === '' || $kelas === '' || $kategori === '' || $judul === '' || $deskripsi === '' || $pelapor === '') {
        $error = "Semua kolom wajib diisi!";
    } elseif (!in_array($kategori, $daftar_kategori, true)) {
        $error = "Kategori tidak valid.";
    } else {
        $query = "INSERT INTO tb_laporan (tanggal, kelas, kategori, judul, deskripsi, pelapor, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($koneksi, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssss", $tanggal, $kelas, $kategori, $judul, $deskripsi, $pelapor, $status);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: tampilLaporan.php?pesan=tambah");
                exit;
            }
            $error = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $error = "Prepare gagal: " . mysqli_error($koneksi);
        }
    }
}

$kat_dipilih = $_POST['kategori'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laporan Masalah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4 py-8 max-w-xl">
        <h1 class="text-2xl font-bold mb-6">Tambah Laporan Baru</h1>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm" role="alert"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="bg-white shadow-md rounded-lg p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="<?= htmlspecialchars($_POST['tanggal'] ?? date('Y-m-d')); ?>" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kelas</label>
                <input type="text" name="kelas" value="<?= htmlspecialchars($_POST['kelas'] ?? ''); ?>" placeholder="Contoh: XII IPA 1" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="kategori" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php foreach ($daftar_kategori as $k): ?>
                        <option value="<?= $k; ?>" <?= $kat_dipilih === $k ? 'selected' : ''; ?>><?= ucfirst($k); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Judul Masalah</label>
                <input type="text" name="judul" maxlength="100" value="<?= htmlspecialchars($_POST['judul'] ?? ''); ?>" placeholder="Contoh: Kipas angin rusak" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi Lengkap</label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan detail masalah..." required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nama Pelapor</label>
                <input type="text" name="pelapor" value="<?= htmlspecialchars($_POST['pelapor'] ?? ''); ?>" placeholder="Nama siswa/pengguna" required class="w-full border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-end space-x-2 pt-4">
                <a href="tampilLaporan.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm transition">Kembali</a>
                <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
