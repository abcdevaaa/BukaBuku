<?php
session_start();
include('../koneksi.php');

// if (!isset($_SESSION['username'])) {
//     header("location:login.php?pesan=logindulu");
//     exit;
// }

$id_buku = $_GET['id_buku'];

$sql = "DELETE FROM buku WHERE id_buku = '$id_buku'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
    header("location:buku.php?hapus=sukses");
    exit;
} else {
    header("location:buku.php?hapus=gagal");
    exit;
}
?>