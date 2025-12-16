<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$q = mysqli_query($koneksi, "SELECT * FROM reservasi WHERE reservasi_id='$id'");
$data = mysqli_fetch_assoc($q);

// Proteksi: Hanya bisa edit jika status 'menunggu dikonfirmasi'
if (!$data || $data['status'] != 'menunggu dikonfirmasi') {
    echo "<script>
        alert('Data reservasi yang sudah diproses tidak dapat diubah.');
        window.location='reservasi.php';
    </script>";
    exit;
}

$dokter = mysqli_query($koneksi, "SELECT * FROM dokter");

// Set Timezone & Min Date (Untuk mencegah pilih masa lalu)
date_default_timezone_set('Asia/Jakarta');
$minDate = date('Y-m-d\TH:i');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .card-form { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 40px; background: white; }
        .form-control, .form-select { border-radius: 10px; padding: 12px; background: #f8f9fa; border: 1px solid #ddd; }
        .info-box { background: #e7f1ff; border-left: 5px solid #0d6efd; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
    </style>
</head>

<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <a href="reservasi.php" class="text-decoration-none text-muted mb-3 d-inline-block fw-bold small">
                <i class="bi bi-arrow-left"></i> Batal Edit
            </a>

            <div class="card-form">
                <h3 class="fw-bold text-primary mb-4">Edit Reservasi</h3>

                <form action="reservasiedit_function.php" method="POST" onsubmit="return validateForm()">
                    <input type="hidden" name="id" value="<?= $data['reservasi_id'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Dokter</label>
                        <select name="dokter_id" id="selectDokter" class="form-select" onchange="updateInfo(true)" required>
                            <?php while ($d = mysqli_fetch_assoc($dokter)) { 
                                $jadwal = $d['jadwal_hari'] . ' (' . $d['jadwal_jam'] . ')';
                            ?>
                                <option value="<?= $d['dokter_id'] ?>" 
                                    data-biaya="<?= $d['biaya_konsultasi'] ?>"
                                    data-jadwal="<?= $jadwal ?>"
                                    data-hari="<?= $d['jadwal_hari'] ?>"
                                    data-jam="<?= $d['jadwal_jam'] ?>"
                                    <?= ($d['dokter_id'] == $data['dokter_id']) ? 'selected' : '' ?>>
                                    <?= $d['nama_dokter'] ?> (<?= $d['spesialisasi'] ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="info-box">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-bold text-muted">Jadwal Praktik:</small>
                            <small class="fw-bold text-dark" id="txtJadwal">-</small>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="fw-bold text-muted">Biaya Baru:</small>
                            <small class="fw-bold text-success" id="txtBiaya">Rp 0</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Layanan / Keluhan</label>
                        <input type="text" name="layanan_spesialis" class="form-control" value="<?= $data['layanan_spesialis'] ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Tanggal & Waktu</label>
                        <input type="datetime-local" name="tanggal_waktu" id="inputTanggal" class="form-control" 
                               value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_waktu'])) ?>" 
                               min="<?= $minDate ?>" required onchange="checkJadwal()">
                        <div class="form-text text-danger" id="errorTanggal" style="display:none;"></div>
                    </div>

                    <button class="btn btn-primary w-100 fw-bold rounded-pill py-2">Simpan Perubahan</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    // Variabel Global
    let dokterHari = "";
    let dokterJam = "";

    function updateInfo(isChange = false) {
        var select = document.getElementById("selectDokter");
        var selected = select.options[select.selectedIndex];
        
        var biaya = selected.getAttribute("data-biaya");
        var jadwal = selected.getAttribute("data-jadwal");
        dokterHari = selected.getAttribute("data-hari");
        dokterJam = selected.getAttribute("data-jam");

        var formatRupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(biaya);
        
        document.getElementById("txtJadwal").innerText = jadwal;
        document.getElementById("txtBiaya").innerText = formatRupiah;

        if (isChange) {
            document.getElementById("inputTanggal").value = "";
            document.getElementById("errorTanggal").style.display = "none";
        }
    }

    function checkJadwal() {
        var input = document.getElementById("inputTanggal");
        var errorDiv = document.getElementById("errorTanggal");
        
        if(!input.value) return;

        var tanggalPilih = new Date(input.value);
        var jamPilih = tanggalPilih.getHours();

        // --- 1. VALIDASI JAM KLINIK (08.00 - 17.00) ---
        if (jamPilih < 8 || jamPilih >= 17) {
            input.value = ""; 
            errorDiv.innerHTML = "❌ <b>Klinik Tutup.</b><br>Jam operasional: <b>08:00 - 17:00 WIB</b>.";
            errorDiv.style.display = "block";
            return;
        }
        
        // --- 2. VALIDASI HARI SPESIFIK DOKTER ---
        var hariIndex = tanggalPilih.getDay(); 
        var hariNama = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][hariIndex];
        var isValidDay = true;
        
        if (dokterHari.toLowerCase().includes("senin - jumat")) {
            if (hariIndex === 0 || hariIndex === 6) isValidDay = false;
        } else if (dokterHari.toLowerCase().includes("senin - sabtu")) {
            if (hariIndex === 0) isValidDay = false;
        } else {
            if (!dokterHari.includes(hariNama)) isValidDay = false;
        }

        if (!isValidDay) {
            input.value = "";
            errorDiv.innerHTML = "❌ Dokter tidak praktik hari <b>" + hariNama + "</b>.<br>Jadwal: " + dokterHari;
            errorDiv.style.display = "block";
            return;
        }

        // --- 3. VALIDASI JAM SPESIFIK DOKTER ---
        var jamMulai = parseInt(dokterJam.substring(0, 2));
        var jamSelesai = parseInt(dokterJam.substring(8, 10));
        
        if (isNaN(jamMulai)) jamMulai = 8;
        if (isNaN(jamSelesai)) jamSelesai = 17;

        if (jamPilih < jamMulai || jamPilih >= jamSelesai) {
            input.value = ""; 
            errorDiv.innerHTML = "❌ Jam praktik dokter: <b>" + dokterJam + "</b>.";
            errorDiv.style.display = "block";
            return;
        }

        errorDiv.style.display = "none";
    }

    // Jalankan saat load halaman pertama kali
    window.onload = function() {
        updateInfo(false); 
    };
</script>

</body>
</html>