<?php
session_start();
include "koneksi.php";

// CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// CEK ID ADA ATAU TIDAK
if (!isset($_GET['id'])) {
    echo "<script>alert('ID dokter tidak ditemukan!'); window.location='keloladokter.php';</script>";
    exit;
}

$dokter_id = $_GET['id'];

// HAPUS DATA DOKTER
$query = mysqli_query($koneksi, "DELETE FROM dokter WHERE dokter_id = '$dokter_id'");

if ($query) {
    echo "<script>alert('Data dokter berhasil dihapus!'); window.location='keloladatadokter.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data dokter!'); window.location='keloladatadokter.php';</script>";
}
?>
