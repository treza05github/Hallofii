<?php
// session_start();

// if(!isset($_SESSION['username'])){
//     header("Location: login.php");
//     exit;
// }

// $username = $_SESSION['username'];

session_start();
// Proteksi: hanya admin yang boleh mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Ambil username dari session dengan pengecekan
$username = isset($_SESSION['username']) && $_SESSION['username'] !== '' 
            ? $_SESSION['username'] 
            : 'Admin';

// Pastikan variabel string sebelum memanggil strtoupper()
$displayName = is_string($username) ? strtoupper($username) : 'ADMIN';

?>

<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="navbar">
    <div>DASHBOARD ADMIN</div>
    <div class="right">
        <span><?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></span>
        <span><a href="adminprofil.php"><img src="profil.png" alt="profil" style="width: 30px;"></a></span>
    </div>
</div>

<div class="container">

    <div class="card">
        <img src="profil.png">
        <h3>Kelola Data Pasien</h3>
        <a href="keloladatapasien.php" class="akses-btn">Akses</a>
    </div>

    <div class="card">
        <img src="dokter.png">
        <h3>Kelola Data Dokter</h3>
        <a href="keloladatadokter.php" class="akses-btn">Akses</a>
    </div>

    <div class="card">
        <img src="reservasii.png">
        <h3>Kelola Reservasi</h3>
        <a href="keloladatareservasi.php" class="akses-btn">Akses</a>
    </div>


</div>


</body>
</html>
