<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit();
}

// Ambil data dari form
$id_users = $_SESSION['id_users'];
$id_buku = (int)$_POST['id_buku'];
$jumlah = (int)$_POST['jumlah'];
$metode_pengiriman = (int)$_POST['metode_pengiriman'];
$metode_pembayaran = (int)$_POST['metode_pembayaran'];
$total_harga = (int)$_POST['total_harga'];
$biaya_pengiriman = (int)$_POST['biaya_pengiriman'];
$total_belanja = (int)$_POST['total_belanja'];

// Query alamat pengguna
$query_alamat = "SELECT id_alamat FROM alamat WHERE id_users = $id_users";
$result_alamat = mysqli_query($koneksi, $query_alamat);
$alamat = mysqli_fetch_assoc($result_alamat);
$id_alamat = $alamat['id_alamat'];

// Generate nomor pesanan unik
$nomor_pesanan = 'BUKU' . date('Ymd') . strtoupper(uniqid());

// Insert data ke tabel pesanan
$query_pesanan = "INSERT INTO pesanan (id_users, id_alamat, total_belanja, tanggal_pesanan, metode_pengiriman, metode_pembayaran, bukti, status)
                  VALUES ($id_users, $id_alamat, $total_belanja, NOW(), $metode_pengiriman, $metode_pembayaran, '', 'menunggu pembayaran')";
    

if (mysqli_query($koneksi, $query_pesanan)) {
    $id_pesanan = mysqli_insert_id($koneksi);
    
    // Insert detail pesanan
    $query_detail = "INSERT INTO detailpesanan (id_pesanan, id_buku, jumlah, harga)
                     VALUES ($id_pesanan, $id_buku, $jumlah, $total_harga)";
    
    if (mysqli_query($koneksi, $query_detail)) {
        // Kurangi stok buku
    $query_kurangi_stok = "UPDATE buku SET stok = stok - $jumlah WHERE id_buku = $id_buku";
    
    if (mysqli_query($koneksi, $query_kurangi_stok)) {
        // Redirect ke halaman pembayaran dengan nomor pesanan
        header("Location: pembayaran2.php?id_pesanan=$id_pesanan");
    } else {
        echo "Error mengurangi stok: " . mysqli_error($koneksi);
    }
} else {
    echo "Error: " . $query_detail . "<br>" . mysqli_error($koneksi);
}
}