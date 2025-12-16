<?php
session_start();
include "koneksi.php";

// CEK LOGIN
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID reservasi
if (!isset($_GET['id'])) {
    echo "ID reservasi tidak ditemukan!";
    exit;
}

$id = $_GET['id'];

// --- FUNGSI FORMAT TANGGAL INDONESIA ---
function tgl_indo($tanggal){
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
// ----------------------------------------

// Query cetak data invoice reservasi per-ID
$query = mysqli_query($koneksi, "
    SELECT r.*, p.username, p.no_telpon, p.email, d.nama_dokter, d.spesialisasi
    FROM reservasi r
    JOIN pengguna p ON r.id_pengguna = p.id_pengguna
    JOIN dokter d ON r.dokter_id = d.dokter_id
    WHERE r.reservasi_id = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data reservasi tidak ditemukan!";
    exit;
}

// --- LOGIKA CEGAH CETAK JIKA TIDAK BERHASIL ---
if ($data['status'] == 'menunggu dikonfirmasi') {
    echo "<script>
            alert('Invoice belum dapat dicetak karena status reservasi masih Menunggu Konfirmasi.');
            window.close(); 
            window.history.back();
          </script>";
    exit;
} else if ($data['status'] == 'reservasi dibatalkan') {
    echo "<script>
            alert('Tidak dapat mencetak invoice untuk reservasi yang dibatalkan.');
            window.close();
            window.history.back();
          </script>";
    exit;
}

// Link kembali (bisa disesuaikan jika admin atau pasien)
$link_kembali = isset($_SESSION['role']) && $_SESSION['role'] == 'admin' ? 'keloladatareservasi.php' : 'reservasi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Reservasi #<?= $id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #eee; font-family: 'Courier New', monospace; }
        .ticket-card {
            background: white; width: 100%; max-width: 400px; margin: 30px auto;
            padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid #0d6efd;
        }
        .dashed-line { border-top: 2px dashed #bbb; margin: 15px 0; }
        .total-row { font-size: 1.2rem; font-weight: bold; }
        .details-label { color: #666; font-size: 0.9rem; }
        .details-value { font-weight: bold; }
        
        .action-buttons { text-align: center; margin-top: 20px; }
        .btn-print { background: #0d6efd; color: white; border: none; padding: 10px 20px; border-radius: 5px; }
        .btn-back { background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-right: 10px; }
        
        @media print {
            body { background: white; }
            .no-print { display: none; }
            .ticket-card { box-shadow: none; border: 1px solid #000; margin: 0; }
        }
    </style>
</head>
<body>

<div class="ticket-card">
    <div class="text-center mb-4">
        <h4 class="fw-bold m-0">HALLOFII KLINIK</h4>
        <small>Jl. Mendalo No. 123, Muaro Jambi</small>
    </div>

    <div class="dashed-line"></div>

    <div class="row">
        <div class="col-6"><small>No. Tiket:</small><br><strong>#RSV-<?= $data['reservasi_id'] ?></strong></div>
        <div class="col-6 text-end"><small>Tanggal Cetak:</small><br><strong><?= date('d/m/Y') ?></strong></div>
    </div>

    <div class="mt-3">
        <small>Pasien:</small><br><strong><?= strtoupper($data['username']) ?></strong>
    </div>

    <div class="dashed-line"></div>

    <div class="mb-2">
        <strong><?= $data['nama_dokter'] ?></strong><br>
        <small class="text-muted"><?= $data['spesialisasi'] ?></small>
    </div>

    <div class="row mt-3">
        <div class="col-6">
            <small>Tanggal Reservasi:</small><br>
            <strong><?= tgl_indo($data['tanggal_waktu']) ?></strong>
        </div>
        <div class="col-6 text-end">
            <small>Waktu / Pukul:</small><br>
            <strong><?= date('H:i', strtotime($data['tanggal_waktu'])) ?> WIB</strong>
        </div>
    </div>

    <div class="mb-2 mt-3">
        <small>Layanan/Keluhan:</small><br><span><?= $data['layanan_spesialis'] ?></span>
    </div>

    <div class="dashed-line"></div>

    <div class="d-flex justify-content-between total-row">
        <span>TOTAL TAGIHAN</span>
        <span>Rp <?= number_format($data['total_biaya'], 0, ',', '.') ?></span>
    </div>
    
    <div class="text-center mt-3 p-2 bg-light border">
        <small class="fw-bold">METODE PEMBAYARAN:</small><br>
        BAYAR DI KASIR (CHECK-IN)
    </div>

    <div class="mt-4">
        <table class="w-100" style="font-size: 0.85rem;">
            <tr>
                <td class="details-label">ID Transaksi Sistem</td>
                <td class="details-value text-end">RSV-<?= $data['reservasi_id'] ?>-<?= date('Y') ?></td>
            </tr>
        </table>
    </div>

    <div class="alert alert-info mt-4 d-flex align-items-center p-2" role="alert" style="font-size: 0.85rem;">
        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
        <div>
            <strong>Penting:</strong> Tunjukkan tiket ini kepada resepsionis saat melakukan Check-In.
        </div>
    </div>

    <div class="text-center mt-4 mb-2">
        <small class="text-muted">Terima kasih telah mempercayakan kesehatan Anda kepada HalloFii.</small>
    </div>
</div>

<div class="action-buttons no-print">
    <a href="<?= $link_kembali; ?>" class="btn btn-back text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
    <button onclick="window.print()" class="btn btn-print">
        <i class="bi bi-printer-fill me-2"></i> Cetak Tiket
    </button>
</div>

<script>
    // Opsional: Langsung print saat dibuka
    // window.onload = () => { window.print(); }
</script>

</body>
</html>