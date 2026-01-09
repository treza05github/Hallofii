<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    echo "<script>alert('Akses ditolak!'); window.location='login.php';</script>";
    exit;
}

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

$query = mysqli_query($koneksi, "
    SELECT r.*, p.username, p.no_telpon, p.email, d.nama_dokter, d.spesialisasi
    FROM reservasi r
    JOIN pengguna p ON r.id_pengguna = p.id_pengguna
    JOIN dokter d ON r.dokter_id = d.dokter_id
    WHERE r.reservasi_id = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) { echo "Data tidak ditemukan!"; exit; }

$no_invoice = "INV/" . date('Y') . "/" . str_pad($data['reservasi_id'], 4, '0', STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice <?= $no_invoice ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { background: #555; font-family: 'Roboto', sans-serif; padding: 20px; }
        .invoice-box { background: white; max-width: 800px; margin: auto; padding: 40px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.15); position: relative; }
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .clinic-name { font-size: 28px; font-weight: 700; color: #0f4c75; margin-bottom: 5px; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #333; text-align: right; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .invoice-table th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #ddd; font-weight: bold; }
        .invoice-table td { padding: 12px; border-bottom: 1px solid #eee; }
        .total-row td { border-top: 2px solid #333; font-weight: bold; font-size: 16px; color: #000; }
        .stamp-lunas { position: absolute; top: 200px; right: 100px; border: 3px solid #198754; color: #198754; font-size: 30px; font-weight: bold; padding: 10px 20px; text-transform: uppercase; letter-spacing: 5px; transform: rotate(-15deg); opacity: 0.8; }
        .signature-area { margin-top: 50px; display: flex; justify-content: flex-end; }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { border-bottom: 1px solid #333; margin-top: 60px; margin-bottom: 5px; }
        @media print { body { background: white; padding: 0; } .invoice-box { box-shadow: none; border: none; max-width: 100%; padding: 0; } .no-print { display: none !important; } }
    </style>
</head>
<body>

    <div class="text-center mb-3 no-print">
        <button onclick="window.print()" class="btn btn-primary fw-bold"><i class="bi bi-printer-fill"></i> Cetak Invoice</button>
        <a href="keloladatareservasi.php" class="btn btn-secondary fw-bold"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                <div class="clinic-name">HALLOFII KLINIK</div>
                <div>Jl. Mendalo No. 123, Muaro Jambi</div>
            </div>
            <div class="text-end">
                <div class="invoice-title">INVOICE</div>
                <div><strong>No:</strong> <?= $no_invoice ?></div>
                <div><strong>Tgl Cetak:</strong> <?= tgl_indo(date('Y-m-d')) ?></div>
            </div>
        </div>

        <hr>

        <div class="row mb-4">
            <div class="col-6">
                <h6 class="fw-bold text-uppercase text-muted">Ditagihkan Kepada:</h6>
                <div class="fw-bold fs-5"><?= strtoupper($data['username']) ?></div>
                <div><?= $data['no_telpon'] ?></div>
                <div><?= $data['email'] ?></div>
            </div>
            <div class="col-6 text-end">
                <h6 class="fw-bold text-uppercase text-muted">Jadwal Reservasi Pasien:</h6>
                <div class="fs-5 text-primary fw-bold">
                    <?= tgl_indo($data['tanggal_waktu']) ?>
                </div>
                <div class="fw-bold">
                    Pukul <?= date('H:i', strtotime($data['tanggal_waktu'])) ?> WIB
                </div>
            </div>
        </div>

        <?php if ($data['status'] == "reservasi berhasil") { ?>
            <div class="stamp-lunas">LUNAS</div>
        <?php } ?>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="50%">Deskripsi Layanan</th>
                    <th width="25%">Dokter</th>
                    <th width="20%" class="text-end">Jumlah (IDR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <strong>Konsultasi Dokter Spesialis</strong><br>
                        <small class="text-muted">Keluhan: <?= $data['layanan_spesialis'] ?></small>
                    </td>
                    <td>
                        <?= $data['nama_dokter'] ?><br>
                        <small>(<?= $data['spesialisasi'] ?>)</small>
                    </td>
                    <td class="text-end"><?= number_format($data['total_biaya'], 0, ',', '.') ?></td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="text-end">TOTAL TAGIHAN</td>
                    <td class="text-end">Rp <?= number_format($data['total_biaya'], 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <div class="signature-area">
            <div class="signature-box">
                <div>Jambi, <?= tgl_indo(date('Y-m-d')) ?></div>
                <div class="signature-line"></div>
                <div><strong><?= strtoupper($_SESSION['username']) ?></strong></div>
                <small>(Admin)</small>
            </div>
        </div>

        <div class="mt-5 text-center text-muted" style="font-size: 12px;">
            <p>Terima kasih telah mempercayakan kesehatan Anda kepada HalloFii Klinik.</p>
            <p>Bukti pembayaran ini sah dan diterbitkan secara komputerisasi.</p>
        </div>
    </div>

</body>
</html>