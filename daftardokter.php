<?php
session_start();
include "koneksi.php";

// Cek Login Pasien
if (!isset($_SESSION['role']) || $_SESSION['role'] != "pasien") {
    header("Location: login.php");
    exit;
}

$pasien = $_SESSION['username'];

// Konfigurasi Pagination & Pencarian
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";

// Filter pencarian (Nama atau Spesialisasi)
$filter = "";
if ($search != "") {
    $filter = "WHERE nama_dokter LIKE '%$search%' OR spesialisasi LIKE '%$search%'";
}

// Hitung Total Data
$totalQ = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dokter $filter");
$totalData = mysqli_fetch_assoc($totalQ)['total'];
$totalPage = ceil($totalData / $limit);

// Ambil Data Dokter
$query = mysqli_query($koneksi, "SELECT * FROM dokter $filter ORDER BY dokter_id DESC LIMIT $start, $limit");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Dokter | HalloFii</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, #0d6efd, #0099ff);
            padding: 15px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Doctor Card */
        .doctor-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15);
        }

        .doctor-img-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: #e9ecef;
        }

        .doctor-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.3s;
        }

        .doctor-card:hover .doctor-img {
            transform: scale(1.05);
        }

        .spesialis-badge {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: #0d6efd;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .doctor-name {
            font-weight: 700;
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .info-row {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 8px;
            display: flex;
            align-items: start;
        }

        .info-row i {
            color: #0d6efd;
            margin-right: 10px;
            margin-top: 3px;
        }

        .biaya-section {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px dashed #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .biaya-label {
            font-size: 0.8rem;
            color: #888;
        }

        .biaya-value {
            font-weight: 700;
            color: #198754;
            font-size: 1.1rem;
        }

        .btn-janji {
            width: 100%;
            margin-top: 15px;
            border-radius: 50px;
            font-weight: 600;
            padding: 10px;
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
        }

        .btn-janji:hover {
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        /* Pagination */
        .pagination .page-link {
            border-radius: 50%;
            margin: 0 3px;
            border: none;
            color: #555;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination .active .page-link {
            background-color: #0d6efd;
            color: white;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-custom navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">DAFTAR DOKTER</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">

        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <a href="pasien.php" class="text-decoration-none text-muted mb-2 d-inline-block fw-bold small">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
                <h2 class="fw-bold" style="color: #0d6efd;">Temukan Dokter</h2>
                <p class="text-muted">Pilih dokter spesialis terbaik untuk kebutuhan kesehatan Anda.</p>
            </div>
            <div class="col-md-6">
                <form method="GET" class="position-relative">
                    <input type="text" name="search" class="form-control form-control-lg rounded-pill ps-5 border-0 shadow-sm"
                        placeholder="Cari nama dokter atau spesialis..." value="<?= htmlspecialchars($search); ?>">
                    <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 rounded-circle btn-primary" style="width: 40px; height: 40px;">
                        <i class="bi bi-search"></i>
                    </button>
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <?php while ($d = mysqli_fetch_assoc($query)) { ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card doctor-card">
                        <div class="doctor-img-wrapper">
                            <?php if ($d['foto_dokter']) { ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($d['foto_dokter']); ?>" class="doctor-img">
                            <?php } else { ?>
                                <img src="https://via.placeholder.com/400x300?text=No+Image" class="doctor-img">
                            <?php } ?>
                            <span class="spesialis-badge"><?= $d['spesialisasi']; ?></span>
                        </div>

                        <div class="card-body">
                            <h5 class="doctor-name"><?= $d['nama_dokter']; ?></h5>

                            <div class="info-row">
                                <i class="bi bi-calendar-check-fill"></i>
                                <span><?= !empty($d['jadwal_hari']) ? $d['jadwal_hari'] : 'Senin - Jumat'; ?></span>
                            </div>
                            <div class="info-row">
                                <i class="bi bi-clock-fill"></i>
                                <span><?= !empty($d['jadwal_jam']) ? $d['jadwal_jam'] : '08:00 - 16:00'; ?> WIB</span>
                            </div>

                            <div class="biaya-section">
                                <div>
                                    <div class="biaya-label">Biaya Konsultasi</div>
                                    <div class="biaya-value">
                                        Rp <?= number_format($d['biaya_konsultasi'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                            </div>

                            <a href="reservasiform.php?dokter_id=<?= $d['dokter_id'] ?>" class="btn btn-primary btn-janji shadow-sm">
                                Buat Janji Temu <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ($totalData == 0) { ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-frown fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">Maaf, dokter tidak ditemukan.</h5>
                    <a href="daftardokter.php" class="btn btn-outline-primary mt-2 rounded-pill">Lihat Semua Dokter</a>
                </div>
            <?php } ?>
        </div>

        <?php if ($totalPage > 1): ?>
            <div class="d-flex justify-content-center mt-5 mb-5">
                <nav>
                    <ul class="pagination">
                        <?php if ($page > 1) { ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= $search ?>"><i class="bi bi-chevron-left"></i></a></li>
                        <?php } ?>

                        <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a></li>
                        <?php } ?>

                        <?php if ($page < $totalPage) { ?>
                            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= $search ?>"><i class="bi bi-chevron-right"></i></a></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>