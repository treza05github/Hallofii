<?php
session_start();
include "koneksi.php";

// Cek Login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>alert('ID Dokter tidak ditemukan!'); window.location='keloladatadokter.php';</script>";
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM dokter WHERE dokter_id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data dokter tidak ditemukan!'); window.location='keloladatadokter.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .navbar-custom { 
            background: linear-gradient(135deg, #0f4c75, #3282b8); 
            padding: 15px 0; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .form-card { border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05); padding: 40px; background: white; }
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

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="keloladatadokter.php" class="text-decoration-none text-muted mb-3 d-inline-block"><i class="bi bi-arrow-left"></i> Kembali</a>
            
            <div class="card form-card">
                <h3 class="fw-bold mb-4 text-warning"><i class="bi bi-pencil-square me-2"></i>Edit Data Dokter</h3>
                
                <form action="dokteredit_function.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="dokter_id" value="<?= $data['dokter_id']; ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Dokter</label>
                            <input type="text" name="nama_dokter" class="form-control py-2" value="<?= $data['nama_dokter']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Spesialisasi</label>
                            <input type="text" name="spesialisasi" class="form-control py-2" value="<?= $data['spesialisasi']; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hari Praktik</label>
                            <input type="text" name="jadwal_hari" class="form-control py-2" value="<?= $data['jadwal_hari']; ?>" placeholder="Senin - Jumat" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Jam Praktik</label>
                            <input type="text" name="jadwal_jam" class="form-control py-2" value="<?= $data['jadwal_jam']; ?>" placeholder="08:00 - 15:00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Biaya (Rp)</label>
                            <input type="number" name="biaya_konsultasi" class="form-control py-2" value="<?= $data['biaya_konsultasi']; ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold d-block">Foto Dokter</label>
                        <?php if (!empty($data['foto_dokter'])) { ?>
                            <div class="mb-2">
                                <img src="data:image/jpeg;base64,<?= base64_encode($data['foto_dokter']); ?>" class="rounded shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                        <?php } ?>
                        <input type="file" name="foto_dokter" class="form-control" accept="image/*">
                        <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto.</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning text-white px-4 py-2 rounded-3 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>