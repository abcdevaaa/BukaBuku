<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$id_users = $_SESSION['id_users'];
$nama_penerima = $_POST['nama_penerima'] ?? '';
$no_telepon = $_POST['no_telepon'] ?? '';
$alamat_lengkap = $_POST['alamat_lengkap'] ?? '';
$kabupaten = $_POST['kabupaten'] ?? '';
$provinsi = $_POST['provinsi'] ?? '';
$kode_pos = $_POST['kode_pos'] ?? '';

// Validate input
if (empty($nama_penerima) || empty($no_telepon) || empty($alamat_lengkap) || 
    empty($kabupaten) || empty($provinsi) || empty($kode_pos)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

// Check if we're updating an existing address or creating a new one
if (isset($_POST['id_alamat']) && !empty($_POST['id_alamat'])) {
    $id_alamat = (int)$_POST['id_alamat'];
    $query = "UPDATE alamat SET 
              nama_penerima = '$nama_penerima',
              no_telepon = '$no_telepon',
              alamat_lengkap = '$alamat_lengkap',
              kabupaten = '$kabupaten',
              provinsi = '$provinsi',
              kode_pos = '$kode_pos'
              WHERE id_alamat = $id_alamat AND id_users = $id_users";
} else {
    $query = "INSERT INTO alamat (id_users, nama_penerima, no_telepon, alamat_lengkap, kabupaten, provinsi, kode_pos)
              VALUES ($id_users, '$nama_penerima', '$no_telepon', '$alamat_lengkap', '$kabupaten', '$provinsi', '$kode_pos')";
}

$result = mysqli_query($koneksi, $query);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($koneksi)]);
}
?>