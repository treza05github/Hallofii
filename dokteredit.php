<?php
session_start();
include "koneksi.php";

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// Cek dokter_id
if (!isset($_GET['id'])) {
    echo "<script>alert('ID Dokter tidak ditemukan!'); window.location='keloladokter.php';</script>";
    exit;
}

$id = $_GET['id'];

// Ambil data dokter
$query = mysqli_query($koneksi, "SELECT * FROM dokter WHERE dokter_id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data dokter tidak ditemukan!'); window.location='keloladokter.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokter</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f5f5f5;
        }

        .navbar-custom {
            background: #648db5;
            padding: 15px 30px;
        }

        .wrapper-box {
            background: #54a8c0;
            width: 60%;
            max-width: 650px;
            margin: auto;
            padding: 35px;
            border-radius: 12px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
        }

        .btn-green {
            background: #0c8e00;
            color: white;
            font-weight: bold;
            padding: 10px 25px;
            border-radius: 8px;
        }

        .btn-green:hover {
            opacity: 0.8;
        }

        .form-label {
            font-weight: bold;
            font-size: 18px;
            color: white;
        }

        .img-preview {
            width: 120px;
            border-radius: 10px;
            margin-bottom: 10px;
            border: 2px solid white;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-custom navbar-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="text-white fw-bold">KELOLA DATA DOKTER</h3>

        <div class="d-flex align-items-center">
            <span class="text-white fw-bold me-2"><?= strtoupper($_SESSION['username']); ?></span>
            <img src="profil.png" width="42" height="42" style="border-radius:50%; border:2px solid white;">
        </div>
    </div>
</nav>

<!-- KONTEN -->
<div class="container mt-4">

    <!-- TOP TITLE -->
        <div class="d-flex align-items-center mb-3">
            <a href="keloladatadokter.php" class="me-3" style="font-size:24px; text-decoration:none; color:black;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                    <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                </svg>
            </a>
            <h3 class="fw-bold">Form Edit Dokter</h3>
        </div>

    
    <hr>

    <div class="wrapper-box shadow-lg">

        <form action="dokteredit_function.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="dokter_id" value="<?= $data['dokter_id']; ?>">

            <label class="form-label">Nama Dokter</label>
            <input type="text" name="nama_dokter" class="form-control mb-3" 
                   value="<?= $data['nama_dokter']; ?>" required>

            <label class="form-label">Spesialisasi</label>
            <input type="text" name="spesialisasi" class="form-control mb-3"
                   value="<?= $data['spesialisasi']; ?>" required>

            <label class="form-label">Foto Dokter</label>
            <br>

            <!-- Preview Foto -->
            <img src="data:image/jpeg;base64,<?= base64_encode($data['foto_dokter']); ?>" 
                 class="img-preview">

            <input type="file" name="foto_dokter" class="form-control mb-4">

            <div class="text-center">
                <button type="submit" class="btn-green">Perbarui Data</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>


