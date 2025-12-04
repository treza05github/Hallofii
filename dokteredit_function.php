<?php
include "koneksi.php";

$dokter_id = $_POST['dokter_id'];
$nama = $_POST['nama_dokter'];
$spesialisasi = $_POST['spesialisasi'];

// Jika foto baru diupload
if ($_FILES['foto_dokter']['size'] > 0) {
    $foto = addslashes(file_get_contents($_FILES['foto_dokter']['tmp_name']));

    $query = mysqli_query($koneksi, "
        UPDATE dokter SET 
            nama_dokter = '$nama',
            spesialisasi = '$spesialisasi',
            foto_dokter = '$foto'
        WHERE dokter_id = '$dokter_id'
    ");
} else {
    // Foto tetap (tidak diubah)
    $query = mysqli_query($koneksi, "
        UPDATE dokter SET 
            nama_dokter = '$nama',
            spesialisasi = '$spesialisasi'
        WHERE dokter_id = '$dokter_id'
    ");
}

if ($query) {
    echo "<script>alert('Data dokter berhasil diperbarui!'); window.location='keloladatadokter.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui data dokter!'); window.location='keloladatadokter.php';</script>";
}
?>
