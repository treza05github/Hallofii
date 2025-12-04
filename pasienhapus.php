<?php
session_start();
include "koneksi.php";

// CEK LOGIN ADMIN
if (!isset($_SESSION['username']) || $_SESSION['username'] !== "admin") {
    header("Location: login.php");
    exit;
}

// CEK APAKAH ADA ID YANG DIKIRIM
if (!isset($_GET['id'])) {
    echo "<script>alert('ID pasien tidak ditemukan!'); window.location='keloladatapasien.php';</script>";
    exit;
}

$id = $_GET['id'];

// CEK DATA PASIEN TERDAFTAR
$cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$id'");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='kelolapasien.php';</script>";
    exit;
}

// CEGAH ADMIN DIHAPUS
if (strtolower($data['username']) == "admin") {
    echo "<script>alert('Akun admin tidak boleh dihapus!'); window.location='keloladatapasien.php';</script>";
    exit;
}

// HAPUS PASIEN (username selain admin)
$query = mysqli_query($koneksi, "DELETE FROM pengguna WHERE id_pengguna='$id'");

if ($query) {
    echo "<script>alert('Data pasien berhasil dihapus!'); window.location='keloladatapasien.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus pasien!'); window.location='keloladatapasien.php';</script>";
}
?>
