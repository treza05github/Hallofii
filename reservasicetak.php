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

// Query cetak data invoice reservasi per-ID
$query = mysqli_query($koneksi, "
    SELECT r.*, p.username, p.no_telpon, d.nama_dokter, d.spesialisasi
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice Reservasi - HalloFii</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: Arial, sans-serif; padding: 30px; }
        .invoice-box {
            border: 2px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            width: 70%;
            margin: auto;
        }
        .title-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-header h2 { margin: 0; font-weight: bold; }
        .title-header p { margin:0; }
        .info-table td { padding: 5px 0; font-size: 15px; }
        @media print {
            .no-print { display:none; }
            body { padding: 0; }
            .invoice-box { border: none; width: 100%; }
        }
    </style>
</head>

<body>

<div class="invoice-box">

    <div class="title-header">
        <h2>KLINIK HalloFii</h2>
        <p>Jl. Jambi – Muara Bulian, Mendalo Darat, Jambi</p>
        <p>Telp: +62 822-4305-4197</p>
        <hr>
        <h3>INVOICE RESERVASI</h3>
    </div>

    <!-- DATA INVOICE -->
    <table class="table info-table">
        <tr>
            <td><strong>ID Reservasi</strong></td>
            <td>: <?= $data['reservasi_id'] ?></td>
        </tr>
        <tr>
            <td><strong>Nama Pasien</strong></td>
            <td>: <?= $data['username'] ?></td>
        </tr>
        <tr>
            <td><strong>No Telpon</strong></td>
            <td>: <?= $data['no_telpon'] ?></td>
        </tr>
        <tr>
            <td><strong>Dokter</strong></td>
            <td>: <?= $data['nama_dokter'] ?> (<?= $data['spesialisasi'] ?>)</td>
        </tr>
        <tr>
            <td><strong>Layanan & Spesialisasi</strong></td>
            <td>: <?= $data['layanan_spesialis'] ?></td>
        </tr>
        <tr>
            <td><strong>Tanggal & Waktu</strong></td>
            <td>: <?= $data['tanggal_waktu'] ?></td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td>:
                <?php if ($data['status'] == "menunggu dikonfirmasi") { ?>
                    <span class="badge bg-warning text-dark">Menunggu Dikonfirmasi</span>
                <?php } elseif ($data['status'] == "reservasi berhasil") { ?>
                    <span class="badge bg-success">Reservasi Berhasil</span>
                <?php } else { ?>
                    <span class="badge bg-danger">Reservasi Dibatalkan</span>
                <?php } ?>
            </td>
        </tr>
    </table>

    <br>

    <p><strong>Keterangan:</strong> Invoice ini digunakan untuk keperluan Check-in di Klinik HalloFii.</p>

    <hr>

    <!-- FOOTER -->
    <div class="text-center">
        <p>Terima kasih telah menggunakan layanan reservasi HalloFii.</p>
        <p><i>Semoga lekas membaik 🙏</i></p>
    </div>

</div>

<!-- TOMBOL -->
<div class="text-center mt-4 no-print">
    <a href="javascript:history.back()" class="btn btn-secondary">Kembali</a>
    <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
</div>

<script>
    window.onload = () => {
        window.print();
    };
</script>

</body>
</html>
