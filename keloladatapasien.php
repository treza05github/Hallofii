<?php
session_start();
include "koneksi.php";

// CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// Ambil data admin yang sedang login
$username = $_SESSION['username'];
$queryAdmin = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$username'");
$admin = mysqli_fetch_assoc($queryAdmin);

// PAGINATION
$limit = 5; // jumlah baris per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// SEARCH
$search = isset($_GET['search']) ? $_GET['search'] : "";
$filter = ($search != "") ? "AND (username LIKE '%$search%' OR email LIKE '%$search%' OR no_telpon LIKE '%$search%')" : "";

// TOTAL DATA PASIEN
$totalQuery = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total FROM pengguna 
    WHERE username != 'admin' $filter
");

$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage = ceil($totalData / $limit);

// DATA PASIEN
$query = mysqli_query($koneksi, "
    SELECT * FROM pengguna 
    WHERE username != 'admin' $filter
    ORDER BY id_pengguna DESC
    LIMIT $start, $limit
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Pasien</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f5f5f5;
        }

        .navbar-custom {
            background: #648db5;
            padding: 18px 30px;
        }

        .admin-photo {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }

        .btn-blue {
            background: #0f62fe;
            color: white;
            font-weight: bold;
        }

        .search-box {
            border: 2px solid #b98989;
            padding: 7px;
            width: 200px;
            border-radius: 6px;
        }

        .btn-search {
            background: #b98989;
            padding: 10px 16px;
            color: black;
            border-radius: 6px;
            font-weight: bold;
        }

        .table-header {
            background: #dadada;
            font-weight: bold;
        }

        table td {
            padding: 14px;
            background: #dcdcdc;
        }

        .btn-hapus {
            background: #e03131;
            color: white;
        }

        .pagination .active > span {
            background: #0f62fe !important;
            color: white !important;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-custom navbar-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="text-white fw-bold">KELOLA DATA PASIEN</h3>

        <div class="d-flex align-items-center">
            <span class="text-white fw-bold me-2"><?= strtoupper($admin['username']); ?></span>
            <a href="adminprofil.php">
                <img src="profil.png" class="admin-photo">
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <!-- TOP TITLE -->
    <div class="d-flex align-items-center mb-3">
        <a href="admin.php" class="me-3" style="font-size:24px; text-decoration:none; color:black;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
  <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1"/>
</svg></a>
        <h3 class="fw-bold">Data Pasien</h3>
    </div>

    <hr>

    <!-- SEARCH -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5 class="fw-bold text-dark">Daftar Pasien</h5>

        <form method="GET" class="d-flex">
            <input type="text" name="search" class="search-box me-2" placeholder="Cari Pasien....." value="<?= $search ?>">
            <button class="btn-search"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
</svg></button>
        </form>
    </div>

    <hr>

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered text-center">

            <thead class="table-header">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>No Telpon</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $no = $start + 1;
                while ($row = mysqli_fetch_assoc($query)) { 
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['username']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['no_telpon']; ?></td>
                    <td>
                        <a href="pasienhapus.php?id=<?= $row['id_pengguna'] ?>" 
                           onclick="return confirm('Yakin ingin menghapus data pasien ini?')" 
                        >
                           <i class="bi bi-trash delete"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-end">

        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
                </li>
            <?php } ?>
        </ul>

    </div>

</div>

</body>
</html>
