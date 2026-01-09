<?php
session_start();
include "koneksi.php";

// Tangkap Data dari Form
$dokter_id = $_POST['dokter_id'];
$nama = $_POST['nama_dokter'];
$spesialisasi = $_POST['spesialisasi'];
$hari = $_POST['jadwal_hari'];
$jam = $_POST['jadwal_jam'];
$biaya = (int) $_POST['biaya_konsultasi'];

$success = false;

// LOGIKA UPDATE
if (!empty($_FILES['foto_dokter']['tmp_name'])) {
    // 1. JIKA GANTI FOTO
    
    // Ambil isi file foto baru
    $foto = file_get_contents($_FILES['foto_dokter']['tmp_name']);

    // Query Update dengan Foto (Prepared Statement)
    $stmt = $koneksi->prepare("UPDATE dokter SET nama_dokter=?, spesialisasi=?, jadwal_hari=?, jadwal_jam=?, biaya_konsultasi=?, foto_dokter=? WHERE dokter_id=?");
    
    // s=string, i=integer, s=blob (foto)
    $stmt->bind_param("ssssisi", $nama, $spesialisasi, $hari, $jam, $biaya, $foto, $dokter_id);
    
    if ($stmt->execute()) {
        $success = true;
    }

} else {
    // 2. JIKA TIDAK GANTI FOTO (Update data teks saja)
    
    $stmt = $koneksi->prepare("UPDATE dokter SET nama_dokter=?, spesialisasi=?, jadwal_hari=?, jadwal_jam=?, biaya_konsultasi=? WHERE dokter_id=?");
    
    $stmt->bind_param("ssssii", $nama, $spesialisasi, $hari, $jam, $biaya, $dokter_id);

    if ($stmt->execute()) {
        $success = true;
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
    <?php if ($success) { ?>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data dokter berhasil diperbarui.',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location = 'keloladatadokter.php';
        });
    <?php } else { ?>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat memperbarui data.',
            icon: 'error',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.history.back();
        });
    <?php } ?>
</script>
</body>
</html>