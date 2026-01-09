<?php
session_start();
include "koneksi.php";

$id = $_POST['id'];
$dokter_id = $_POST['dokter_id'];
$layanan = $_POST['layanan_spesialis'];
$tanggal = $_POST['tanggal_waktu'];

// --- VALIDASI JAM (PHP) ---
$jamInput = date('H', strtotime($tanggal));
if ($jamInput < 8 || $jamInput >= 17) {
    echo "<script>
        alert('Maaf, Klinik hanya buka dari jam 08:00 - 17:00 WIB.');
        window.history.back();
    </script>";
    exit;
}
// --------------------------

$cekDokter = mysqli_query($koneksi, "SELECT biaya_konsultasi FROM dokter WHERE dokter_id = '$dokter_id'");
$dataDokter = mysqli_fetch_assoc($cekDokter);
$biaya_baru = $dataDokter['biaya_konsultasi'];

$query = mysqli_query($koneksi, "
    UPDATE reservasi SET 
        dokter_id = '$dokter_id',
        layanan_spesialis = '$layanan',
        tanggal_waktu = '$tanggal',
        total_biaya = '$biaya_baru' 
    WHERE reservasi_id = '$id'
");
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
            text: 'Data reservasi berhasil diperbarui.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'reservasi.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Gagal!',
            text: 'Gagal memperbarui data.',
            icon: 'error'
        }).then(() => {
            window.history.back();
        });
    <?php } ?>
</script>
</body>
</html>