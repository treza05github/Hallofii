<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_pengguna'])) { header("Location: login.php"); exit; }

$id_pengguna = $_SESSION['id_pengguna'];
$dokter_id   = $_POST['dokter_id'];
$layanan     = $_POST['layanan'];
$tanggal     = $_POST['tanggal'];

// --- VALIDASI JAM (PHP) ---
// Ambil jam dari input tanggal (format Y-m-d H:i)
$jamInput = date('H', strtotime($tanggal));

// Jika jam < 8 ATAU jam >= 17 (Artinya 17:00 ke atas ditolak)
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
$total_biaya = ($dataDokter) ? $dataDokter['biaya_konsultasi'] : 0;

$query = mysqli_query($koneksi, "
    INSERT INTO reservasi (id_pengguna, dokter_id, layanan_spesialis, tanggal_waktu, total_biaya, status)
    VALUES ('$id_pengguna', '$dokter_id', '$layanan', '$tanggal', '$total_biaya', 'menunggu dikonfirmasi')
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
            html: 'Reservasi berhasil dibuat.<br><b>Tagihan: Rp <?= number_format($total_biaya,0,',','.') ?></b><br>(Bayar di Klinik)',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location = 'reservasi.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan data.',
            icon: 'error'
        }).then(() => {
            window.history.back();
        });
    <?php } ?>
</script>
</body>
</html>