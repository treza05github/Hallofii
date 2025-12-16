<?php
session_start();
include "koneksi.php";

// Cek Login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// QUERY HAPUS
// Hanya menghapus data milik user ini DAN statusnya 'reservasi dibatalkan'
$query = mysqli_query($koneksi, "
    DELETE FROM reservasi 
    WHERE id_pengguna = '$id_pengguna' 
    AND status = 'reservasi dibatalkan'
");

// Cek apakah ada baris yang terhapus
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
            title: 'Berhasil!',
            text: '<?= $jumlah_terhapus ?> riwayat reservasi batal berhasil dibersihkan.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'reservasi.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Info',
            text: 'Tidak ada riwayat batal yang ditemukan.',
            icon: 'info',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'reservasi.php';
        });
    <?php } ?>
</script>
</body>
</html>