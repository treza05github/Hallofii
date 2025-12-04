<?php
session_start();
include "koneksi.php";

// CEK ROLE ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php");
    exit;
}

// QUERY SEMUA RESERVASI
$query = mysqli_query($koneksi, "
    SELECT r.*, p.username, p.no_telpon, d.nama_dokter, d.spesialisasi
    FROM reservasi r
    JOIN pengguna p ON r.id_pengguna = p.id_pengguna
    JOIN dokter d ON r.dokter_id = d.dokter_id
    ORDER BY r.reservasi_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Reservasi - HalloFii</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .title h2 { font-weight: bold; }
        .header-info {
            font-size: 14px;
            margin-bottom: 20px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
        table {
            font-size: 14px;
        }
    </style>

</head>
<body>

<div class="title">
    <h2>LAPORAN DATA RESERVASI</h2>
    <h4>KLINIK HalloFii</h4>
</div>

<div class="header-info">
    Dicetak oleh: <b><?= $_SESSION['username']; ?></b><br>
    Tanggal: <?= date("d-m-Y H:i:s"); ?>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr class="table-secondary text-center">
            <th>No</th>
            <th>Nama Pasien</th>
            <th>No Telpon</th>
            <th>Dokter</th>
            <th>Layanan & Spesialisasi</th>
            <th>Tanggal & Waktu</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
    <?php 
    $no = 1;
    while ($row = mysqli_fetch_assoc($query)) { 
    ?>
        <tr>
            <td class="text-center"><?= $no++; ?></td>
            <td><?= $row['username']; ?></td>
            <td><?= $row['no_telpon']; ?></td>
            <td><?= $row['nama_dokter']; ?> (<?= $row['spesialisasi']; ?>)</td>
            <td><?= $row['layanan_spesialis']; ?></td>
            <td><?= $row['tanggal_waktu']; ?></td>
            <td>
                <?php if ($row['status'] == "menunggu dikonfirmasi") { ?>
                    <span class="badge bg-warning text-dark">Menunggu</span>
                <?php } elseif ($row['status'] == "reservasi berhasil") { ?>
                    <span class="badge bg-success">Berhasil</span>
                <?php } else { ?>
                    <span class="badge bg-danger">Dibatalkan</span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<!-- TOMBOL BACK -->
<div class="text-center mt-4 no-print">
    <a href="keloladatareservasi.php" class="btn btn-secondary">Kembali</a>
    <button onclick="window.print()" class="btn btn-primary">Print</button>
</div>

<!-- AUTO PRINT SAAT HALAMAN DIBUKA -->
<script>
    window.onload = () => {
        window.print();
    }
</script>

</body>
</html>
