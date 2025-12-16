<?php
session_start();
include "koneksi.php";

// 1. CEK LOGIN PASIEN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "pasien") {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_pengguna'];
$alertMsg = "";
$alertType = "";

// 2. PROSES UPDATE
if (isset($_POST['update'])) {
    $username  = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email     = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_telpon = mysqli_real_escape_string($koneksi, $_POST['no_telpon']);
    $password  = $_POST['password'];

    // Cek username kembar (Kecuali punya sendiri)
    $cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username' AND id_pengguna!='$id'");

    if (mysqli_num_rows($cek) > 0) {
        $alertType = "error";
        $alertMsg = "Username sudah digunakan oleh orang lain!";
    } else {
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE pengguna SET username='$username', email='$email', no_telpon='$no_telpon', password='$hash' WHERE id_pengguna='$id'";
        } else {
            $query = "UPDATE pengguna SET username='$username', email='$email', no_telpon='$no_telpon' WHERE id_pengguna='$id'";
        }

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['username'] = $username; // Update session
            $alertType = "success";
            $alertMsg = "Profil Anda berhasil diperbarui!";
        } else {
            $alertType = "error";
            $alertMsg = "Gagal update: " . mysqli_error($koneksi);
        }
    }
}

// 3. AMBIL DATA PASIEN
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Profil Pasien</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0d6efd, #0099ff);
            padding: 15px 0;
        }

        .card-form {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 40px;
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border: none;
            border-radius: 50px;
            padding: 12px 40px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: 0.3s;
        }

        .btn-confirm:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .user-photo {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid white;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-custom navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-person-lines-fill me-2"></i> EDIT PROFIL</a>
            <div class="d-flex align-items-center">
                <span class="text-white small fw-bold">Halo, <?= strtoupper($data['username']); ?></span>
                <img src="profil.png" class="user-photo">
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mt-4 mb-2">
                    <a href="pasienprofil.php" class="text-decoration-none text-secondary fw-bold"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>

                <div class="card-form">
                    <h3 class="fw-bold text-center mb-4" style="color: #0d6efd;">Perbarui Data Diri</h3>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="fw-bold mb-1">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= $data['username']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-1">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $data['email']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-1">No Telpon</label>
                            <input type="text" name="no_telpon" class="form-control" value="<?= $data['no_telpon']; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-1 text-danger">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ganti password">
                        </div>

                        <button type="submit" name="update" class="btn-confirm">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        <?php if ($alertMsg != "") { ?>
            Swal.fire({
                title: '<?= ($alertType == "success") ? "Berhasil!" : "Gagal!" ?>',
                text: '<?= $alertMsg ?>',
                icon: '<?= $alertType ?>',
                confirmButtonColor: '#0d6efd'
            }).then(() => {
                <?php if ($alertType == "success") { ?>
                    window.location = 'pasienprofil.php';
                <?php } ?>
            });
        <?php } ?>
    </script>

</body>

</html>