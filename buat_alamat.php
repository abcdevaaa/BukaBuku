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

// Escape string untuk mencegah SQL injection
$id_users = mysqli_real_escape_string($koneksi, $_SESSION['id_users']);
$nama_penerima = mysqli_real_escape_string($koneksi, $data['nama_penerima']);
$no_telepon = mysqli_real_escape_string($koneksi, $data['no_telepon']);
$alamat_lengkap = mysqli_real_escape_string($koneksi, $data['alamat_lengkap']);
$kabupaten = mysqli_real_escape_string($koneksi, $data['kabupaten']);
$provinsi = mysqli_real_escape_string($koneksi, $data['provinsi']);
$kode_pos = mysqli_real_escape_string($koneksi, $data['kode_pos']);

// Query untuk insert alamat baru
$query = "INSERT INTO alamat (id_users, nama_penerima, no_telepon, alamat_lengkap, kabupaten, provinsi, kode_pos) 
          VALUES ('$id_users', '$nama_penerima', '$no_telepon', '$alamat_lengkap', '$kabupaten', '$provinsi', '$kode_pos')";

if (mysqli_query($koneksi, $query)) {
    echo json_encode(['success' => true, 'message' => 'Alamat berhasil ditambahkan']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan alamat: ' . mysqli_error($koneksi)]);
}

mysqli_close($koneksi);
?>