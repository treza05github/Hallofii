<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "pasien") { header("Location: login.php"); exit; }
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Pasien';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .navbar-custom { background: linear-gradient(90deg, #0d6efd, #0099ff); }
        .menu-card { border: none; border-radius: 15px; padding: 30px; transition: 0.3s; background: white; height: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .icon-box { font-size: 2.5rem; color: #0d6efd; margin-bottom: 15px; }
    </style>
</head>
<body>

<nav class="navbar navbar-custom navbar-dark py-3 mb-5">
    <div class="container">
        <span class="navbar-brand fw-bold">DASHBOARD PASIEN</span>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Halo, <b><?= strtoupper($username) ?></b></span>
            <a href="pasienprofil.php"><img src="profil.png" class="rounded-circle border border-2 border-white" width="40" height="40"></a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row g-4 justify-content-center">
        <div class="col-md-5">
            <div class="menu-card text-center">
                <i class="bi bi-journal-medical icon-box"></i>
                <h4 class="fw-bold">Riwayat Reservasi</h4>
                <p class="text-muted">Lihat status janji temu Anda</p>
                <a href="reservasi.php" class="btn btn-primary rounded-pill w-100">Lihat Reservasi</a>
            </div>
        </div>
        <div class="col-md-5">
            <div class="menu-card text-center">
                <i class="bi bi-heart-pulse-fill icon-box"></i>
                <h4 class="fw-bold">Cari Dokter</h4>
                <p class="text-muted">Temukan dokter dan buat janji</p>
                <a href="daftardokter.php" class="btn btn-primary rounded-pill w-100">Cari Dokter</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>