<?php
include "koneksi.php";

$id = $_GET['id'];

mysqli_query($koneksi,"DELETE FROM reservasi WHERE reservasi_id='$id'");
header("Location: keloladatareservasi.php");
exit;
