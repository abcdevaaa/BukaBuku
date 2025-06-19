<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit();
}

$id_pesanan = (int)$_POST['id_pesanan'];

// Validasi file upload
if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['bukti_pembayaran'];
    
    // Validasi tipe file
    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($file['type'], $allowed_types)) {
        die("Hanya file JPG/JPEG/PNG yang diizinkan");
    }
    
    // Validasi ukuran file (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        die("Ukuran file maksimal 2MB");
    }
    
    // Generate nama file unik
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $ext;
    $upload_path = 'bukti_pembayaran/' . $filename;
    
    // Pindahkan file ke folder uploads
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Update database dengan nama file bukti
        $query = "UPDATE pesanan SET bukti = '$filename', status = 'pesanan diproses' WHERE id_pesanan = $id_pesanan";
        
        if (mysqli_query($koneksi, $query)) {
            header("Location: rincian.php?id_pesanan=$id_pesanan");
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
        }
    } else {
        die("Gagal mengupload file");
    }
} else {
    die("Terjadi kesalahan saat upload file");
}