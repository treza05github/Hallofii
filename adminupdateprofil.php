<?php
session_start();
include "koneksi.php";

// Proteksi: hanya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ubah Profil Admin</title>

<!-- Ikon Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .navbar {
        width: 100%;
        background: #648db5;
        padding: 15px 25px;
        color: white;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .wrapper {
        flex: 1;
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .profile-box {
        width: 90%;
        max-width: 450px;
        background: #74a2d6;
        padding: 30px;
        border-radius: 12px;
    }

    .back a {
        color: white;
        font-weight: bold;
        text-decoration: none;
    }

    .profile-image { text-align: center; margin-bottom: 20px; }

    .profile-image img {
        width: 140px;
    }

    .form-group { color: white; font-weight: bold; margin-bottom: 12px; }

    .form-group input {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: none;
        background: #dcdcdc;
        margin-bottom: 10px;
    }

    .button-group { display: flex; gap: 12px; margin-top: 20px; }

    .btn {
        padding: 12px;
        border-radius: 8px;
        border: none;
        color: white;
        font-weight: bold;
        cursor: pointer;
        flex: 1;
        text-align: center;
        text-decoration: none;
    }

    .save-btn { background: #2f9e44; }
    .back-btn { background: #b07b7a; }
</style>
</head>

<body>

<div class="navbar">UBAH PROFIL ADMIN</div>

<div class="wrapper">

    <div class="profile-box">

        <div class="back">
            <a href="adminprofil.php"><i class="bi bi-arrow-left-square-fill"></i> Kembali</a>
        </div>

        <div class="profile-image">
            <img src="profil.png">
        </div>

        <form method="POST">

            <div class="form-group">Username (tidak bisa diubah)
                <input type="text" value="<?php echo $data['username']; ?>" readonly>
            </div>

            <div class="form-group">Email
                <input type="text" name="email" value="<?php echo $data['email']; ?>" required>
            </div>

            <div class="form-group">No Telpon
                <input type="text" name="no_telpon" value="<?php echo $data['no_telpon']; ?>" required>
            </div>

            <div class="form-group">Password Baru (opsional)
                <input type="password" name="password" placeholder="Isi jika ingin ganti password">
            </div>

            <div class="button-group">
                <button type="submit" name="update" class="btn save-btn">Simpan Perubahan</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>

<?php
if (isset($_POST['update'])) {

    $email = $_POST['email'];
    $no_telpon = $_POST['no_telpon'];
    $password = $_POST['password'];

    if ($password == "") {
        $update = mysqli_query($koneksi, 
            "UPDATE pengguna SET email='$email', no_telpon='$no_telpon' WHERE username='$username'");
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_query($koneksi, 
            "UPDATE pengguna SET email='$email', no_telpon='$no_telpon', password='$hash' WHERE username='$username'");
    }

    echo "<script>alert('Profil berhasil diperbarui!');window.location='adminprofil.php';</script>";
}
?>
