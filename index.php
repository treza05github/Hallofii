<?php
include "koneksi.php";
$dokterQuery = mysqli_query($koneksi, "SELECT * FROM dokter ORDER BY dokter_id DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HalloFii - Layanan Kesehatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero { padding: 100px 0; background: linear-gradient(to right, #f8f9fa, #eef2f3); }
        .feature-box { padding: 30px; border-radius: 20px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); height: 100%; transition: 0.3s; }
        .feature-box:hover { transform: translateY(-5px); }
        .doctor-img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; }
        footer { background: #0f172a; color: white; padding: 50px 0 20px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary fs-4" href="#">HalloFii</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link text-dark mx-2" href="#home">Beranda</a></li>
                <li class="nav-item"><a class="nav-link text-dark mx-2" href="#about">Tentang</a></li>
                <li class="nav-item"><a class="nav-link text-dark mx-2" href="#doctor">Dokter</a></li>
                <li class="nav-item ms-3"><a href="login.php" class="btn btn-primary rounded-pill px-4">Masuk</a></li>
            </ul>
        </div>
    </div>
</nav>

<section id="home" class="hero d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-dark mb-3">Solusi Kesehatan <span class="text-primary">Terpercaya.</span></h1>
                <p class="lead text-muted mb-4">Layanan reservasi dokter yang mudah, cepat, dan aman untuk keluarga Anda.</p>
                <a href="login.php" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Mulai Sekarang</a>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center">
                <img src="https://img.freepik.com/free-vector/health-professional-team-concept-illustration_114360-1618.jpg" class="img-fluid" width="500">
            </div>
        </div>
    </div>
</section>

<section id="about" class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6"><img src="https://img.freepik.com/free-vector/medical-video-call-consultation-illustration_88138-415.jpg" class="img-fluid rounded-4"></div>
            <div class="col-md-6">
                <h6 class="text-primary fw-bold">TENTANG KAMI</h6>
                <h2 class="fw-bold mb-3">Layanan Digital Modern</h2>
                <p class="text-muted">HalloFii menyediakan sistem reservasi online yang memudahkan pasien memesan jadwal konsultasi tanpa antri.</p>
                <ul class="list-unstyled"><li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Akses 24 Jam</li><li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Dokter Spesialis</li></ul>
            </div>
        </div>
    </div>
</section>

<section id="doctor" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5"><h2 class="fw-bold">Dokter Kami</h2></div>
        <div class="row g-4">
            <?php while ($d = mysqli_fetch_assoc($dokterQuery)) { ?>
            <div class="col-md-4">
                <div class="feature-box text-center p-4">
                    <?php if ($d['foto_dokter']) { ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($d['foto_dokter']); ?>" class="doctor-img shadow">
                    <?php } else { ?>
                        <img src="https://cdn-icons-png.flaticon.com/512/3774/3774299.png" class="doctor-img shadow">
                    <?php } ?>
                    <h5 class="fw-bold mt-3"><?= $d['nama_dokter']; ?></h5>
                    <p class="text-primary small fw-bold"><?= $d['spesialisasi']; ?></p>
                    <a href="login.php" class="btn btn-outline-primary btn-sm rounded-pill mt-2">Buat Janji</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<footer>
    <div class="container text-center">
        <p class="mb-0 small">&copy; 2025 HalloFii. All Rights Reserved.</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>