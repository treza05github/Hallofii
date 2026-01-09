<?php
session_start();
include "koneksi.php";

$id = $_POST['id'];
$status = $_POST['status'];

$query = mysqli_query($koneksi,"UPDATE reservasi SET status='$status' WHERE reservasi_id='$id'");
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
            title: 'Status Diperbarui!',
            text: 'Data reservasi berhasil diupdate.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'keloladatareservasi.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan sistem.',
            icon: 'error'
        }).then(() => {
            window.location = 'keloladatareservasi.php';
        });
    <?php } ?>
</script>
</body>
</html>