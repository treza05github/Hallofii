<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

function tgl_indo($tanggal){
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

$id_pengguna = $_SESSION['id_pengguna'];
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;
$keyword = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";

$where = "WHERE r.id_pengguna = '$id_pengguna' AND (d.nama_dokter LIKE '%$keyword%' OR r.status LIKE '%$keyword%')";

$totalQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM reservasi r JOIN dokter d ON r.dokter_id = d.dokter_id $where");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage = ceil($totalData / $limit);

$query = mysqli_query($koneksi, "SELECT r.*, d.nama_dokter, d.spesialisasi FROM reservasi r JOIN dokter d ON r.dokter_id = d.dokter_id $where ORDER BY r.reservasi_id DESC LIMIT $start, $limit");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .card-table { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .badge-soft-warning { background: #fff7ed; color: #c2410c; }
        .badge-soft-success { background: #f0fdf4; color: #15803d; }
        .badge-soft-danger { background: #fef2f2; color: #b91c1c; }
        .text-price { color: #198754; font-weight: 600; }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark bg-primary mb-4 py-3">
        <div class="container">
            <span class="navbar-brand fw-bold">RESERVASI</span>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="pasien.php" class="text-decoration-none text-muted mb-1 d-block small"><i class="bi bi-arrow-left"></i> Dashboard</a>
                <h3 class="fw-bold">Reservasi Saya</h3>
            </div>
            <div class="d-flex gap-2">
                <a href="reservasiform.php" class="btn btn-primary rounded-pill"><i class="bi bi-plus-lg"></i> Buat Baru</a>
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control rounded-pill me-2" placeholder="Cari..." value="<?= htmlspecialchars($keyword) ?>">
                    <button class="btn btn-secondary rounded-circle"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        <div class="card card-table p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Dokter</th>
                            <th>Jadwal</th>
                            <th>Tagihan (Bayar di Klinik)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $start + 1;
                        while ($row = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <span class="fw-bold"><?= $row['nama_dokter']; ?></span><br>
                                    <small class="text-muted"><?= $row['spesialisasi']; ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">
                                        <?= tgl_indo($row['tanggal_waktu']); ?>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="bi bi-clock"></i> Pukul <?= date('H:i', strtotime($row['tanggal_waktu'])); ?> WIB
                                    </div>
                                </td>
                                <td>
                                    <span class="text-price">Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></span>
                                </td>
                                <td>
                                    <?php if ($row['status'] == "menunggu dikonfirmasi") { ?>
                                        <span class="badge badge-soft-warning px-3 py-2 rounded-pill">Menunggu</span>
                                    <?php } elseif ($row['status'] == "reservasi berhasil") { ?>
                                        <span class="badge badge-soft-success px-3 py-2 rounded-pill">Disetujui</span>
                                        <div class="small text-muted mt-2 fst-italic" style="font-size: 11px; line-height: 1.2;">
                                            Silakan cetak atau tunjukkan<br>invoice dan lakukan check-in.
                                        </div>
                                    <?php } else { ?>
                                        <span class="badge badge-soft-danger px-3 py-2 rounded-pill">Batal</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] == "menunggu dikonfirmasi") { ?>
                                        <a href="reservasiedit.php?id=<?= $row['reservasi_id']; ?>" class="btn btn-sm btn-light text-primary"><i class="bi bi-pencil-square"></i></a>
                                        <a href="reservasihapus.php?id=<?= $row['reservasi_id']; ?>" onclick="konfirmasiBatal(event)" class="btn btn-sm btn-light text-danger"><i class="bi bi-x-circle"></i></a>
                                    <?php } else { ?>
                                        <button class="btn btn-sm btn-light text-muted" disabled><i class="bi bi-pencil-square"></i></button>
                                        <button class="btn btn-sm btn-light text-muted" disabled><i class="bi bi-x-circle"></i></button>
                                    <?php } ?>
                                    
                                    <?php if ($row['status'] == "reservasi berhasil") { ?>
                                        <a href="reservasicetak.php?id=<?= $row['reservasi_id']; ?>" target="_blank" class="btn btn-sm btn-light text-dark" title="Cetak Invoice"><i class="bi bi-printer"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <nav class="mt-3">
                <ul class="pagination justify-content-end">
                    <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= $keyword ?>"><?= $i ?></a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>

    <script>
        function konfirmasiBatal(event) {
            event.preventDefault();
            const link = event.currentTarget.getAttribute('href');
            Swal.fire({
                title: 'Batalkan Reservasi?',
                text: "Apakah Anda yakin ingin membatalkan jadwal ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link;
                }
            });
        }
    </script>

</body>
</html>