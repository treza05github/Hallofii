<!-- fungsi registrasi -->
<?php
include "koneksi.php";

if (isset($_POST['registrasi'])) {

    // Cegah membuat akun admin
    if ($_POST['username'] == "admin" || $_POST['email'] == "admin@gmail.com") {
        echo "<script>alert('Username atau Email ini dikhususkan untuk admin!');</script>";
        exit;
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $no_telpon = $_POST['no_telpon'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO pengguna (username, email, no_telpon, password)
              VALUES ('$username', '$email', '$no_telpon', '$password')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Registrasi berhasil!');
                window.location='login.php';
              </script>";
    } else {
        echo "<script>alert('Registrasi gagal!');</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi | HalloFii</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom right, #e0f7fa, #ffffff);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
    }

    /* Background elemen */
    .bubble {
      position: absolute;
      border-radius: 50%;
      background: rgba(173, 216, 230, 0.4);
      animation: float 10s infinite ease-in-out;
    }

    .bubble:nth-child(1) {
      width: 100px;
      height: 100px;
      top: 10%;
      left: 15%;
      animation-delay: 0s;
    }

    .bubble:nth-child(2) {
      width: 150px;
      height: 150px;
      bottom: 15%;
      right: 10%;
      animation-delay: 3s;
    }

    .bubble:nth-child(3) {
      width: 80px;
      height: 80px;
      bottom: 25%;
      left: 25%;
      animation-delay: 5s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); opacity: 0.8; }
      50% { transform: translateY(-20px); opacity: 1; }
    }

    /* Kotak Login */
    .login-container {
      position: relative;
      z-index: 2;
      width: 360px;
      padding: 40px;
      background: #ffffffcc;
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: fadeIn 1.2s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .logo {
      font-size: 34px;
      font-weight: 700;
      color: #00aaff;
      margin-bottom: 5px;
    }

    .tagline {
      font-size: 14px;
      color: #555;
      margin-bottom: 25px;
    }

    .input-box {
      margin: 15px 0;
    }

    .input-box input {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 2px solid #aee1f9;
      font-size: 16px;
      outline: none;
      transition: all 0.3s;
    }

    .input-box input:focus {
      border-color: #00aaff;
      box-shadow: 0 0 10px #b3ecff;
    }

    button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(90deg, #00b4d8, #0096c7);
      border: none;
      border-radius: 10px;
      color: #fff;
      font-size: 18px;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 10px;
    }

    button:hover {
      background: linear-gradient(90deg, #0096c7, #0077b6);
      transform: scale(1.03);
    }

    .footer-text {
      margin-top: 20px;
      font-size: 13px;
      color: #777;
    }

    .footer-text a {
      color: #00aaff;
      text-decoration: none;
    }

    .footer-text a:hover {
      text-decoration: underline;
    }

    /* Ikon medis animasi */
    .pulse-icon {
      width: 50px;
      height: 50px;
      margin: 0 auto 10px auto;
      background: #00aaff;
      mask: url('https://cdn-icons-png.flaticon.com/512/2966/2966486.png') no-repeat center;
      mask-size: contain;
      animation: pulse 1.8s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.9; }
      50% { transform: scale(1.15); opacity: 1; }
    }
  </style>
</head>
<body>
  <!-- Elemen background -->
  <div class="bubble"></div>
  <div class="bubble"></div>
  <div class="bubble"></div>

  <!-- Kotak login -->
  <div class="login-container">
    <div class="pulse-icon"></div>
    <div class="logo">HalloFii</div>
    <div class="tagline">Layanan Reservasi & Konsultasi</div>

    <form action="" method="post">
      <div class="input-box">
        <input type="text" id="username" name="username" placeholder="Username" required>
      </div>
      <div class="input-box">
        <input type="text" id="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-box">
        <input type="text" id="no_telpon" name="no_telpon" placeholder="Nomor Telpon" required>
      </div>
      <div class="input-box">
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>
      <button type="submit" name="registrasi" value="registrasi">Registrasi</button>
    </form>

    <div class="footer-text">
      Sudah punya akun? <a href="login.php">Login</a>
    </div>
  </div>

  
</body>
</html>
