<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$q = mysqli_query($koneksi, "SELECT * FROM reservasi WHERE reservasi_id='$id'");
$data = mysqli_fetch_assoc($q);

$dokter = mysqli_query($koneksi, "SELECT * FROM dokter");
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Reservasi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
        .navbar-custom { background: #648db5; padding: 18px 30px; }
        .profile-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">

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
    <div class="card p-4 shadow">
        <a href="reservasi.php" style="text-decoration:none; color:black; font-size:25px; font-weight:bold;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                    <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                </svg> Edit Reservasi</a>

        <form action="reservasiedit_function.php" method="POST">
            <input type="hidden" name="id" value="<?= $data['reservasi_id'] ?>">

            <label>Dokter</label>
            <select name="dokter_id" class="form-control mb-3">
                <?php while ($d = mysqli_fetch_assoc($dokter)) { ?>
                    <option value="<?= $d['dokter_id'] ?>" 
                        <?= $d['dokter_id']==$data['dokter_id'] ? 'selected':'' ?>>
                        <?= $d['nama_dokter'] ?> (<?= $d['spesialisasi'] ?>)
                    </option>
                <?php } ?>
            </select>

            <label>Layanan & Spesialisasi</label>
            <input type="text" name="layanan_spesialis" class="form-control mb-3"
                   value="<?= $data['layanan_spesialis'] ?>">

            <label>Tanggal & Waktu</label>
            <input type="datetime-local" name="tanggal_waktu" class="form-control mb-3"
                   value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_waktu'])) ?>">

            <button class="btn btn-primary">Simpan Perubahan</button>
        </form>

    </div>
</div>

</body>
</html>
