<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "pasien") {
    header("Location: login.php");
    exit;
}
// Ambil username dari session dengan pengecekan
$username = isset($_SESSION['username']) && $_SESSION['username'] !== '' 
            ? $_SESSION['username'] 
            : 'pasien';

// Pastikan variabel string sebelum memanggil strtoupper()
$displayName = is_string($username) ? strtoupper($username) : 'PASIEN';
?>



<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<title>Dashboard Pasien</title>
<link rel="stylesheet" href="admin.css">
<!-- Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="navbar">
    <div>DASHBOARD PASIEN</div>
    <div class="right">
        <span><?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></span>
        <span><a href="pasienprofil.php"><img src="profil.png" alt="profil" style="width: 30px;"></a></span>
    </div>
</div>

<div class="container">
    <div class="card">
        <img src="reservasii.png">
        <h3> Reservasi</h3>
        <a href="reservasi.php" class="akses-btn">Akses</a>
    </div>

    <div class="card">
        <img src="dokter.png">
        <h3> Daftar Dokter</h3>
        <a href="daftardokter.php" class="akses-btn">Akses</a>
    </div>

</div>


</body>
</html>