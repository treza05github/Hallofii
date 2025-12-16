<?php
session_start();
include "koneksi.php";

$errorMsg = "";

if (isset($_POST['login'])) {
    $input_login = $_POST['username']; // Bisa diisi Username ATAU Email
    $password = $_POST['password'];

    // 1. GUNAKAN PREPARED STATEMENT (Lebih Aman dari SQL Injection)
    // Cek apakah input cocok dengan kolom 'username' ATAU 'email'
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM pengguna WHERE username=? OR email=?");
    mysqli_stmt_bind_param($stmt, "ss", $input_login, $input_login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    // 2. VALIDASI KEBERADAAN AKUN
    if ($data) {
        // Akun ditemukan, sekarang cek password
        if (password_verify($password, $data['password'])) {
            
            // 3. SET SESSION
            $_SESSION['id_pengguna'] = $data['id_pengguna'];
            $_SESSION['username']    = $data['username'];
            $_SESSION['role']        = $data['role'];

            // 4. CEK ROLE & REDIRECT
            if ($data['role'] == "admin") {
                header("Location: admin.php"); 
            } else {
                header("Location: pasien.php");
            }
            exit;
            
        } else {
            $errorMsg = "Password yang Anda masukkan salah!";
        }
    } else {
        // Jika data tidak ditemukan di database
        $errorMsg = "Username atau Email tidak terdaftar di sistem kami.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | HalloFii</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { margin: 0; padding: 0; font-family: 'Poppins', sans-serif; background: linear-gradient(to bottom right, #e0f7fa, #ffffff); height: 100vh; display: flex; justify-content: center; align-items: center; overflow: hidden; position: relative; }
    .login-container { position: relative; z-index: 2; width: 360px; padding: 40px; background: #ffffffcc; backdrop-filter: blur(10px); border-radius: 20px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); text-align: center; }
    .logo { font-size: 34px; font-weight: 700; color: #00aaff; margin-bottom: 5px; }
    .tagline { font-size: 14px; color: #555; margin-bottom: 25px; }
    .input-box { margin: 15px 0; }
    .input-box input { width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #aee1f9; font-size: 16px; outline: none; transition: all 0.3s; }
    .input-box input:focus { border-color: #00aaff; box-shadow: 0 0 10px #b3ecff; }
    button { width: 100%; padding: 12px; background: linear-gradient(90deg, #00b4d8, #0096c7); border: none; border-radius: 10px; color: #fff; font-size: 18px; cursor: pointer; transition: 0.3s; margin-top: 10px; }
    button:hover { background: linear-gradient(90deg, #0096c7, #0077b6); transform: scale(1.03); }
    .footer-text { margin-top: 20px; font-size: 13px; color: #777; }
    .footer-text a { color: #00aaff; text-decoration: none; }
    .pulse-icon { width: 50px; height: 50px; margin: 0 auto 10px auto; background: #00aaff; mask: url('https://cdn-icons-png.flaticon.com/512/2966/2966486.png') no-repeat center; mask-size: contain; animation: pulse 1.8s infinite; }
    @keyframes pulse { 0%, 100% { transform: scale(1); opacity: 0.9; } 50% { transform: scale(1.15); opacity: 1; } }
  </style>
</head>
<body>
  
  <div class="login-container">
    <div class="pulse-icon"></div>
    <div class="logo">HalloFii</div>
    <div class="tagline">Layanan Reservasi & Konsultasi</div>

    <form action="" method="post">
      <div class="input-box">
          <input type="text" name="username" placeholder="Username atau Email" required>
      </div>
      <div class="input-box">
          <input type="password" name="password" placeholder="Password" required>
      </div>
      <button type="submit" name="login" value="login">Masuk</button>
    </form>

    <div class="footer-text">
      Belum punya akun? <a href="registrasi.php">Daftar di sini</a>
      <hr>
      Kembali ke <a href="index.php">Beranda</a>
    </div>
  </div>

  <script>
    <?php if ($errorMsg != "") { ?>
        Swal.fire({
            title: 'Login Gagal',
            text: '<?= $errorMsg ?>',
            icon: 'error',
            confirmButtonColor: '#d33'
        });
    <?php } ?>
  </script>
</body>
</html>