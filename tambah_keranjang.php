<?php
session_start();
include "koneksi.php";

$id_users = $_SESSION['id_users']; // pastikan sudah login
$id_buku = $_GET['id_buku'];

// Cek apakah buku sudah ada di keranjang
$query = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE id_users = $id_users AND id_buku = $id_buku");
if (mysqli_num_rows($query) > 0) {
    // Jika sudah ada, update jumlah
    mysqli_query($koneksi, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_users = $id_users AND id_buku = $id_buku");
} else {
    // Jika belum, tambahkan baru
    mysqli_query($koneksi, "INSERT INTO keranjang (id_users, id_buku, jumlah) VALUES ($id_users, $id_buku, 1)");
}

header("Location:keranjang.php");
exit;
?>