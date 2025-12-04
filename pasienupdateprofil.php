<?php
session_start();
include "koneksi.php";

// Proteksi
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "pasien") {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_pengguna'];

$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ubah Profil Pasien</title>

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
        text-decoration: none;
        font-weight: bold;
    }

    .profile-image { text-align: center; margin-bottom: 20px; }

    .profile-image img { width: 140px; }

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

<div class="navbar">UBAH PROFIL PASIEN</div>

<div class="wrapper">
    <div class="profile-box">

        <div class="back"><a href="pasienprofil.php"><i class="bi bi-arrow-left-square-fill"></i> Kembali</a></div>

        <div class="profile-image">
            <img src="profil.png">
        </div>

        <form method="POST">

            <div class="form-group">Username
                <input type="text" name="username" value="<?php echo $data['username']; ?>" required>
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

    $username  = $_POST['username'];
    $email     = $_POST['email'];
    $no_telpon = $_POST['no_telpon'];
    $password  = $_POST['password'];

    // Cek duplikasi username
    $cek = mysqli_query($koneksi,
        "SELECT * FROM pengguna WHERE username='$username' AND id_pengguna!='$id'");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!');</script>";
        exit;
    }

    if ($password == "") {
        $update = mysqli_query($koneksi,
            "UPDATE pengguna SET username='$username', email='$email', no_telpon='$no_telpon'
             WHERE id_pengguna='$id'");
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_query($koneksi,
            "UPDATE pengguna SET username='$username', email='$email', no_telpon='$no_telpon', password='$hash'
             WHERE id_pengguna='$id'");
    }

    $_SESSION['username'] = $username;

    echo "<script>alert('Profil pasien berhasil diperbarui!');window.location='pasienprofil.php';</script>";
}
?>
