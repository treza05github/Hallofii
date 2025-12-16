<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .navbar-custom { background: linear-gradient(90deg, #0f4c75, #3282b8); }
        .menu-card { border: none; border-radius: 15px; padding: 30px; transition: 0.3s; background: white; height: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .icon-box { font-size: 2.5rem; color: #0f4c75; margin-bottom: 15px; }
    </style>
</head>
<body>

<nav class="navbar navbar-custom navbar-dark py-3 mb-5">
    <div class="container">
        <span class="navbar-brand fw-bold">DASHBOARD ADMIN</span>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Halo, <b><?= strtoupper($username) ?></b></span>
            <a href="adminprofil.php"><img src="profil.png" class="rounded-circle border border-2 border-white" width="40" height="40"></a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="menu-card text-center">
                <i class="bi bi-people-fill icon-box"></i>
                <h4 class="fw-bold">Data Pasien</h4>
                <p class="text-muted">Kelola data pengguna terdaftar</p>
                <a href="keloladatapasien.php" class="btn btn-outline-primary rounded-pill w-100">Akses</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="menu-card text-center">
                <i class="bi bi-person-hearts icon-box"></i>
                <h4 class="fw-bold">Data Dokter</h4>
                <p class="text-muted">Tambah atau edit dokter</p>
                <a href="keloladatadokter.php" class="btn btn-outline-primary rounded-pill w-100">Akses</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="menu-card text-center">
                <i class="bi bi-calendar-check-fill icon-box"></i>
                <h4 class="fw-bold">Reservasi</h4>
                <p class="text-muted">Konfirmasi jadwal temu</p>
                <a href="keloladatareservasi.php" class="btn btn-outline-primary rounded-pill w-100">Akses</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>