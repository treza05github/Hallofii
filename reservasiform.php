<?php
session_start();
include "koneksi.php";

// Cek Login Pasien
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data pengguna
$p = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id_pengguna='$id_pengguna'"));

// Ambil data dokter
$dokter = mysqli_query($koneksi, "SELECT * FROM dokter");

// Set Timezone agar 'min' date sesuai waktu lokal
date_default_timezone_set('Asia/Jakarta');
$minDate = date('Y-m-d\TH:i'); // Format HTML5 datetime-local
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Reservasi | HalloFii</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }
        .navbar-custom { background: linear-gradient(135deg, #0d6efd, #0099ff); padding: 15px 0; }
        .card-form { border: none; border-radius: 20px; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 40px; margin-top: 30px; margin-bottom: 50px; }
        .form-control, .form-select { border-radius: 10px; padding: 12px 15px; border: 1px solid #e0e0e0; background-color: #f8f9fa; }
        .input-readonly { background-color: #e9ecef !important; color: #6c757d; cursor: not-allowed; }
        .btn-confirm { background: linear-gradient(135deg, #0d6efd, #0b5ed7); border: none; border-radius: 50px; padding: 12px 40px; font-weight: 600; color: white; width: 100%; transition: 0.3s; }
        .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(13, 110, 253, 0.4); }
        
        /* Box Info Dokter */
        .doctor-info-box {
            background: #f8f9fa; border-left: 5px solid #0d6efd; border-radius: 5px; padding: 20px;
            display: none; margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-custom navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">FORM RESERVASI</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mt-4 mb-2">
                <a href="reservasi.php" class="text-decoration-none text-muted fw-bold small">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                </a>
            </div>

            <div class="card-form">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">Buat Janji Temu</h3>
                    <p class="text-muted small">Silakan lengkapi data reservasi di bawah ini.</p>
                </div>

                <form action="reservasiform_function.php" method="post" onsubmit="return validateForm()">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nama Pasien</label>
                            <input type="text" class="form-control input-readonly" value="<?= $p['username']; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Nomor Ponsel</label>
                            <input type="text" class="form-control input-readonly" value="<?= $p['no_telpon']; ?>" readonly>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Dokter Spesialis</label>
                        <select name="dokter_id" id="selectDokter" class="form-select" required onchange="updateInfoDokter()">
                            <option value="" data-biaya="0" data-hari="" data-jam="">-- Silahkan Pilih Dokter --</option>
                            <?php while ($d = mysqli_fetch_assoc($dokter)) { ?>
                                <option value="<?= $d['dokter_id']; ?>" 
                                        data-biaya="<?= $d['biaya_konsultasi']; ?>" 
                                        data-hari="<?= $d['jadwal_hari']; ?>"
                                        data-jam="<?= $d['jadwal_jam']; ?>">
                                    <?= $d['nama_dokter']; ?> (<?= $d['spesialisasi']; ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div id="infoDokter" class="doctor-info-box">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block">Jadwal Praktik</small>
                                <span id="txtJadwal" class="fw-bold text-dark">-</span>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted d-block">Biaya Konsultasi</small>
                                <span id="txtBiaya" class="fw-bold text-success fs-5">-</span>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <small class="text-danger fst-italic">
                                <i class="bi bi-info-circle me-1"></i> Pembayaran dilakukan saat <b>Check-In</b>.
                            </small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keluhan / Layanan</label>
                        <input type="text" name="layanan" class="form-control" placeholder="Contoh: Demam tinggi, Sakit gigi..." required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Rencana Tanggal & Waktu</label>
                        <input type="datetime-local" name="tanggal" id="inputTanggal" class="form-control" 
                               min="<?= $minDate; ?>" required onchange="checkJadwal()">
                        <div class="form-text text-danger" id="errorTanggal" style="display:none;"></div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn-confirm">
                            <i class="bi bi-calendar-check me-2"></i> Konfirmasi Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Variabel Global
    let dokterHari = ""; 
    let dokterJam = "";

    function updateInfoDokter() {
        var select = document.getElementById("selectDokter");
        var selectedOption = select.options[select.selectedIndex];
        var infoBox = document.getElementById("infoDokter");
        var inputTanggal = document.getElementById("inputTanggal");
        
        var biaya = selectedOption.getAttribute("data-biaya");
        dokterHari = selectedOption.getAttribute("data-hari");
        dokterJam = selectedOption.getAttribute("data-jam");

        if (biaya && biaya !== "0") {
            var formatRupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(biaya);
            
            document.getElementById("txtJadwal").innerText = dokterHari + " (" + dokterJam + ")";
            document.getElementById("txtBiaya").innerText = formatRupiah;
            infoBox.style.display = "block";
            
            // Reset tanggal saat ganti dokter
            inputTanggal.value = ""; 
            document.getElementById("errorTanggal").style.display = "none";
        } else {
            infoBox.style.display = "none";
        }
    }

    function checkJadwal() {
        var input = document.getElementById("inputTanggal");
        var errorDiv = document.getElementById("errorTanggal");
        
        if (!input.value) return; // Jika kosong biarkan saja

        var tanggalPilih = new Date(input.value);
        var jamPilih = tanggalPilih.getHours();

        // --- 1. VALIDASI JAM KLINIK (08.00 - 17.00) ---
        // Jika jam kurang dari 8 ATAU jam lebih dari/sama dengan 17
        if (jamPilih < 8 || jamPilih >= 17) {
            input.value = ""; // Reset input
            errorDiv.innerHTML = "❌ <b>Klinik Tutup.</b><br>Jam operasional kami: <b>08:00 - 17:00 WIB</b>.";
            errorDiv.style.display = "block";
            return;
        }

        // --- 2. VALIDASI PILIH DOKTER ---
        if (!dokterHari) {
            Swal.fire('Pilih Dokter Dulu', 'Silakan pilih dokter sebelum menentukan tanggal.', 'warning');
            input.value = "";
            return;
        }

        // --- 3. VALIDASI HARI SPESIFIK DOKTER ---
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
            errorDiv.innerHTML = "❌ Dokter tidak praktik pada hari <b>" + hariNama + "</b>.<br>Jadwal Dokter: " + dokterHari;
            errorDiv.style.display = "block";
            return;
        }

        // --- 4. VALIDASI JAM SPESIFIK DOKTER ---
        var jamMulai = parseInt(dokterJam.substring(0, 2)); 
        var jamSelesai = parseInt(dokterJam.substring(8, 10)); 
        
        if (isNaN(jamMulai)) jamMulai = 8; 
        if (isNaN(jamSelesai)) jamSelesai = 17;

        if (jamPilih < jamMulai || jamPilih >= jamSelesai) {
            input.value = "";
            errorDiv.innerHTML = "❌ Dokter ini hanya praktik jam: <b>" + dokterJam + "</b>.";
            errorDiv.style.display = "block";
            return;
        }

        // Lolos Semua Validasi
        errorDiv.style.display = "none";
    }

    function validateForm() {
        var input = document.getElementById("inputTanggal");
        if (!input.value) {
            Swal.fire('Tanggal Kosong', 'Silakan pilih tanggal dan waktu yang valid.', 'warning');
            return false;
        }
        return true;
    }
</script>

</body>
</html>