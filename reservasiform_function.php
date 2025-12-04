<?php
session_start();
include "koneksi.php";

$id_pengguna = $_SESSION['id_pengguna'];
$dokter_id = $_POST['dokter_id'];
$layanan = $_POST['layanan'];
$tanggal = $_POST['tanggal'];

mysqli_query($koneksi, "
    INSERT INTO reservasi (id_pengguna, dokter_id, layanan_spesialis, tanggal_waktu)
    VALUES ('$id_pengguna', '$dokter_id', '$layanan', '$tanggal')
");

header("Location: reservasi.php");
exit;
