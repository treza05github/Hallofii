<?php
include "koneksi.php";

$alertType = ""; 
$alertMsg = "";  
$alertTitle = ""; // Judul alert agar lebih spesifik

if (isset($_POST['registrasi'])) {

    // 1. Ambil & Bersihkan Input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $no_telpon = htmlspecialchars(trim($_POST['no_telpon']));
    $password_raw = $_POST['password'];

    // 2. VALIDASI DATA
    
    // A. Cek Data Tidak Lengkap (Empty Check)
    if (empty($username) || empty($email) || empty($no_telpon) || empty($password_raw)) {
        $alertType = "warning";
        $alertTitle = "Data Tidak Lengkap!";
        $alertMsg = "Mohon isi semua kolom formulir pendaftaran.";
    }
    // B. Cek Username (Maks 30 Karakter)
    elseif (strlen($username) > 30) {
        $alertType = "warning";
        $alertTitle = "Data Tidak Sesuai!";
        $alertMsg = "Username terlalu panjang (maksimal 30 karakter).";
    }
    // C. Cek Format Email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertType = "warning";
        $alertTitle = "Data Tidak Sesuai!";
        $alertMsg = "Format email yang Anda masukkan tidak valid (harus mengandung @ dan domain).";
    }
    // D. Cek Nomor Telpon (Harus Angka & Maks 15)
    elseif (!is_numeric($no_telpon)) {
        $alertType = "warning";
        $alertTitle = "Data Tidak Sesuai!";
        $alertMsg = "Nomor telpon harus berupa angka saja.";
    }
    elseif (strlen($no_telpon) > 15 || strlen($no_telpon) < 10) {
        $alertType = "warning";
        $alertTitle = "Data Tidak Sesuai!";
        $alertMsg = "Nomor telpon harus antara 10 sampai 15 digit.";
    }
    // E. Proteksi Username Admin
    elseif (strtolower($username) == "admin" || strtolower($email) == "admin@gmail.com") {
        $alertType = "error";
        $alertTitle = "Akses Ditolak!";
        $alertMsg = "Username/Email ini dilindungi sistem.";
    } 
    else {
        // 3. CEK DUPLIKASI DATA DI DATABASE
        $cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username' OR email='$email'");
        if(mysqli_num_rows($cek) > 0){
             $alertType = "error";
             $alertTitle = "Gagal Daftar!";
             $alertMsg = "Username atau Email sudah terdaftar sebelumnya.";
        } else {
            // 4. PROSES SIMPAN (Hash Password)
            $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO pengguna (username, email, no_telpon, password)
                      VALUES ('$username', '$email', '$no_telpon', '$password_hash', 'pasien')";
            
            if (mysqli_query($koneksi, $query)) {
                $alertType = "success";
                $alertTitle = "Berhasil!";
                $alertMsg = "Akun berhasil dibuat! Silakan login.";
            } else {
                $alertType = "error";
                $alertTitle = "Error Server!";
                $alertMsg = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi | HalloFii</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    body { 
        margin: 0; padding: 0; 
        font-family: 'Poppins', sans-serif; 
        background: linear-gradient(to bottom right, #e0f7fa, #ffffff); 
        height: 100vh; 
        display: flex; justify-content: center; align-items: center; 
        overflow: hidden; 
    }
    .login-container { 
        width: 360px; padding: 40px; 
        background: #ffffffcc; backdrop-filter: blur(10px); 
        border-radius: 20px; 
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); 
        text-align: center; 
    }
    .logo { font-size: 34px; font-weight: 700; color: #00aaff; margin-bottom: 5px; }
    .tagline { font-size: 14px; color: #555; margin-bottom: 25px; }
    
    .input-box { margin: 15px 0; text-align: left; }
    .input-box input { 
        width: 100%; padding: 12px; box-sizing: border-box;
        border-radius: 10px; border: 2px solid #aee1f9; 
        font-size: 14px; outline: none; transition: all 0.3s; 
    }
    .input-box input:focus { border-color: #00aaff; box-shadow: 0 0 10px #b3ecff; }
    
    button { 
        width: 100%; padding: 12px; 
        background: linear-gradient(90deg, #00b4d8, #0096c7); 
        border: none; border-radius: 10px; 
        color: #fff; font-size: 16px; font-weight: 600;
        cursor: pointer; transition: 0.3s; margin-top: 10px; 
    }
    button:hover { background: linear-gradient(90deg, #0096c7, #0077b6); transform: scale(1.03); }
    
    .footer-text { margin-top: 20px; font-size: 13px; color: #777; }
    .footer-text a { color: #00aaff; text-decoration: none; font-weight: 600; }
    
    /* Animasi Icon */
    .pulse-icon { 
        width: 50px; height: 50px; margin: 0 auto 10px auto; 
        background: #00aaff; 
        mask: url('https://cdn-icons-png.flaticon.com/512/2966/2966486.png') no-repeat center; 
        mask-size: contain; 
        animation: pulse 1.8s infinite; 
    }
    @keyframes pulse { 0%, 100% { transform: scale(1); opacity: 0.9; } 50% { transform: scale(1.15); opacity: 1; } }
  </style>
</head>
<body>
  
  <div class="login-container">
    <div class="pulse-icon"></div>
    <div class="logo">HalloFii</div>
    <div class="tagline">Buat Akun Baru</div>

    <form action="" method="post" novalidate> 
      <div class="input-box">
         <p style="font-family: 'Poppins', sans-serif; font-weight: bold; font-size: 12px;">Username</p>   
          <input type="text" name="username" placeholder="Masukan Username. max: 30" value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>">
      </div>
      
      <div class="input-box">
         <p style="font-family: 'Poppins', sans-serif; font-weight: bold; font-size: 12px;">Email</p>   
          <input type="email" name="email" placeholder="Masukan Alamat Email. contoh: nama@gmail.com" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
      </div>
      
      <div class="input-box">
        <p style="font-family: 'Poppins', sans-serif; font-weight: bold; font-size: 12px;">Nomor Telpon</p>        
        <input type="text" name="no_telpon" placeholder="Masukan Nomor Telpon. contoh: 08123..." value="<?= isset($_POST['no_telpon']) ? $_POST['no_telpon'] : '' ?>"
          oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);">
      </div>
      
      <div class="input-box">
         <p style="font-family: 'Poppins', sans-serif; font-weight: bold; font-size: 12px;">Password</p>   
          <input type="password" name="password" placeholder="Masukan Password">
      </div>
      
      <button type="submit" name="registrasi" value="registrasi">Daftar Sekarang</button>
    </form>

    <div class="footer-text">Sudah punya akun? <a href="login.php">Login</a></div>
  </div>

  <script>
    <?php if ($alertType != "") { ?>
        Swal.fire({
            title: '<?= $alertTitle ?>',
            text: '<?= $alertMsg ?>',
            icon: '<?= $alertType ?>',
            confirmButtonColor: '<?= ($alertType=="success") ? "#00aaff" : "#d33" ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            <?php if ($alertType == "success") { ?>
                window.location = 'login.php';
            <?php } ?>
        });
    <?php } ?>
  </script>

</body>
</html>