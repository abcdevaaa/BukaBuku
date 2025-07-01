<?php
session_start();
require 'koneksi.php';

// Cek session user
if (!isset($_SESSION['id_users'])) {
    header("Location: LoginRegister.php");
    exit();
}

// Ambil data dari form
$id_users = $_SESSION['id_users'];
$metode_pengiriman = (int)$_POST['metode_pengiriman'];
$metode_pembayaran = (int)$_POST['metode_pembayaran'];
$total_harga = (int)$_POST['total_harga'];
$from_cart = isset($_POST['from_cart']) ? (int)$_POST['from_cart'] : 0;

// Validasi data penting
if ($metode_pengiriman <= 0 || $metode_pembayaran <= 0 || $total_harga <= 0) {
    die("Data pembayaran tidak valid");
}

// 1. Ambil data alamat pengguna
$query_alamat = "SELECT id_alamat FROM alamat WHERE id_users = ?";
$stmt = mysqli_prepare($koneksi, $query_alamat);
mysqli_stmt_bind_param($stmt, "i", $id_users);
mysqli_stmt_execute($stmt);
$result_alamat = mysqli_stmt_get_result($stmt);

if (!$result_alamat || mysqli_num_rows($result_alamat) == 0) {
    die("Alamat pengiriman tidak ditemukan");
}
$alamat = mysqli_fetch_assoc($result_alamat);
$id_alamat = $alamat['id_alamat'];

// 2. Ambil biaya pengiriman
$query_pengiriman = "SELECT biaya FROM metode_pengiriman WHERE id_metodePengiriman = ?";
$stmt = mysqli_prepare($koneksi, $query_pengiriman);
mysqli_stmt_bind_param($stmt, "i", $metode_pengiriman);
mysqli_stmt_execute($stmt);
$result_pengiriman = mysqli_stmt_get_result($stmt);

if (!$result_pengiriman || mysqli_num_rows($result_pengiriman) == 0) {
    die("Metode pengiriman tidak valid");
}
$pengiriman = mysqli_fetch_assoc($result_pengiriman);
$biaya_pengiriman = $pengiriman['biaya'];
$total_belanja = $total_harga + $biaya_pengiriman;

// 3. Insert data pesanan ke database (tanpa nomor_pesanan)
$query_pesanan = "INSERT INTO pesanan (id_users, id_alamat, total_belanja, 
                  tanggal_pesanan, metode_pengiriman, metode_pembayaran, bukti, status)
                  VALUES (?, ?, ?, NOW(), ?, ?, '', 'menunggu pembayaran')";

$stmt = mysqli_prepare($koneksi, $query_pesanan);
mysqli_stmt_bind_param($stmt, "iiiii", $id_users, $id_alamat, 
                      $total_belanja, $metode_pengiriman, $metode_pembayaran);

if (!mysqli_stmt_execute($stmt)) {
    die("Gagal membuat pesanan: " . mysqli_error($koneksi));
}

$id_pesanan = mysqli_insert_id($koneksi);

// 4. Proses item pembelian
// Di bagian cart purchase:
if ($from_cart) {
    // Pembelian dari keranjang
    if (!isset($_POST['selected_items']) || empty($_POST['selected_items'])) {
        die("Item keranjang tidak valid");
    }

    // Proses setiap item yang dipilih
    foreach ($_POST['selected_items'] as $id_buku) {
        $id_buku = (int)$id_buku;
        $jumlah = (int)$_POST['keranjang'][$id_buku]['jumlah']; // Ambil jumlah dari POST
        
        // Hapus item dari keranjang di database
        $query_hapus_keranjang = "DELETE FROM keranjang WHERE id_users = ? AND id_buku = ?";
        $stmt = mysqli_prepare($koneksi, $query_hapus_keranjang);
        mysqli_stmt_bind_param($stmt, "ii", $id_users, $id_buku);
        mysqli_stmt_execute($stmt);
        
        // Proses item untuk pesanan
        process_book_item($koneksi, $id_pesanan, $id_buku, $jumlah);
    }
    
    // Kosongkan session keranjang checkout jika ada
    if (isset($_SESSION['cart_checkout_items'])) {
        unset($_SESSION['cart_checkout_items']);
    }

} else {
    // Pembelian langsung
    if (!isset($_POST['id_buku']) || !isset($_POST['jumlah'])) {
        die("Data buku tidak valid");
    }
    
    $id_buku = (int)$_POST['id_buku'];
    $jumlah = (int)$_POST['jumlah'];
    
    process_book_item($koneksi, $id_pesanan, $id_buku, $jumlah);
}

// Redirect ke halaman pembayaran
header("Location: pembayaran2.php?id_pesanan=$id_pesanan");
exit();

// Fungsi untuk memproses item buku
function process_book_item($koneksi, $id_pesanan, $id_buku, $jumlah) {
    // Validasi input
    if ($id_buku <= 0 || $jumlah <= 0) {
        die("Data buku tidak valid");
    }

    // 1. Ambil data buku
    $query_buku = "SELECT harga, stok FROM buku WHERE id_buku = ?";
    $stmt = mysqli_prepare($koneksi, $query_buku);
    mysqli_stmt_bind_param($stmt, "i", $id_buku);
    mysqli_stmt_execute($stmt);
    $result_buku = mysqli_stmt_get_result($stmt);

    if (!$result_buku || mysqli_num_rows($result_buku) == 0) {
        die("Buku tidak ditemukan");
    }

    $buku = mysqli_fetch_assoc($result_buku);
    
    // 2. Cek stok cukup
    if ($buku['stok'] < $jumlah) {
        die("Stok buku tidak mencukupi");
    }

    $harga = $buku['harga'];
    
    // 3. Insert detail pesanan
    $query_detail = "INSERT INTO detailpesanan (id_pesanan, id_buku, jumlah, harga)
                     VALUES (?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($koneksi, $query_detail);
    mysqli_stmt_bind_param($stmt, "iiid", $id_pesanan, $id_buku, $jumlah, $harga);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Gagal menambahkan detail pesanan: " . mysqli_error($koneksi));
    }
    
    // 4. Kurangi stok buku
    $query_kurangi_stok = "UPDATE buku SET stok = stok - ? WHERE id_buku = ?";
    $stmt = mysqli_prepare($koneksi, $query_kurangi_stok);
    mysqli_stmt_bind_param($stmt, "ii", $jumlah, $id_buku);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Gagal mengurangi stok buku: " . mysqli_error($koneksi));
    }
}
?>