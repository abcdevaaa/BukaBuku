<?php
session_start();
include "koneksi.php";

// Periksa apakah user sudah login
if (!isset($_SESSION['id_users'])) {
    echo json_encode(['success' => false, 'message' => 'Anda belum login']);
    exit();
}

// Ambil data dari POST request
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id_alamat'])) {
    echo json_encode(['success' => false, 'message' => 'ID alamat tidak valid']);
    exit();
}

// Escape string untuk mencegah SQL injection
$id_alamat = mysqli_real_escape_string($koneksi, $data['id_alamat']);
$id_users = mysqli_real_escape_string($koneksi, $_SESSION['id_users']);

// Query untuk hapus alamat
$query = "DELETE FROM alamat WHERE id_alamat = '$id_alamat' AND id_users = '$id_users'";

if (mysqli_query($koneksi, $query)) {
    if (mysqli_affected_rows($koneksi) > 0) {
        echo json_encode(['success' => true, 'message' => 'Alamat berhasil dihapus']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Alamat tidak ditemukan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus alamat: ' . mysqli_error($koneksi)]);
}

mysqli_close($koneksi);
?>