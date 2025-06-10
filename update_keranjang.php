<?php
session_start();
include "koneksi.php";



$id_users = $_SESSION['id_users'];
$id_buku = $_POST['id_buku'];
$jumlah = $_POST['jumlah'];

$sql = "UPDATE keranjang SET jumlah = '$jumlah' WHERE id_users = '$id_users' AND id_buku = '$id_buku'";
$query = mysqli_query($koneksi, $sql);

echo "ok";
?>
