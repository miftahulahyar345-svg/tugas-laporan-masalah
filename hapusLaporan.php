<?php
include 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tb_laporan WHERE id_laporan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: tampilLaporan.php?pesan=hapus");
exit;
