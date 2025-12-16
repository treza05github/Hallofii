<?php
include "koneksi.php";

// Daftar Admin yang mau ditambahkan
$daftar_admin = [
    [
        'username' => 'admin2',
        'password' => '77', // Password awal
        'email'    => 'admin2@gmail.com',
        'telpon'   => '081299998888'
    ],
    [
        'username' => 'admin3',
        'password' => '44', // Password awal
        'email'    => 'admin3@gmail.com',
        'telpon'   => '081277776666'
    ]
];

echo "<h3>Proses Menambahkan Admin Baru...</h3><hr>";

foreach ($daftar_admin as $adm) {
    $user = $adm['username'];
    $pass_raw = $adm['password'];
    $email = $adm['email'];
    $telp = $adm['telpon'];

    // 1. Cek dulu apakah username sudah ada?
    $cek = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$user'");
    
    if (mysqli_num_rows($cek) > 0) {
        echo "<p style='color:orange'>⚠️ Username <b>$user</b> sudah ada, dilewati.</p>";
        
        // Opsional: Jika ingin update admin lama menjadi role admin (jika dulunya pasien)
        mysqli_query($koneksi, "UPDATE pengguna SET role='admin' WHERE username='$user'");
        
    } else {
        // 2. Enkripsi Password (WAJIB!)
        $pass_hash = password_hash($pass_raw, PASSWORD_DEFAULT);

        // 3. Masukkan ke Database dengan role 'admin'
        $query = "INSERT INTO pengguna (username, email, no_telpon, password, role) 
                  VALUES ('$user', '$email', '$telp', '$pass_hash', 'admin')";

        if (mysqli_query($koneksi, $query)) {
            echo "<p style='color:green'>✅ Sukses membuat akun: <b>$user</b> (Password: $pass_raw)</p>";
        } else {
            echo "<p style='color:red'>❌ Gagal: " . mysqli_error($koneksi) . "</p>";
        }
    }
}

echo "<hr><p>Selesai. Silakan <a href='login.php'>Login Disini</a>.</p>";
?>