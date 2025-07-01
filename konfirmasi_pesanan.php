<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: LoginRegister.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_pesanan'])) {
    $id_pesanan = (int)$_POST['id_pesanan'];
    
    // Update status pesanan menjadi 'pesanan diterima'
    $query = "UPDATE pesanan SET status = 'pesanan diterima' 
              WHERE id_pesanan = $id_pesanan AND id_users = {$_SESSION['id_users']}";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: rincian.php?id_pesanan=$id_pesanan");
        exit();
    } else {
        die("Error: " . mysqli_error($koneksi));
    }
} else {
    header("Location: transaksiU.php");
    exit();
}
?>