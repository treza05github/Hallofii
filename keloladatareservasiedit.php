<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") { header("Location: login.php"); exit; }

// --- FUNGSI TANGGAL INDONESIA ---
function tgl_indo($tanggal){
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
// --------------------------------

$id = $_GET['id'];
$q = mysqli_query($koneksi,"
    SELECT r.*, p.username, p.no_telpon, d.nama_dokter, d.jadwal_hari, d.jadwal_jam 
    FROM reservasi r
    JOIN pengguna p ON r.id_pengguna=p.id_pengguna
    JOIN dokter d ON r.dokter_id=d.dokter_id
    WHERE reservasi_id='$id'
");
$data = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Status Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 30px; background: white; }
        .info-label { font-size: 0.85rem; color: #6c757d; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .info-value { font-size: 1rem; font-weight: 600; color: #212529; margin-bottom: 15px; }
        .price-tag { color: #198754; font-size: 1.2rem; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-custom">
                <h4 class="fw-bold text-center text-primary mb-4">Konfirmasi Reservasi</h4>
                
                <div class="row">
                    <div class="col-6">
                        <div class="info-label">Pasien</div>
                        <div class="info-value"><?= $data['username'] ?></div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="info-label">Tagihan</div>
                        <div class="info-value price-tag">Rp <?= number_format($data['total_biaya'], 0, ',', '.') ?></div>
                    </div>
                </div>

                <div class="info-label">Dokter</div>
                <div class="info-value"><?= $data['nama_dokter'] ?></div>

                <div class="info-label">Jadwal Praktik</div>
                <div class="info-value"><?= $data['jadwal_hari'] ?> (<?= $data['jadwal_jam'] ?>)</div>

                <div class="info-label">Tanggal Booking</div>
                <div class="info-value">
                    <?= tgl_indo($data['tanggal_waktu']) ?>, Pukul <?= date('H:i', strtotime($data['tanggal_waktu'])) ?> WIB
                </div>

                <hr>

                <form action="keloladatareservasiedit_function.php" method="post">
                    <input type="hidden" name="id" value="<?= $data['reservasi_id']; ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Update Status</label>
                        <select name="status" class="form-select py-2 border-primary">
                            <option value="menunggu dikonfirmasi" <?= ($data['status']=="menunggu dikonfirmasi"?"selected":"") ?>>⏳ Menunggu Dikonfirmasi</option>
                            <option value="reservasi berhasil" <?= ($data['status']=="reservasi berhasil"?"selected":"") ?>>✅ Reservasi Berhasil (Setujui)</option>
                            <option value="reservasi dibatalkan" <?= ($data['status']=="reservasi dibatalkan"?"selected":"") ?>>❌ Reservasi Dibatalkan (Tolak)</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="keloladatareservasi.php" class="btn btn-light flex-grow-1 text-muted fw-bold">Kembali</a>
                        <button class="btn btn-primary flex-grow-1 fw-bold">Simpan Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>