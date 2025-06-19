<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_users'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

$id_buku = $_POST['id_buku'];
$id_user = $_SESSION['id_users'];

// Cek apakah buku sudah ada di wishlist
$check = mysqli_query($koneksi, "SELECT * FROM wishlist WHERE id_users = '$id_user' AND id_buku = '$id_buku'");
if (mysqli_num_rows($check) > 0) {
    // Hapus dari wishlist jika sudah ada
    mysqli_query($koneksi, "DELETE FROM wishlist WHERE id_users = '$id_user' AND id_buku = '$id_buku'");
    echo json_encode(['status' => 'removed']);
} else {
    // Tambahkan ke wishlist jika belum ada
    mysqli_query($koneksi, "INSERT INTO wishlist (id_users, id_buku) VALUES ('$id_user', '$id_buku')");
    echo json_encode(['status' => 'added']);
}
?>