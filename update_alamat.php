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
$id_alamat = mysqli_real_escape_string($koneksi, $data['id_alamat']);
$id_users = mysqli_real_escape_string($koneksi, $_SESSION['id_users']);
$nama_penerima = mysqli_real_escape_string($koneksi, $data['nama_penerima']);
$no_telepon = mysqli_real_escape_string($koneksi, $data['no_telepon']);
$alamat_lengkap = mysqli_real_escape_string($koneksi, $data['alamat_lengkap']);
$kabupaten = mysqli_real_escape_string($koneksi, $data['kabupaten']);
$provinsi = mysqli_real_escape_string($koneksi, $data['provinsi']);
$kode_pos = mysqli_real_escape_string($koneksi, $data['kode_pos']);

// Query untuk update alamat
$query = "UPDATE alamat SET 
          nama_penerima = '$nama_penerima',
          no_telepon = '$no_telepon',
          alamat_lengkap = '$alamat_lengkap',
          kabupaten = '$kabupaten',
          provinsi = '$provinsi',
          kode_pos = '$kode_pos'
          WHERE id_alamat = '$id_alamat' AND id_users = '$id_users'";

if (mysqli_query($koneksi, $query)) {
    if (mysqli_affected_rows($koneksi) > 0) {
        echo json_encode(['success' => true, 'message' => 'Alamat berhasil diperbarui']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak ada perubahan data atau alamat tidak ditemukan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui alamat: ' . mysqli_error($koneksi)]);
}

mysqli_close($koneksi);
?>