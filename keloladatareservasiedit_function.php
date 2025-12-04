<?php
include "koneksi.php";

$id = $_POST['id'];
$status = $_POST['status'];

mysqli_query($koneksi,"
    UPDATE reservasi SET status='$status' WHERE reservasi_id='$id'
");

header("Location: keloladatareservasi.php");
exit;
