<?php
session_start();
include "koneksi.php";

$id_users = $_SESSION['id_users'];
$id_buku = $_POST['id_buku'];

mysqli_query($koneksi, "DELETE FROM keranjang WHERE id_users = $id_users AND id_buku = $id_buku");

header("Location: keranjang.php");
exit;
?>

