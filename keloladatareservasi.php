<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: login.php");
    exit;
}
$admin = $_SESSION['username'];

// --- FUNGSI TANGGAL INDONESIA ---
function tgl_indo($tanggal)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
// --------------------------------

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";

$filter = ($search != "") ? "AND (p.username LIKE '%$search%' OR d.nama_dokter LIKE '%$search%')" : "";

$totalQ = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM reservasi r JOIN pengguna p ON r.id_pengguna=p.id_pengguna JOIN dokter d ON r.dokter_id=d.dokter_id WHERE 1 $filter");
$totalData = mysqli_fetch_assoc($totalQ)['total'];
$totalPage = ceil($totalData / $limit);

$query = mysqli_query($koneksi, "
    SELECT r.*, p.username, p.no_telpon, d.nama_dokter, d.spesialisasi 
    FROM reservasi r 
    JOIN pengguna p ON r.id_pengguna = p.id_pengguna 
    JOIN dokter d ON r.dokter_id = d.dokter_id 
    WHERE 1 $filter 
    ORDER BY r.reservasi_id DESC 
    LIMIT $start, $limit
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Kelola Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0f4c75, #3282b8);
            padding: 15px 0;
        }

        .card-table {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .text-price {
            color: #198754;
            font-weight: 600;
        }

        .badge-soft-warning {
            background: #fff7ed;
            color: #c2410c;
        }

        .badge-soft-success {
            background: #f0fdf4;
            color: #15803d;
        }

        .badge-soft-danger {
            background: #fef2f2;
            color: #b91c1c;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-custom navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-calendar-check-fill me-2"></i> KELOLA RESERVASI</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="admin.php" class="text-decoration-none text-muted mb-1 d-block small fw-bold"><i class="bi bi-arrow-left"></i> Dashboard</a>
                <h3 class="fw-bold" style="color: #0f4c75;">Daftar Reservasi</h3>
            </div>
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control rounded-pill me-2 border-0 shadow-sm" placeholder="Cari pasien/dokter..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-primary rounded-circle shadow-sm" style="background-color: #0f4c75; border:none;"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <div class="card card-table p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Jadwal</th>
                            <th>Tagihan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $start + 1;
                        while ($row = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td class="text-muted fw-bold"><?= $no++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $row['username']; ?></div>
                                    <div class="small text-muted"><?= $row['no_telpon']; ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= $row['nama_dokter']; ?></div>
                                    <div class="small text-muted"><?= $row['spesialisasi']; ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= tgl_indo($row['tanggal_waktu']); ?></div>
                                    <div class="small text-muted">Pukul <?= date('H:i', strtotime($row['tanggal_waktu'])); ?> WIB</div>
                                </td>
                                <td>
                                    <span class="text-price">Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></span>
                                </td>
                                <td>
                                    <?php if ($row['status'] == "menunggu dikonfirmasi") { ?>
                                        <span class="badge badge-soft-warning px-3 py-2 rounded-pill">Menunggu</span>
                                    <?php } elseif ($row['status'] == "reservasi berhasil") { ?>
                                        <span class="badge badge-soft-success px-3 py-2 rounded-pill">Berhasil</span>
                                    <?php } else { ?>
                                        <span class="badge badge-soft-danger px-3 py-2 rounded-pill">Batal</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <a href="keloladatareservasiedit.php?id=<?= $row['reservasi_id']; ?>" class="btn btn-sm btn-light text-primary rounded-circle p-2" title="Edit Status">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="keloladatareservasihapus.php?id=<?= $row['reservasi_id']; ?>"
                                        class="btn btn-sm btn-light text-danger rounded-circle p-2"
                                        title="Hapus" onclick="konfirmasiHapus(event)">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                    <?php if ($row['status'] == "reservasi berhasil") { ?>
                                        <a href="keloladatareservasicetakinvoice.php?id=<?= $row['reservasi_id']; ?>" target="_blank" class="btn btn-sm btn-light text-dark rounded-circle p-2" title="Cetak Invoice"><i class="bi bi-printer-fill"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="keloladatareservasicetaklaporan.php" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill fw-bold">
                    <i class="bi bi-printer-fill me-2"></i> Cetak Laporan
                </a>

                <?php if ($totalPage > 1): ?>
                    <ul class="pagination mb-0">
                        <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a></li>
                        <?php } ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function konfirmasiHapus(event) {
            event.preventDefault();
            const link = event.currentTarget.getAttribute('href');
            Swal.fire({
                title: 'Hapus Reservasi?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = link;
            });
        }
    </script>

</body>

</html>