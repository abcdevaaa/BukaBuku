<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit();
}

$id_users = $_SESSION['id_users'];

// Validasi input
$errors = [];
$nama_penerima = $_POST['nama_penerima'] ?? '';
$no_telepon = $_POST['no_telepon'] ?? '';
$alamat_lengkap = $_POST['alamat_lengkap'] ?? '';
$kabupaten = $_POST['kabupaten'] ?? '';
$provinsi = $_POST['provinsi'] ?? '';
$kode_pos = $_POST['kode_pos'] ?? '';

// Cek apakah alamat sudah ada
$query_cek = "SELECT id_alamat FROM alamat WHERE id_users = $id_users";
$result_cek = mysqli_query($koneksi, $query_cek);

if (mysqli_num_rows($result_cek) > 0) {
    // Update alamat yang sudah ada
    $row = mysqli_fetch_assoc($result_cek);
    $id_alamat = $row['id_alamat'];
    
    $query = "UPDATE alamat SET 
              nama_penerima = '$nama_penerima',
              no_telepon = '$no_telepon',
              alamat_lengkap = '$alamat_lengkap',
              kabupaten = '$kabupaten',
              provinsi = '$provinsi',
              kode_pos = '$kode_pos'
              WHERE id_alamat = $id_alamat";
} else {
    // Buat alamat baru
    $query = "INSERT INTO alamat (id_users, nama_penerima, no_telepon, alamat_lengkap, kabupaten, provinsi, kode_pos)
              VALUES ($id_users, '$nama_penerima', '$no_telepon', '$alamat_lengkap', '$kabupaten', '$provinsi', '$kode_pos')";
}

if (mysqli_query($koneksi, $query)) {
    header("Location: checkout.php?id_buku=" . $_GET['id_buku'] . "&jumlah=" . $_GET['jumlah']);
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
}