<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data pengguna
$p = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$id_pengguna'"));

// Ambil data dokter
$dokter = mysqli_query($koneksi, "SELECT * FROM dokter");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .box {
            
            background: #59abc2;
            padding: 30px;
            border-radius: 12px;
            width: 60%;
            margin: auto;
        }
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

    <a href="reservasi.php" style="text-decoration:none; color:black; font-size:25px;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                    <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                </svg> Form Reservasi</a>
    <hr>

    <div class="box">
        <form action="reservasiform_function.php" method="post">
            
            <label>Nama</label>
            <input type="text" class="form-control mb-3" value="<?= $p['username']; ?>" readonly>

            <label>Nomor Telpon</label>
            <input type="text" class="form-control mb-3" value="<?= $p['no_telpon']; ?>" readonly>

            <label>Dokter</label>
            <select name="dokter_id" class="form-control mb-3">
                <?php while ($d = mysqli_fetch_assoc($dokter)) { ?>
                    <option value="<?= $d['dokter_id']; ?>">
                        <?= $d['nama_dokter']; ?> (<?= $d['spesialisasi']; ?>)
                    </option>
                <?php } ?>
            </select>

            <label>Layanan dan Spesialisasi</label>
            <input type="text" name="layanan" class="form-control mb-3" required>

            <label>Tanggal & Waktu Reservasi</label>
            <input type="datetime-local" name="tanggal" class="form-control mb-3" required>

            <button class="btn btn-success">Konfirmasi</button>
        </form>
    </div>

</div>

</body>
</html>
