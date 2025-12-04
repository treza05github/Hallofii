<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php");
    exit;
}

// Ambil data admin
$username = $_SESSION['username'];
$admin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username'"));

// PAGINATION
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// SEARCH
$search = isset($_GET['search']) ? $_GET['search'] : "";
$filter = ($search != "") ? 
"AND (p.username LIKE '%$search%' OR d.nama_dokter LIKE '%$search%' OR r.layanan_spesialis LIKE '%$search%')" 
: "";

// TOTAL DATA
$totalQ = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total 
    FROM reservasi r 
    JOIN pengguna p ON r.id_pengguna=p.id_pengguna
    JOIN dokter d ON r.dokter_id=d.dokter_id
    WHERE 1 $filter
");
$totalData = mysqli_fetch_assoc($totalQ)['total'];
$totalPage = ceil($totalData / $limit);

// DATA RESERVASI
$query = mysqli_query($koneksi, "
    SELECT r.*, p.username, p.no_telpon, d.nama_dokter, d.spesialisasi
    FROM reservasi r
    JOIN pengguna p ON r.id_pengguna = p.id_pengguna
    JOIN dokter d ON r.dokter_id = d.dokter_id
    WHERE 1 $filter
    ORDER BY r.reservasi_id DESC
    LIMIT $start, $limit
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Reservasi</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

     <!-- Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <style>
        body { background:#f5f5f5; }
        .navbar-custom { background:#648db5; padding:20px; }
        .admin-photo { width:45px; height:45px; border-radius:50%; object-fit:cover; }
        .table-header { background:#dadada; font-weight:bold; }
        .btn-edit { color:black; font-size:20px; }
        .btn-delete { color:red; font-size:20px; }
        .btn-print { color:black; font-size:20px; }
        .search-box {
            border:2px solid #b98989;
            padding:7px;
            border-radius:7px;
            width:200px;
        }
        .btn-search {
            background:#b98989;
            color:black;
            font-weight:bold;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-custom navbar-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="text-white fw-bold">KELOLA DATA RESERVASI</h3>

        <div class="d-flex align-items-center">
            <span class="text-white fw-bold me-2"><?= strtoupper($admin['username']); ?></span>
            <a href="adminprofil.php"><img src="profil.png" class="admin-photo"></a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <!-- BACK -->
    <a href="admin.php" style="font-size:24px; text-decoration:none; color:black;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                    <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                </svg> Kelola Reservasi</a>
    <hr>

    <!-- SEARCH -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Semua Reservasi</h4>

        <form class="d-flex" method="GET">
            <input type="text" name="search" class="search-box me-2" placeholder="Cari..." value="<?= $search; ?>">
            <button class="btn btn-search"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
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
                    <th>Nama Pasien</th>
                    <th>No Telpon</th>
                    <th>Dokter</th>
                    <th>Layanan & Spesialisasi</th>
                    <th>Tanggal & Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php 
            $no = $start + 1;
            while($row = mysqli_fetch_assoc($query)) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['username']; ?></td>
                    <td><?= $row['no_telpon']; ?></td>
                    <td><?= $row['nama_dokter']; ?></td>
                    <td><?= $row['layanan_spesialis']; ?></td>
                    <td><?= $row['tanggal_waktu']; ?></td>

                    <!-- STATUS -->
                    <td>
                        <?php if ($row['status'] == "menunggu dikonfirmasi") { ?>
                            <span class="badge bg-warning text-dark">Menunggu Dikonfirmasi</span>
                        <?php } elseif ($row['status'] == "reservasi berhasil") { ?>
                            <span class="badge bg-success">Reservasi Berhasil</span>
                        <?php } else { ?>
                            <span class="badge bg-danger">Reservasi Dibatalkan</span>
                        <?php } ?>
                    </td>

                    <td>
                        <a href="keloladatareservasiedit.php?id=<?= $row['reservasi_id']; ?>" ><i class="bi bi-pencil-square edit"></i></a>
                        <a href="keloladatareservasihapus.php?id=<?= $row['reservasi_id']; ?>" onclick="return confirm('Hapus reservasi ini?')" ><i class="bi bi-trash delete"></i></a>
                        <a href="reservasicetak.php?id=<?= $row['reservasi_id']; ?>" ><i class="bi bi-printer"></i></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>

    <!-- BUTTON UNTUK PRINT LAPORAN -->
    <div class="mt-3 text-end">
        <a href="keloladatareservasicetaklaporan.php" class="btn btn-print">
            <i class="bi bi-printer-fill"></i> Print Semua Reservasi
        </a>
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-end">
        <ul class="pagination">
            <?php for ($i=1; $i <= $totalPage; $i++) { ?>
            <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
            </li>
            <?php } ?>
        </ul>
    </div>

</div>

</body>
</html>
