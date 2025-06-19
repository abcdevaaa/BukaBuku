<?php
session_start();
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $status = $_POST['status'];
    
    // Update data pesanan
    $query = "UPDATE pesanan SET 
              status = ?
              WHERE id_pesanan = ?";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id_pesanan);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['alert_message'] = "Pesanan berhasil diperbarui";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['alert_message'] = "Gagal memperbarui pesanan: " . mysqli_error($koneksi);
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