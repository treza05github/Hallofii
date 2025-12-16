<?php
session_start();
include "koneksi.php";

// 1. CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// Inisialisasi variabel Alert
$alertType = "";
$alertMsg = "";

// 2. PROSES LOGIKA PENYIMPANAN DATA (Dijalankan saat tombol 'tambah' ditekan)
if (isset($_POST['tambah'])) {
    
    // Tangkap Input
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_dokter']);
    $spesialisasi = mysqli_real_escape_string($koneksi, $_POST['spesialisasi']);
    $hari = mysqli_real_escape_string($koneksi, $_POST['jadwal_hari']);
    $jam = mysqli_real_escape_string($koneksi, $_POST['jadwal_jam']);
    $biaya = (int) $_POST['biaya_konsultasi']; // Pastikan integer

    // Validasi Foto
    if (!empty($_FILES['foto_dokter']['tmp_name'])) {
        $fileName = $_FILES['foto_dokter']['name'];
        $fileTmp  = $_FILES['foto_dokter']['tmp_name'];
        $fileSize = $_FILES['foto_dokter']['size'];
        
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Cek Ekstensi & Validitas Gambar
        if (!in_array($fileExt, $allowedExt)) {
            $alertType = "error"; $alertMsg = "Format file salah! Hanya boleh JPG/PNG.";
        } elseif (!getimagesize($fileTmp)) {
            $alertType = "error"; $alertMsg = "File rusak atau bukan gambar valid.";
        } elseif ($fileSize > 2097152) { // 2MB
            $alertType = "warning"; $alertMsg = "Ukuran file terlalu besar (Maks 2MB).";
        } else {
            // JIKA LOLOS VALIDASI -> SIMPAN KE DB
            
            // Baca file gambar ke binary (BLOB)
            $foto = file_get_contents($fileTmp);

            // Query Insert (Prepared Statement)
            $sql = "INSERT INTO dokter (nama_dokter, spesialisasi, jadwal_hari, jadwal_jam, biaya_konsultasi, foto_dokter) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $koneksi->prepare($sql);
            
            // Binding params: s=string, i=integer, s=blob
            $stmt->bind_param("ssssis", $nama, $spesialisasi, $hari, $jam, $biaya, $foto);

            if ($stmt->execute()) {
                $alertType = "success"; 
                $alertMsg = "Data dokter berhasil ditambahkan!";
            } else {
                $alertType = "error"; 
                $alertMsg = "Gagal menyimpan: " . $stmt->error;
            }
        }
    } else {
        $alertType = "warning"; 
        $alertMsg = "Mohon upload foto dokter.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .navbar-custom { 
            background: linear-gradient(135deg, #0f4c75, #3282b8); 
            padding: 15px 0; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card-form { border: none; border-radius: 15px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 40px; margin-bottom: 50px; }
        .form-label { font-weight: 600; font-size: 14px; color: #555; }
        .form-control, .input-group-text { border-radius: 10px; padding: 10px 15px; }
        .section-title { font-size: 16px; font-weight: 700; color: #0f4c75; margin-bottom: 15px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-custom navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-people-fill me-2"></i> DATA DOKTER
        </a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a href="keloladatadokter.php" class="text-decoration-none text-muted fw-bold mb-3 d-block"><i class="bi bi-arrow-left"></i> Kembali</a>
            
            <div class="card-form">
                <h3 class="fw-bold text-center mb-4" style="color:#0f4c75;">Tambah Data Dokter</h3>
                
                <form action="" method="POST" enctype="multipart/form-data">
                    
                    <div class="section-title"><i class="bi bi-person-lines-fill me-2"></i>Informasi Dasar</div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama_dokter" class="form-control" placeholder="Contoh: dr. Fulan, Sp.JP" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Spesialisasi</label>
                            <input type="text" name="spesialisasi" class="form-control" placeholder="Contoh: Jantung, Anak" required>
                        </div>
                    </div>

                    <div class="section-title mt-4"><i class="bi bi-calendar-check me-2"></i>Praktik & Biaya</div>
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hari Praktik</label>
                            <input type="text" name="jadwal_hari" class="form-control" placeholder="Senin - Jumat" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jam Praktik</label>
                            <input type="text" name="jadwal_jam" class="form-control" placeholder="08:00 - 15:00" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Biaya Jasa (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="biaya_konsultasi" class="form-control" placeholder="150000" required>
                            </div>
                        </div>
                    </div>

                    <div class="section-title mt-4"><i class="bi bi-image me-2"></i>Foto Profil</div>
                    <div class="mb-4">
                        <input type="file" name="foto_dokter" class="form-control" accept="image/*" required>
                        <div class="form-text">Format JPG/PNG, Max 2MB.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="tambah" class="btn btn-primary btn-lg rounded-pill">Simpan Data Dokter</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    <?php if ($alertType != "") { ?>
        Swal.fire({
            title: '<?= ($alertType=="success")?"Berhasil":"Gagal" ?>',
            text: '<?= $alertMsg ?>',
            icon: '<?= $alertType ?>',
            confirmButtonText: 'OK',
            confirmButtonColor: '<?= ($alertType=="success")?"#0f4c75":"#d33" ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika sukses, pindah ke halaman list. Jika gagal, diam di sini.
                <?php if ($alertType == "success") { ?>
                    window.location = 'keloladatadokter.php';
                <?php } ?>
            }
        });
    <?php } ?>
</script>

</body>
</html>