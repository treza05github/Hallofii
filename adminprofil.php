<?php
session_start();
include "koneksi.php";

// CEK LOGIN
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil username dari session
$username = $_SESSION['username'];

// Ambil data admin dari DB
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username = '$username'");
$data = mysqli_fetch_assoc($query);

// Simpan data ke variabel
$username = $data['username'];
// password hash tidak boleh ditampilkan, jadi kosongkan saat tampil
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>

        <!-- Ikon Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* NAVBAR */
        .navbar {
            width: 100%;
            background: #648db5;
            padding: 15px 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* CONTAINER PROFIL */
        .wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            margin-top: 40px;
            padding: 10px;
        }

        .profile-box {
            width: 80%;
            max-width: 400px;
            background: #74a2d6;
            padding: 30px;
            border-radius: 12px;
            position: relative;
            z-index: 10;
        }

        .back {
            font-size: 18px;
            margin-bottom: 15px;
            cursor: pointer;
            font-weight: bold;
            color: white;
            display: inline-block;
            width: auto;
            z-index: 5;
        }

        .back:hover {
            opacity: 0.7;
        }

        .back a {
            color: white;
            text-decoration: none;
        }

        .profile-image {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-image img {
            width: 140px;
            max-width: 100%;
        }

        /* FORM */
        .form-group {
            margin: 12px 0;
            color: white;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            outline: none;
            background: #dcdcdc;
        }

        /* BUTTON AREA */
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn {
            padding: 12px 22px;
            border-radius: 8px;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 120px;
            text-decoration: none;
        }

        .logout-btn {
            background: #e03131;
        }

        .edit-btn {
            background: #2f9e44;
        }

        .btn:hover {
            opacity: 0.9;
            transform: scale(1.03);
            transition: 0.2s;
        }


        /* RESPONSIVE */
        @media (max-width: 480px) {
            .profile-box {
                padding: 20px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div>PROFIL ADMIN</div>
    </div>

    <!-- PROFILE BOX -->
    <div class="wrapper">
        <div class="profile-box">

            <div class="back">
                <a href="admin.php"><i class="bi bi-arrow-left-square-fill"></i> Kembali</a>
            </div>

            <div class="profile-image">
                <img src="profil.png">
            </div>

            <!-- FORM TAMPIL -->
            <div class="form-group">
                Username
                <input type="text" value="<?php echo $username; ?>" readonly>
            </div>

            <div class="button-group">

                <a href="logout.php" class="btn logout-btn">Logout <i class="bi bi-box-arrow-right"></i></a>

                <a href="adminupdateprofil.php" class="btn edit-btn">Ubah Profil <i class="bi bi-pencil-square edit"></i></a>

            </div>

        </div>
    </div>

</body>

</html>
