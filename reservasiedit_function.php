<?php
include "koneksi.php";

$id = $_POST['id'];
$dokter_id = $_POST['dokter_id'];
$layanan = $_POST['layanan_spesialis'];
$tanggal = $_POST['tanggal_waktu'];

mysqli_query($koneksi, "
    UPDATE reservasi SET 
        dokter_id='$dokter_id',
        layanan_spesialis='$layanan',
        tanggal_waktu='$tanggal'
    WHERE reservasi_id='$id'
");

header("Location: reservasi.php");
exit;
?>
