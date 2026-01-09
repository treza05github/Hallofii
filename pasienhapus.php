<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$id'");
$data = mysqli_fetch_assoc($cek);
$berhasil = false;
$msg = "";

if (!$data) {
    $msg = "Data pasien tidak ditemukan!";
} elseif (strtolower($data['username']) == "admin") {
    $msg = "Akun admin utama tidak boleh dihapus!";
} else {
    $query = mysqli_query($koneksi, "DELETE FROM pengguna WHERE id_pengguna='$id'");
    if ($query) {
        $berhasil = true;
    } else {
        $msg = "Gagal menghapus data dari database.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body{font-family:'Poppins', sans-serif; background:#f3f4f6;}</style>
</head>
<body>
<script>
    <?php if ($berhasil) { ?>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data pasien berhasil dihapus.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'keloladatapasien.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Gagal!',
            text: '<?= $msg; ?>',
            icon: 'error'
        }).then(() => {
            window.location = 'keloladatapasien.php';
        });
    <?php } ?>
</script>
</body>
</html>