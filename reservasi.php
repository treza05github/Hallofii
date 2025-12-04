<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit;
}
$id_pengguna = $_SESSION['id_pengguna'];
// SEARCH
$keyword = "";
if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
}
// PAGINATION
$limit = 5; // jumlah baris per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
// HITUNG TOTAL DATA
$totalQuery = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM reservasi r
    JOIN dokter d ON r.dokter_id = d.dokter_id
    WHERE r.id_pengguna = '$id_pengguna'
    AND (
        d.nama_dokter LIKE '%$keyword%' OR
        r.layanan_spesialis LIKE '%$keyword%' OR
        r.status LIKE '%$keyword%' OR
        r.tanggal_waktu LIKE '%$keyword%'
    )
");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage = ceil($totalData / $limit);
// AMBIL DATA RESERVASI USER
$query = mysqli_query($koneksi, "
    SELECT r.*, d.nama_dokter, d.spesialisasi, p.username, p.no_telpon
    FROM reservasi r
    JOIN dokter d ON r.dokter_id = d.dokter_id
    JOIN pengguna p ON r.id_pengguna = p.id_pengguna
    WHERE r.id_pengguna = '$id_pengguna'
    AND (
        d.nama_dokter LIKE '%$keyword%' OR
        r.layanan_spesialis LIKE '%$keyword%' OR
        r.status LIKE '%$keyword%' OR
        r.tanggal_waktu LIKE '%$keyword%'
    )
    ORDER BY r.reservasi_id DESC
    LIMIT $start, $limit
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Halaman Reservasi</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

     <!-- Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <style>
        body { background: #f5f5f5; }
        .navbar-custom { background: #648db5; padding: 18px 30px; }
        .table-header { background: #dadada; font-weight:bold; }
        .btn-print { color:black; font-size:20px; }
        .btn-edit { color:#000; font-size:20px; }
        .btn-delete { color:red; font-size:20px; }
        .profile-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
        }
        .search-box {
            width: 260px;
            border: 2px solid #bfbfbf;
            padding: 8px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-custom navbar-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="text-white fw-bold">HALAMAN RESERVASI</h3>

        <div class="text-white fw-bold d-flex align-items-center">
            <?= strtoupper($_SESSION['username']); ?>&nbsp;
            <a href="pasienprofil.php">
                <img src="profil.png" class="profile-icon">
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <!-- Back Button -->
    <a href="pasien.php" style="text-decoration:none; color:black; font-size:25px;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                    <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                </svg> Halaman Reservasi</a>
    <hr>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tombol Buat Reservasi -->
    <a href="reservasiform.php" class="btn btn-primary mb-3">Buat Reservasi</a>

    <!-- FORM SEARCH -->
    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="search-box me-2" 
               placeholder="Cari Reservasi" 
               value="<?= $keyword ?>">
        <button class="btn btn-secondary">Cari</button>
    </form>
    </div>
    

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-header">
                <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Nomor Telpon</th>
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

                    <td>
                        <?php if ($row['status'] == "menunggu dikonfirmasi") { ?>
                            <span class="badge bg-warning text-dark">Menunggu Dikonfirmasi</span>
                        <?php } elseif ($row['status'] == "reservasi berhasil") { ?>
                            <span class="badge bg-success">Reservasi Berhasil</span><br>
                            <small class="text-success">Segera lakukan check-in di klinik atau hubungi 08123456</small>
                        <?php } else { ?>
                            <span class="badge bg-danger">Reservasi Dibatalkan</span>
                        <?php } ?>
                    </td>

                    <td>
                        <a href="reservasiedit.php?id=<?= $row['reservasi_id']; ?>" ><i class="bi bi-pencil-square edit"></i></a>
                        <a href="reservasihapus.php?id=<?= $row['reservasi_id']; ?>" onclick="return confirm('Batalkan reservasi?')"><i class="bi bi-trash delete"></i></a>
                        <a href="reservasicetak.php?id=<?= $row['reservasi_id']; ?>" target="_blank"><i class="bi bi-printer"></i></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-end mt-3">
        <ul class="pagination">

            <!-- PREV -->
            <?php if ($page > 1) { ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page-1 ?>&search=<?= $keyword ?>">«</a>
                </li>
            <?php } ?>

            <!-- NOMOR HALAMAN -->
            <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= $keyword ?>"><?= $i ?></a>
                </li>
            <?php } ?>

            <!-- NEXT -->
            <?php if ($page < $totalPage) { ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page+1 ?>&search=<?= $keyword ?>">»</a>
                </li>
            <?php } ?>

        </ul>
    </div>

</div>

</body>
</html>
