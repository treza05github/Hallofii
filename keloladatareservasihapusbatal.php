<?php
session_start();
include "koneksi.php";

// Cek Login Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php");
    exit;
}

// QUERY HAPUS SEMUA DATA STATUS 'BATAL'
$query = mysqli_query($koneksi, "DELETE FROM reservasi WHERE status = 'reservasi dibatalkan'");
$jumlah_terhapus = mysqli_affected_rows($koneksi);

?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body{font-family:'Poppins', sans-serif; background:#f3f4f6;}</style>
</head>
<body>
<script>
    <?php if ($jumlah_terhapus > 0) { ?>
        Swal.fire({
            title: 'Pembersihan Selesai!',
            text: '<?= $jumlah_terhapus ?> data sampah (batal) berhasil dihapus permanen.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'keloladatareservasi.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Bersih!',
            text: 'Tidak ada data batal yang perlu dihapus.',
            icon: 'info',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'keloladatareservasi.php';
        });
    <?php } ?>
</script>
</body>
</html>