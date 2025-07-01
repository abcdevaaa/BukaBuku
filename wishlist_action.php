<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_users'])) {
    header("Location: LoginRegister.php");
    exit();
}

$id_users = $_SESSION['id_users'];
$id_buku = $_POST['id_buku'];

// Check if book is already in wishlist
$check = mysqli_query($koneksi, "SELECT * FROM wishlist WHERE id_users = $id_users AND id_buku = $id_buku");

if (mysqli_num_rows($check) > 0) {
    // Remove from wishlist
    mysqli_query($koneksi, "DELETE FROM wishlist WHERE id_users = $id_users AND id_buku = $id_buku");
} else {
    // Add to wishlist
    mysqli_query($koneksi, "INSERT INTO wishlist (id_users, id_buku) VALUES ($id_users, $id_buku)");
}

// Redirect back to previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>