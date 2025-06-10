<?php
session_start();
include "koneksi.php";

// Periksa apakah user sudah login
if (!isset($_SESSION['id_users'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu']);
    exit();
}

// Periksa apakah ID buku dikirim
if (!isset($_POST['id_buku'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID buku tidak valid']);
    exit();
}

$id_users = $_SESSION['id_users'];
$id_buku = $_POST['id_buku'];

// Lakukan penghapusan dari database
$query = "DELETE FROM keranjang WHERE id_users = ? AND id_buku = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ii", $id_users, $id_buku);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Item berhasil dihapus']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus item: ' . mysqli_error($koneksi)]);
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>

