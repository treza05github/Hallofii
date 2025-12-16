<?php
session_start();
include "koneksi.php";

// CEK LOGIN
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$username'");
$data = mysqli_fetch_assoc($query);
$username = $data['username'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        .profile-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
        }

        .profile-header {
            background: linear-gradient(135deg, #0f4c75, #3282b8);
            height: 160px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
            margin-top: -75px;
            background: white;
        }

        .btn-action {
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            transition: 0.3s;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <a href="admin.php" class="text-decoration-none text-muted mb-3 d-inline-block fw-bold">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>

                <div class="card profile-card">
                    <div class="profile-header"></div>
                    <div class="card-body text-center pt-0 pb-5">
                        <img src="profil.png" class="profile-avatar shadow-sm">
                        <h3 class="fw-bold mt-3 mb-1"><?= strtoupper($username) ?></h3>
                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3">Administrator</span>

                        <div class="info-box text-start">
                            <small class="text-muted fw-bold d-block mb-1">USERNAME</small>
                            <span class="fs-5 text-dark"><?= $username ?></span>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <a href="adminupdateprofil.php" class="btn btn-success btn-action">
                                <i class="bi bi-pencil-square me-2"></i> Edit Profil
                            </a>
                            <a href="logout.php" class="btn logout-btn" onclick="konfirmasiLogout(event)">
                                Logout <i class="bi bi-box-arrow-right"></i>
                            </a>

                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                            <script>
                                function konfirmasiLogout(event) {
                                    event.preventDefault(); // Cegah pindah halaman langsung
                                    const link = event.currentTarget.getAttribute('href'); // Ambil link logout.php

                                    Swal.fire({
                                        title: 'Yakin ingin keluar?',
                                        text: "Sesi Anda akan diakhiri.",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#e03131', 
                                        cancelButtonColor: '#888',
                                        confirmButtonText: 'Ya, Keluar',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Jika user klik Ya, arahkan ke logout.php
                                            window.location.href = link;
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>