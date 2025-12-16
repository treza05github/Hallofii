<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

$dokter_id = $_GET['id'];
$query = mysqli_query($koneksi, "DELETE FROM dokter WHERE dokter_id = '$dokter_id'");
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
            title: 'Terhapus!',
            text: 'Data dokter berhasil dihapus.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'keloladatadokter.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Gagal!',
            text: 'Gagal menghapus data dokter.',
            icon: 'error'
        }).then(() => {
            window.location = 'keloladatadokter.php';
        });
    <?php } ?>
</script>
</body>
</html>