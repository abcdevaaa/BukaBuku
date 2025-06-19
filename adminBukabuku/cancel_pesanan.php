<?php
session_start();
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pesanan = $_POST['id_pesanan'];

    // Update status pesanan menjadi dibatalkan
    $query = "UPDATE pesanan SET 
              status = 'pesanan dibatalkan'
              WHERE id_pesanan = ?";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_pesanan);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['alert_message'] = "Pesanan berhasil dibatalkan";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['alert_message'] = "Gagal membatalkan pesanan: " . mysqli_error($koneksi);
        $_SESSION['alert_type'] = "danger";
    }

    mysqli_stmt_close($stmt);
    header("Location: pesanan.php");
    exit();
} else {
    header("Location: pesanan.php");
    exit();
}
?>