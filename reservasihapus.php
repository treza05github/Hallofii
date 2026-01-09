<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "UPDATE reservasi SET status='reservasi dibatalkan' WHERE reservasi_id='$id'");
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
            title: 'Dibatalkan!',
            text: 'Reservasi Anda telah berhasil dibatalkan.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'reservasi.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Error!',
            text: 'Gagal membatalkan reservasi.',
            icon: 'error'
        }).then(() => {
            window.location = 'reservasi.php';
        });
    <?php } ?>
</script>
</body>
</html>