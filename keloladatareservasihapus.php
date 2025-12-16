<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "DELETE FROM reservasi WHERE reservasi_id='$id'");
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body{font-family:'Poppins', sans-serif; background:#f3f4f6;}</style>
</head>
<body>
<script>
    <?php if ($query) { ?>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data reservasi telah dihapus.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'keloladatareservasi.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menghapus data.',
            icon: 'error'
        }).then(() => {
            window.location = 'keloladatareservasi.php';
        });
    <?php } ?>
</script>
</body>
</html>