<?php
session_start();
include "koneksi.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT r.*, p.username, d.nama_dokter 
    FROM reservasi r
    JOIN pengguna p ON r.id_pengguna=p.id_pengguna
    JOIN dokter d ON r.dokter_id=d.dokter_id
    WHERE reservasi_id='$id'
"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Status Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 col-md-6">

    <div class="card p-4 shadow">
        <h4 class="fw-bold mb-3">Edit Status Reservasi</h4>

        <form action="keloladatareservasiedit_function.php" method="post">
            <input type="hidden" name="id" value="<?= $data['reservasi_id']; ?>">

            <p><b>Nama Pasien:</b> <?= $data['username']; ?></p>
            <p><b>Dokter:</b> <?= $data['nama_dokter']; ?></p>

            <label>Status Reservasi</label>
            <select name="status" class="form-control mb-3">
                <option value="menunggu dikonfirmasi" <?= ($data['status']=="menunggu dikonfirmasi"?"selected":"") ?>>Menunggu Dikonfirmasi</option>
                <option value="reservasi berhasil" <?= ($data['status']=="reservasi berhasil"?"selected":"") ?>>Reservasi Berhasil</option>
                <option value="reservasi dibatalkan" <?= ($data['status']=="reservasi dibatalkan"?"selected":"") ?>>Reservasi Dibatalkan</option>
            </select>

            <button class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

</div>

</body>
</html>
