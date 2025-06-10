<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: Loginregister.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: checkout.php");
    exit();
}

// Validasi data
$required_fields = ['shipping_method', 'alamat_id', 'items'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error'] = "Data pembayaran tidak lengkap";
        header("Location: checkout.php");
        exit();
    }
}

// Decode items
$items = json_decode($_POST['items'], true);
if (json_last_error() !== JSON_ERROR_NONE || empty($items)) {
    $_SESSION['error'] = "Data item tidak valid";
    header("Location: checkout.php");
    exit();
}

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {
    // 1. Simpan data pesanan
    $stmt = mysqli_prepare($koneksi, 
        "INSERT INTO pesanan (id_users, id_alamat, total_belanja, tanggal_pesanan, metode_pengiriman, status) 
         VALUES (?, ?, ?, ?, ?, 'menunggu pembayaran')");
    
    $tanggal_pesanan = date('Y-m-d H:i:s');
    mysqli_stmt_bind_param($stmt, "iidss", 
        $_SESSION['id_users'],
        $_POST['alamat_id'],
        $_POST['total_pembayaran'],
        $tanggal_pesanan,
        $_POST['shipping_method']
    );
    
    mysqli_stmt_execute($stmt);
    $id_pesanan = mysqli_insert_id($koneksi);
    
    // 2. Simpan detail pesanan
    $detail_stmt = mysqli_prepare($koneksi,
        "INSERT INTO detail_pesanan (id_pesanan, id_buku, jumlah, harga) 
         VALUES (?, ?, ?, ?)");
    
    foreach ($items as $item) {
        mysqli_stmt_bind_param($detail_stmt, "iiid", 
            $id_pesanan,
            $item['id_buku'],
            $item['jumlah'],
            $item['harga']
        );
        mysqli_stmt_execute($detail_stmt);
        
        // Kurangi stok
        $update_stmt = mysqli_prepare($koneksi,
            "UPDATE buku SET stok = stok - ? WHERE id_buku = ?");
        mysqli_stmt_bind_param($update_stmt, "ii", $item['jumlah'], $item['id_buku']);
        mysqli_stmt_execute($update_stmt);
    }
    
    // 3. Hapus dari keranjang
    $item_ids = array_column($items, 'id_buku');
    $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
    $delete_stmt = mysqli_prepare($koneksi,
        "DELETE FROM keranjang WHERE id_users = ? AND id_buku IN ($placeholders)");
    
    $params = array_merge([$_SESSION['id_users']], $item_ids);
    $types = str_repeat('i', count($params));
    mysqli_stmt_bind_param($delete_stmt, $types, ...$params);
    mysqli_stmt_execute($delete_stmt);
    
    // Commit transaksi
    mysqli_commit($koneksi);
    
    // Set session untuk pembayaran
    $_SESSION['id_pesanan'] = $id_pesanan;
    $_SESSION['total_pembayaran'] = $_POST['total_pembayaran'];
    
    header("Location: metode.php");
    exit();
    
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $_SESSION['error'] = "Gagal memproses pesanan: " . $e->getMessage();
    header("Location: checkout.php");
    exit();
}
?>