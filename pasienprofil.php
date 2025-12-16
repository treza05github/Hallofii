<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$usernameSession = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$usernameSession'");
$data = mysqli_fetch_assoc($query);

$username  = $data['username'];
$email     = $data['email'];
$no_telpon = $data['no_telpon'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Pasien</title>
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
            background: linear-gradient(135deg, #0d6efd, #6610f2);
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

        .info-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .label-text {
            font-size: 0.85rem;
            color: #888;
            font-weight: 600;
            text-transform: uppercase;
        }

        .value-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <a href="pasien.php" class="text-decoration-none text-muted mb-3 d-inline-block fw-bold">
                    <i class="bi bi-arrow-left"></i> Dashboard Pasien
                </a>

                <div class="card profile-card">
                    <div class="profile-header"></div>
                    <div class="card-body text-center pt-0 pb-4">
                        <img src="profil.png" class="profile-avatar shadow-sm">
                        <h3 class="fw-bold mt-3 mb-1"><?= strtoupper($username) ?></h3>
                        <p class="text-muted">Member Pasien</p>

                        <div class="text-start px-3 mt-4">
                            <div class="info-item">
                                <div class="label-text">Email</div>
                                <div class="value-text"><?= $email ?></div>
                            </div>
                            <div class="info-item">
                                <div class="label-text">Nomor Telepon</div>
                                <div class="value-text"><?= $no_telpon ?></div>
                            </div>
                        </div>

                        <div class="row g-2 mt-4 px-3">
                            <div class="col-6">
                                <a href="pasienupdateprofil.php" class="btn btn-primary w-100 rounded-pill fw-bold">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                            </div>
                            <div class="col-6">
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
                                            confirmButtonColor: '#e03131', // Warna merah untuk logout
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
    </div>

</body>

</html>