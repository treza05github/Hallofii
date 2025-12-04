<?php
session_start();
include "koneksi.php";

// CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = $_POST['nama_dokter'];
    $spesialisasi = $_POST['spesialisasi'];

    // HANDLE FOTO
    $foto = NULL;

    if (!empty($_FILES['foto_dokter']['tmp_name'])) {
        $foto = file_get_contents($_FILES['foto_dokter']['tmp_name']);
    }

    $stmt = $koneksi->prepare("INSERT INTO dokter (nama_dokter, spesialisasi, foto_dokter) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $spesialisasi, $foto);

    if ($stmt->execute()) {
        echo "<script>alert('Data dokter berhasil ditambahkan!'); window.location='keloladatadokter.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #ffffff;
        }

        .navbar-custom {
            background: #648db5;
            padding: 18px 30px;
        }

        .admin-photo {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
        }

        .form-box {
            background: #5fb3c5;
            width: 55%;
            padding: 40px;
            border-radius: 6px;
            margin: auto;
            margin-top: 30px;
        }

        .form-label {
            font-weight: bold;
            font-size: 18px;
        }

        .btn-confirm {
            background: #2f9e44;
            color: white;
            font-weight: bold;
            padding: 10px 30px;
            font-size: 18px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-custom navbar-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <h3 class="text-white fw-bold">KELOLA DATA DOKTER</h3>

        <div class="d-flex align-items-center">
            <span class="text-white fw-bold me-3">ADMIN</span>
            <img src="profil.png" class="admin-photo">
        </div>
    </div>
</nav>

<div class="container mt-4">

    <div class="d-flex align-items-center mb-3">
        <a href="keloladatadokter.php" class="me-3" style="font-size:30px; text-decoration:none; color:black;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                    <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                </svg>
        </a>
        <h3 class="fw-bold">Form Tambah Dokter</h3>
    </div>

    <hr>

    <!-- FORM BOX -->
    <div class="form-box shadow">

        <form method="POST" enctype="multipart/form-data">

            <!-- NAMA -->
            <label class="form-label">Nama Dokter</label>
            <input type="text" name="nama_dokter" class="form-control mb-3" required>

            <!-- SPESIALISASI -->
            <label class="form-label">Spesialisasi</label>
            <input type="text" name="spesialisasi" class="form-control mb-3" required>

            <!-- FOTO -->
            <label class="form-label">Foto Dokter</label>
            <input type="file" name="foto_dokter" class="form-control mb-4">

            <!-- BUTTON -->
            <div class="text-center">
                <button type="submit" class="btn-confirm">Konfirmasi</button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
