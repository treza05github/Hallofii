<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") { header("Location: login.php"); exit; }

// Query Data (Urutkan dari yang terbaru)
$query = mysqli_query($koneksi, "
    SELECT r.*, p.username, d.nama_dokter, d.spesialisasi
    FROM reservasi r
    JOIN pengguna p ON r.id_pengguna = p.id_pengguna
    JOIN dokter d ON r.dokter_id = d.dokter_id
    ORDER BY r.reservasi_id DESC
");

$total_pendapatan = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Reservasi & Keuangan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .status-success { color: green; font-weight: bold; }
        .status-cancel { color: red; font-style: italic; }
        .text-right { text-align: right; }
        .total-row { background-color: #e8f5e9; font-weight: bold; font-size: 14px; }
        
        @media print { .no-print { display: none; } }
        
        .btn-print { 
            background: #0f4c75; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; 
            font-weight: bold; display: inline-block; margin-bottom: 20px;
        }
        .btn-back { 
            background: #0e8fe6ff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; 
            font-weight: bold; display: inline-block; margin-bottom: 20px; 
        }
    </style>
</head>
<body>

    <div class="no-print text-center">
        <button onclick="window.print()" class="btn-print">Cetak Laporan (PDF)</button>
        <button class="btn-back"><a href="keloladatareservasi.php" style="text-decoration: none; color: white;"> Kembali</a></button>
    </div>

    <div class="header">
        <h2 style="margin:0;">HALLOFII KLINIK</h2>
        <p style="margin:5px 0;">Laporan Reservasi & Pendapatan</p>
        <small>Dicetak Tanggal: <?= date('d F Y'); ?></small>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Layanan</th>
                <th>Status</th>
                <th class="text-right">Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            while($row = mysqli_fetch_assoc($query)) { 
                // Hitung total hanya jika status Berhasil
                if ($row['status'] == 'reservasi berhasil') {
                    $total_pendapatan += $row['total_biaya'];
                }
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= date('d/m/Y', strtotime($row['tanggal_waktu'])); ?></td>
                <td><?= $row['username']; ?></td>
                <td><?= $row['nama_dokter']; ?></td>
                <td><?= $row['layanan_spesialis']; ?></td>
                <td>
                    <?php if ($row['status'] == 'reservasi berhasil') { ?>
                        <span class="status-success">Berhasil</span>
                    <?php } elseif ($row['status'] == 'reservasi dibatalkan') { ?>
                        <span class="status-cancel">Batal</span>
                    <?php } else { ?>
                        <span>Menunggu</span>
                    <?php } ?>
                </td>
                <td class="text-right"><?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
            </tr>
            <?php } ?>
            
            <tr class="total-row">
                <td colspan="6" class="text-right">TOTAL PENDAPATAN (Reservasi Berhasil)</td>
                <td class="text-right">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 40px; float: right; text-align: center; width: 200px;">
        <p>Jambi, <?= date("d F Y"); ?></p>
        <br><br><br>
        <p><b>Administrator</b></p>
    </div>

</body>
</html>