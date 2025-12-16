<?php
session_start();
include "koneksi.php";

// 1. CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: login.php");
    exit;
}

// 2. CONFIG PAGINATION & PENCARIAN
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$start = ($page - 1) * $limit;

$keyword = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : "";

// Filter Pencarian (Nama, Email, atau No Telpon)
$search_filter = "";
if (!empty($keyword)) {
    $search_filter = "AND (username LIKE '%$keyword%' OR email LIKE '%$keyword%' OR no_telpon LIKE '%$keyword%')";
}

// ------------------------------------------------------------------------------------
// KUNCI UTAMA: Tambahkan "WHERE role = 'pasien'"
// Ini akan memfilter agar Admin (1, 2, 3) TIDAK MUNCUL di daftar ini.
// ------------------------------------------------------------------------------------

// 3. HITUNG TOTAL DATA (Hanya Pasien)
$totalQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengguna WHERE role = 'pasien' $search_filter");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPage = ceil($totalData / $limit);

// 4. AMBIL DATA PASIEN (Hanya Pasien)
$query = mysqli_query($koneksi, "
    SELECT * FROM pengguna 
    WHERE role = 'pasien' $search_filter 
    ORDER BY id_pengguna DESC 
    LIMIT $start, $limit
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Pasien</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }
        
        .navbar-custom { 
            background: linear-gradient(135deg, #0f4c75, #3282b8); 
            padding: 15px 0; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .card-table {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .table-custom th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            border-bottom: 2px solid #dee2e6;
        }
        .table-custom td { vertical-align: middle; padding: 15px 10px; }

        /* Tombol Aksi */
        .btn-action {
            width: 35px; height: 35px;
            border-radius: 50%;
            display: inline-flex; justify-content: center; align-items: center;
            border: none; transition: 0.3s;
        }
        .btn-delete { background: #fee2e2; color: #b91c1c; }
        .btn-delete:hover { background: #b91c1c; color: white; }

        .pagination .page-link {
            border-radius: 50%; margin: 0 3px; width: 35px; height: 35px;
            display: flex; align-items: center; justify-content: center;
            color: #0f4c75; border: none;
        }
        .pagination .active .page-link { background-color: #0f4c75; color: white; }
    </style>
</head>

<body>

<nav class="navbar navbar-custom navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-people-fill me-2"></i> DATA PASIEN
        </a>
    </div>
</nav>

<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="admin.php" class="text-decoration-none text-secondary fw-bold">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a>
        
        <form method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control border-0 shadow-sm ps-4" 
                       placeholder="Cari pasien..." value="<?= htmlspecialchars($keyword) ?>" style="border-radius: 50px 0 0 50px;">
                <button class="btn btn-primary px-4 shadow-sm" style="border-radius: 0 50px 50px 0; background-color: #0f4c75; border-color: #0f4c75;">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="card-table">
        <h4 class="fw-bold mb-4" style="color: #0f4c75;">Daftar Pasien Terdaftar</h4>
        
        <div class="table-responsive">
            <table class="table table-custom table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Nama Pasien atau Username</th>
                        <th>Email</th>
                        <th>No. Telpon</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = $start + 1;
                    while($row = mysqli_fetch_assoc($query)) { 
                    ?>
                    <tr>
                        <td class="text-center fw-bold text-muted"><?= $no++; ?></td>
                        <td class="fw-bold text-dark"><?= $row['username']; ?></td>
                        <td class="text-secondary"><?= $row['email']; ?></td>
                        <td class="text-secondary"><?= $row['no_telpon']; ?></td>
                        <td class="text-center">
                            <a href="pasienhapus.php?id=<?= $row['id_pengguna']; ?>" 
                               class="btn-action btn-delete"
                               onclick="konfirmasiHapus(event)"
                               title="Hapus Pasien">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if(mysqli_num_rows($query) == 0): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                            Tidak ada data pasien ditemukan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPage > 1): ?>
        <div class="d-flex justify-content-end mt-4">
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= $keyword ?>"><?= $i ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
    function konfirmasiHapus(event) {
        event.preventDefault();
        const link = event.currentTarget.getAttribute('href');
        
        Swal.fire({
            title: 'Hapus Pasien?',
            text: "Data pasien dan riwayat reservasinya akan terhapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
            }
        });
    }
</script>

</body>
</html>