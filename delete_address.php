<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$id_users = $_SESSION['id_users'];
$id_alamat = $_POST['id_alamat'] ?? 0;

if (empty($id_alamat)) {
    echo json_encode(['success' => false, 'message' => 'Invalid address ID']);
    exit();
}

$query = "DELETE FROM alamat WHERE id_alamat = $id_alamat AND id_users = $id_users";
$result = mysqli_query($koneksi, $query);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($koneksi)]);
}
?>