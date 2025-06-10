<?php
session_start();

if (!isset($_POST['metode_pembayaran']) || empty($_POST['metode_pembayaran'])) {
    header("Location: metode.php?error=pilih_metode");
    exit();
}

// Simpan metode pembayaran ke session
$_SESSION['metode_pembayaran'] = $_POST['metode_pembayaran'];

// Redirect ke halaman pembayaran
header("Location: pembayaran.php");
exit();
?>