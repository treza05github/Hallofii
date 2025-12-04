<?php
session_start();
include "koneksi.php";

// Cek login sebagai pasien
if (!isset($_SESSION['role']) || $_SESSION['role'] != "pasien") {
    header("Location: login.php");
    exit;
}

// Ambil data pasien
$username = $_SESSION['username'];
$pasien = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username'"));

// PAGINATION
$limit = 6;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// SEARCH
$search = isset($_GET['search']) ? $_GET['search'] : "";
$filter = ($search != "") ?
    "WHERE nama_dokter LIKE '%$search%' OR spesialisasi LIKE '%$search%'" : "";

// TOTAL DATA
$totalQ = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dokter $filter");
$totalData = mysqli_fetch_assoc($totalQ)['total'];
$totalPage = ceil($totalData / $limit);

// QUERY DATA
$query = mysqli_query($koneksi, "
    SELECT * FROM dokter
    $filter
    ORDER BY dokter_id DESC
    LIMIT $start, $limit
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Daftar Dokter</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f5f5f5;
        }

        .navbar-custom {
            background: #648db5;
            padding: 20px;
        }

        .patient-photo {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }

        .doctor-card {
            border-radius: 10px;
            overflow: hidden;
            transition: 0.2s;
            background: white;
        }

        .doctor-card:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .doctor-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #e3e3e3;
        }

        .search-box {
            border: 2px solid #b98989;
            padding: 7px;
            border-radius: 7px;
            width: 230px;
        }

        .btn-search {
            background: #b98989;
            color: black;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-custom navbar-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h3 class="text-white fw-bold">DAFTAR DOKTER</h3>

            <div class="d-flex align-items-center">
                <span class="text-white fw-bold me-2"><?= strtoupper($pasien['username']); ?></span>
                <a href="pasienprofil.php"><img src="profil.png" class="patient-photo"></a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <!-- BACK -->
        <a href="pasien.php" style="font-size:20px; text-decoration:none; color:black;">
            <i class="bi bi-arrow-left-square-fill"></i> Daftar Dokter
        </a>
        <hr>

        <!-- SEARCH -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Cari Dokter</h4>

            <form class="d-flex" method="GET">
                <input type="text" name="search" class="search-box me-2" placeholder="Cari dokter..." value="<?= $search; ?>">
                <button class="btn btn-search"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <hr>

        <!-- LIST DOKTER CARD -->
        <div class="row">
            <?php while ($d = mysqli_fetch_assoc($query)) { ?>

                <div class="col-md-4 mb-4">
                    <div class="card doctor-card shadow-sm">

                        <!-- FOTO DOKTER -->
                        <?php if ($d['foto_dokter'] != NULL) { ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($d['foto_dokter']); ?>" class="doctor-img">
                        <?php } else { ?>
                            <img src="noimage.png" class="doctor-img">
                        <?php } ?>

                        <!-- CARD BODY -->
                        <div class="p-3">
                            <h5 class="fw-bold"><?= $d['nama_dokter']; ?></h5>
                            <p class="mb-1"><b>Spesialisasi:</b> <?= $d['spesialisasi']; ?></p>
                        </div>

                    </div>
                </div>

            <?php } ?>

            <?php if ($totalData == 0) { ?>
                <p class="text-center text-danger mt-3">Tidak ada dokter ditemukan.</p>
            <?php } ?>
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-end mt-3">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                    <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>

    </div>

</body>

</html>