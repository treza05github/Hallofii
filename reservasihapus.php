<?php
include "koneksi.php";

$id = $_GET['id'];

mysqli_query($koneksi, "
    UPDATE reservasi 
    SET status='reservasi dibatalkan'
    WHERE reservasi_id='$id'
");

header("Location: reservasi.php");
exit;
?>
