<?php
session_start();
include "koneksi.php";

// 1. CEK LOGIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") { 
    header("Location: login.php"); 
    exit; 
}

$username = $_SESSION['username'];

// 2. PAGINATION & PENCARIAN
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";

// Filter pencarian diperluas ke spesialisasi dan hari
$filter = "";
if ($search != "") {
    $filter = "WHERE nama_dokter LIKE '%$search%' OR spesialisasi LIKE '%$search%' OR jadwal_hari LIKE '%$search%'";
}

$totalQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dokter $filter");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage = ceil($totalData / $limit);

$query = mysqli_query($koneksi, "SELECT * FROM dokter $filter ORDER BY dokter_id DESC LIMIT $start, $limit");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokter</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background: #f0f2f5; font-family: 'Poppins', sans-serif; }
        
        .navbar-custom { 
            background: linear-gradient(135deg, #0f4c75, #3282b8); 
            padding: 15px 0; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .card-table { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        .table th { 
            background: #f8f9fa; 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
            color: #555; 
            border-bottom: 2px solid #eee;
        }
        .table td { vertical-align: middle; padding: 15px 10px; }

        .foto-sm { 
            width: 50px; height: 50px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 2px solid #dee2e6;
        }

        .badge-biaya {
            background-color: #d1e7dd; color: #0f5132;
            padding: 5px 10px; border-radius: 50px;
            font-weight: 600; font-size: 0.85rem;
        }
        
        /* Pagination */
        .pagination .page-link { border-radius: 50%; margin: 0 3px; border: none; color: #555; }
        .pagination .active .page-link { background-color: #0f4c75; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-custom navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-people-fill me-2"></i> DATA DOKTER
        </a>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="admin.php" class="text-decoration-none text-muted mb-1 d-block small fw-bold">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <h3 class="fw-bold" style="color: #0f4c75;">Kelola Dokter</h3>
        </div>
        <a href="doktertambah.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" style="background-color: #0f4c75; border:none;">
            <i class="bi bi-plus-lg"></i> Tambah Dokter
        </a>
    </div>

    <div class="card card-table p-4 bg-white">
        
        <div class="d-flex justify-content-end mb-3">
            <form method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control border-0 bg-light" placeholder="Cari dokter..." value="<?= htmlspecialchars($search) ?>" style="border-radius: 50px 0 0 50px;">
                    <button class="btn btn-light border-0" style="border-radius: 0 50px 50px 0;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%">Foto</th>
                        <th width="25%">Nama & Spesialis</th>
                        <th width="25%">Jadwal Praktik</th>
                        <th width="20%">Biaya Konsultasi</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = $start + 1; while ($row = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td class="text-center text-muted fw-bold"><?= $no++; ?></td>
                        <td>
                            <?php if ($row['foto_dokter']) { ?>
                                <img src="data:image/*;base64,<?= base64_encode($row['foto_dokter']); ?>" class="foto-sm">
                            <?php } else { ?>
                                <img src="https://via.placeholder.com/50" class="foto-sm">
                            <?php } ?>
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= $row['nama_dokter']; ?></div>
                            <div class="small text-muted"><?= $row['spesialisasi']; ?></div>
                        </td>
                        <td>
                            <div class="text-dark"><i class="bi bi-calendar-check me-1 text-primary"></i> <?= $row['jadwal_hari']; ?></div>
                            <div class="small text-muted ms-4"><?= $row['jadwal_jam']; ?></div>
                        </td>
                        <td>
                            <span class="badge-biaya">
                                Rp <?= number_format($row['biaya_konsultasi'], 0, ',', '.'); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="dokteredit.php?id=<?= $row['dokter_id'] ?>" class="btn btn-sm btn-light text-primary rounded-circle p-2" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="dokterhapus.php?id=<?= $row['dokter_id'] ?>" 
                               class="btn btn-sm btn-light text-danger rounded-circle p-2" 
                               title="Hapus"
                               onclick="konfirmasiHapus(event)">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                
                <?php if(mysqli_num_rows($query) == 0): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">Tidak ada data dokter ditemukan.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPage > 1): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-end">
                <?php for ($i=1; $i<=$totalPage; $i++) { ?>
                    <li class="page-item <?= ($i==$page)?'active':'' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<script>
    function konfirmasiHapus(event) {
        event.preventDefault();
        const link = event.currentTarget.getAttribute('href');
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data dokter ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
            }
        });
    }
</script>

</body>
</html>