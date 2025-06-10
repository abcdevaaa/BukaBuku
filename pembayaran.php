<?php
session_start();
include 'koneksi.php';

// Redirect jika belum login
if (!isset($_SESSION['id_users'])) {
    header("Location: Loginregister.php");
    exit();
}

//Validasi data checkout - Perubahan utama di sini
if (!isset($_SESSION['checkout_data']) || 
    !isset($_SESSION['checkout_data']['items']) || 
    empty($_SESSION['checkout_data']['items'])) {
    $_SESSION['error'] = 'Tidak ada item yang dipilih untuk checkout';
    header("Location: keranjang.php"); // Redirect kembali ke keranjang
    exit();
}

//Validasi data ongkir dan alamat
if (!isset($_SESSION['checkout_data']['ongkir']) || 
    !isset($_SESSION['checkout_data']['ongkir']['metode']) || 
    !isset($_SESSION['checkout_data']['alamat']) || 
    !isset($_SESSION['checkout_data']['alamat']['id_alamat'])) {
    header("Location: checkout.php");
    exit();
}

// Redirect jika metode pembayaran belum dipilih
if (!isset($_SESSION['metode_pembayaran'])) {
    header("Location: metode.php?error=metode_belum_dipilih");
    exit();
}

$error = '';
$success = '';

// Proses form pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm-payment'])) {
    // Validasi file upload
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['payment_proof'];
        
        // Validasi tipe file
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file['type'], $allowed_types) || !in_array($file_ext, ['jpeg', 'png', 'jpg'])) {
            $error = 'Format file harus JPG, JPEG, atau PNG';
        }
        // Validasi ukuran file (max 2MB)
        elseif ($file['size'] > 2097152) {
            $error = 'Ukuran file maksimal 2MB';
        } else {
            // Generate nama file unik
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file_ext;
            $upload_path = 'bukti_pembayaran/' . $filename;
            
            // Buat folder jika belum ada
            if (!file_exists('bukti_pembayaran')) {
                mkdir('bukti_pembayaran', 0777, true);
            }
            
            // Pindahkan file ke folder upload
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Ambil data dari session
                $checkout_data = $_SESSION['checkout_data'];
                $id_user = $_SESSION['id_users'];
                $nama_metode = $_SESSION['metode_pembayaran'];
                $nama_pengiriman = $checkout_data['ongkir']['metode'];
                $id_alamat = $checkout_data['alamat']['id_alamat'];
                $total_belanja = $checkout_data['total_harga'] + $checkout_data['ongkir']['biaya'];
                
                // Mulai transaksi
                $koneksi->begin_transaction();
                
                try {
                    // 1. Dapatkan ID metode pembayaran
                    $query_metode = "SELECT id_metodePembayaran FROM metode_pembayaran WHERE nama_metode = ?";
                    $stmt_metode = $koneksi->prepare($query_metode);
                    $stmt_metode->bind_param("s", $nama_metode);
                    $stmt_metode->execute();
                    $result_metode = $stmt_metode->get_result();
                    
                    if ($result_metode->num_rows === 0) {
                        throw new Exception('Metode pembayaran tidak valid');
                    }
                    $metode_pembayaran = $result_metode->fetch_assoc()['id_metodePembayaran'];
                    $stmt_metode->close();
                    
                    // 2. Dapatkan ID metode pengiriman
                    $query_pengiriman = "SELECT id_metodePengiriman FROM metode_pengiriman WHERE nama_metode = ?";
                    $stmt_pengiriman = $koneksi->prepare($query_pengiriman);
                    $stmt_pengiriman->bind_param("s", $nama_pengiriman);
                    $stmt_pengiriman->execute();
                    $result_pengiriman = $stmt_pengiriman->get_result();
                    
                    if ($result_pengiriman->num_rows === 0) {
                        throw new Exception('Metode pengiriman tidak valid');
                    }
                    $metode_pengiriman = $result_pengiriman->fetch_assoc()['id_metodePengiriman'];
                    $stmt_pengiriman->close();
                    
                    // 3. Simpan ke tabel pesanan
                    $query = "INSERT INTO pesanan (
                        id_users, 
                        id_alamat, 
                        total_belanja, 
                        tanggal_pesanan, 
                        metode_pembayaran, 
                        metode_pengiriman, 
                        status, 
                        bukti
                    ) VALUES (?, ?, ?, NOW(), ?, ?, 'Menunggu Konfirmasi', ?)";
                    
                    $stmt = $koneksi->prepare($query);
                    $stmt->bind_param(
                        "iidsss", 
                        $id_user,
                        $id_alamat,
                        $total_belanja,
                        $metode_pembayaran,
                        $metode_pengiriman,
                        $upload_path
                    );
                    
                    if (!$stmt->execute()) {
                        throw new Exception('Gagal menyimpan data pesanan: ' . $koneksi->error);
                    }
                    
                    $id_pesanan = $koneksi->insert_id;
                    
                    // 4. Simpan ke tabel detail_pesanan untuk setiap item
                    foreach ($checkout_data['items'] as $item) {
                        if (!isset($item['id_buku']) || !isset($item['quantity']) || !isset($item['harga'])) {
                            throw new Exception('Data item tidak valid');
                        }
                        
                        $query_detail = "INSERT INTO detail_pesanan (
                            id_pesanan, 
                            id_buku, 
                            jumlah, 
                            harga
                        ) VALUES (?, ?, ?, ?)";
                        
                        $stmt_detail = $koneksi->prepare($query_detail);
                        $stmt_detail->bind_param(
                            "iiid", 
                            $id_pesanan,
                            $item['id_buku'],
                            $item['quantity'],
                            $item['harga']
                        );
                        
                        if (!$stmt_detail->execute()) {
                            throw new Exception('Gagal menyimpan detail pesanan: ' . $koneksi->error);
                        }
                        
                        $stmt_detail->close();
                    }
                    
                    // Commit transaksi
                    $koneksi->commit();
                    
                    // Kosongkan session checkout
                    unset($_SESSION['checkout_data']);
                    unset($_SESSION['metode_pembayaran']);
                    
                    // Redirect ke halaman sukses
                    header("Location: rincian.php?id=" . $id_pesanan);
                    exit();
                    
                } catch (Exception $e) {
                    // Rollback transaksi jika ada error
                    $koneksi->rollback();
                    $error = $e->getMessage();
                    
                    // Hapus file yang sudah diupload jika ada error
                    if (file_exists($upload_path)) {
                        unlink($upload_path);
                    }
                }
            } else {
                $error = 'Gagal mengupload bukti pembayaran';
            }
        }
    } else {
        $error = 'Harap upload bukti pembayaran';
    }
}

// Ambil data metode pembayaran yang dipilih dari session
$pesanan = [
    'metode_pembayaran' => $_SESSION['metode_pembayaran'],
    'total_belanja' => $_SESSION['checkout_data']['total_harga'] + $_SESSION['checkout_data']['ongkir']['biaya']
];

$id_pesanan = isset($_GET['id']) ? $_GET['id'] : 0;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukabuku - Pembayaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.2.0/remixicon.css">
    <style>
        :root {
            --purple: #6e3482;
            --light-purple: #f3e9f7;
            --dark-purple: #5a2a6e;
            --gray: #f5f5f5;
            --dark-gray: #666;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
            outline: none;
            border: none;
            text-decoration: none;
            transition: 0.2s linear;
        }

        html {
            font-size: 62.5%;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body {
            min-height: 100vh;
            background-color: #fff;
            padding-top: 170px;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            height: 130px;
            padding: 0 5px;
            z-index: 1000;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .mini-top-header {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
            padding: 6px 10px;
            background-color: #d9d9d9;
        }

        .mini-top-header i,
        .mini-top-header p {
            font-size: 1.4rem;
            color: #333;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 90px;
            padding: 1rem 0;
        }

        .logo-wrapper img {
            margin-left: 10px;
            width: 120px;
            height: auto;
        }

        .navbar-left,
        .navbar-right {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            color: #000;
            gap: 10px;
            cursor: pointer;
        }

        /* Dropdown Kategori */
        .category-dropdown {
            position: relative;
            cursor: pointer;
        }

        .category-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            width: 200px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
            padding: 10px 0;
            display: none;
            z-index: 1000;
        }

        .category-menu a {
            display: block;
            padding: 8px 20px;
            color: #333;
            font-size: 1.4rem;
            text-decoration: none;
        }

        .category-menu a:hover {
            background-color: #f5f5f5;
            color: var(--purple);
        }

        .category-dropdown:hover .category-menu {
            display: block;
        }

        .category-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .category-menu {
            width: 250px;
        }

        /* Untuk submenu */
        .category-menu .submenu {
            display: none;
            padding-left: 15px;
        }
        .category-menu a.has-submenu::after {
            content: "›";
            float: right;
        }

        .navbar-middle {
            background-color: white;
            border: 1px solid #333;
            border-radius: 25px;
            padding: 7px 15px;
            display: flex;
            align-items: center;
            width: 400px;
        }

        .navbar-middle input {
            flex-grow: 1;
            background: transparent;
            border: none;
            color: black;
            padding: 5px;
        }

        .navbar-middle i {
            color: black;
            font-size: 18px;
            margin-left: 10px;
        }

        .navbar-right {
            gap: 130px;
        }

        .navbar-right a {
            color: #000;
        }

        .navbar-right a:hover {
            color: var(--purple);
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-icon {
            width: 32px;
            height: 32px;
            background-color: #d1d5db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 30px;
        }

        .profile-dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            width: 200px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 5px;
            padding: 15px;
            display: none;
            z-index: 1000;
            margin-top: 10px;
        }

        .profile-dropdown:hover .profile-dropdown-menu {
            display: block;
        }

        .profile-info {
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
        }

        .profile-name {
            font-size: 1.4rem;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .profile-email {
            font-size: 1.2rem;
            color: #666;
        }

        .profile-menu {
            list-style: none;
        }

        .profile-menu li {
            padding: 8px 0;
        }

        .profile-menu li a {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #333;
            font-size: 1.4rem;
        }

        .profile-menu li a i {
            font-size: 1.6rem;
            color: #666;
        }

        .profile-menu li a:hover {
            color: var(--purple);
        }

        .profile-menu li a:hover i {
            color: var(--purple);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Payment Section Styles */
        .payment-section {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .payment-card {
            flex: 1;
            min-width: 300px;
            background: white;
            border-radius: 10px;
            padding: 25px;
            /* box-shadow: 0 4px 12px rgba(0,0,0,0.1); */
        }
        
        .payment-card h2 {
            color: var(--purple);
            margin-bottom: 15px;
            font-size: 1.8rem;
            padding-bottom: 10px;
            text-align: center;
            position: relative;
        }

        .payment-card h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 2px;
            background-color: var(--purple);
        }
        
        .timer-wrapper {
            display: flex;
            justify-content: center; 
            margin: 15px 0;
        }

        .timer {
            font-size: 2.4rem;
            font-weight: bold;
            color: #fff;
            width: 200px;
            background-color: #e63946;
            padding: 10px;
            border-radius: 20px;
            text-align: center;
            margin: 15px 0;
            font-family: monospace;
            box-shadow: 0 4px 8px rgba(230, 57, 70, 0.2);
        }
        
        .deadline {
            font-size: 1.4rem;
            margin-bottom: 20px;
            line-height: 1.6;
            text-align: center;
            color: var(--dark-gray);
        }
        
        .deadline strong {
            font-weight: 600;
            color: #333;
        }
        
        .payment-method-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 20px;
        }
        
        .payment-method-item {
            width: 100%;
        }
        
        .payment-method-title {
            font-size: 1.4rem;
            color: var(--dark-gray);
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .qris-method {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            border-radius: 8px;
            width: 100%;
        }
        
        .qris-method img {
            height: 30px;
        }
        
        .qris-method span {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--purple);
        }
        
        .file-upload-container {
            width: 100%;
        }
        
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 60%;
            height: 90px;
            border: 1px dashed #ccc;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #fafafa;
            padding: 15px;
        }
        
        .file-upload-label:hover {
            border-color: var(--purple);
            background-color: var(--light-purple);
        }
        
        .file-upload-icon {
            font-size: 3rem;
            color: var(--purple);
            margin-bottom: 10px;
        }
        
        .file-upload-text {
            font-size: 1.4rem;
            color: var(--dark-gray);
            text-align: center;
        }
        
        .file-upload-text span {
            color: var(--purple);
            font-weight: bold;
        }
        
        .file-upload-input {
            display: none;
        }
        
        .file-name {
            font-size: 1.3rem;
            color: var(--dark-gray);
            margin-top: 8px;
            display: none;
            padding: 5px 10px;
            background-color: white;
            border-radius: 4px;
            max-width: 100%;
            word-break: break-all;
        }
        
        .total-payment-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        
        .total-payment {
            font-size: 2.4rem;
            font-weight: bold;
            color: var(--purple);
            padding: 12px;
            border-radius: 8px;
        }
        
        .payment-buttons {
            display: flex;
            gap: 15px;
            margin: 25px 0;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 1.4rem;
            flex: 1;
            min-width: 200px;
            text-align: center;
        }
        
        .btn-primary {
            background-color: var(--purple);
            color: white;
        }
        
        .btn-secondary {
            background-color: #f1f1f1;
            color: #333;
            border: 1px solid #ddd;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-purple);
        }
        
        .btn-secondary:hover {
            background-color: #e1e1e1;
        }

        /* Custom file input styling to match the image */
        .custom-file-input {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
            gap: 10px;
        }
        
        .custom-file-input label {
            font-size: 1.4rem;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }
        
        .file-input-wrapper {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 300px;
            background: white;
            border-radius: 10px;
            padding: 10px;
            /* box-shadow: 0 4px 12px rgba(0,0,0,0.1); */
            border: 1px solid #ddd;
        }
        
        .file-input-button {
            background-color: #f1f1f1;
            color: #333;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 1.4rem;
            cursor: pointer;
            border: 1px solid #ddd;
        }
        
        .file-input-text {
            margin-left: 10px;
            font-size: 1.3rem;
            color: #666;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            body {
                padding-top: 150px;
            }
            
            .payment-section {
                flex-direction: column;
            }
            
            .payment-card {
                min-width: 100%;
            }
            
            .payment-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .file-upload-label {
                width: 100%;
            }
        }

        /* Footer Styles */
        .footer-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 30px auto 20px;
            padding: 0 20px;
            max-width: 1200px;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
        }

        .footer-brand img {
            width: 70px;
            height: auto;
        }

        .footer-brand p {
            font-size: 1.4rem;
            color: #666;
            max-width: 600px;
            text-align: right;
        }

        .footer {
            background-color: #f8f8f8;
            color: #333;
            padding: 40px 0 20px;
            font-family: 'Montserrat', sans-serif;
            border-top: 1px solid #e0e0e0;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .footer-column h3 {
            font-size: 1.6rem;
            margin-bottom: 15px;
            color: #333;
            font-weight: 600;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer-column ul li {
            margin-bottom: 10px;
            font-size: 1.4rem;
        }

        .footer-column ul li a {
            color: #666;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-column ul li a:hover {
            color: var(--purple);
        }

        .contact ul li {
            color: #666;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-bottom p {
            font-size: 1.3rem;
            color: #666;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icons a {
            color: #666;
            font-size: 1.6rem;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: var(--purple);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="mini-top-header">
            <i class="ri-question-fill"></i>
            <p>Hubungi Kami</p>
        </div>
        <div class="container">
            <nav class="navbar">
                <div class="logo-wrapper">
                    <img src="image/Navy Colorful fun Kids Book Store Logo1.png" alt="Logo Bukabuku" class="logo">
                </div>
                <div class="navbar-left">
                    <div class="category-dropdown">
                        <p class="category-toggle">Kategori <i class="ri-arrow-down-s-line"></i></p>
                        <div class="category-menu">
                            <a href="k_fiksi.html">Buku Fiksi</a>
                            <a href="k_nonfiksi.html">Buku Nonfiksi</a>
                            <a href="k_anak.html">Buku Anak</a>
                            <a href="k_pelajaran.html">Buku Pelajaran</a>
                            <a href="k_agama.html">Buku Agama</a>
                            <a href="k_sejarah.html">Buku Sejarah</a>
                            <a href="k_komik.html">Komik</a>
                        </div>
                    </div>
                </div>
                <div class="navbar-middle">
                    <input type="text" placeholder="Cari Produk, Judul Buku, Penulis">
                    <i class="ri-search-line"></i>
                </div>
                <div class="navbar-right">
                    <a href="#" class="fas fa-shopping-cart"></a>
                    <div class="profile-dropdown">
                        <div class="profile-icon">
                            <i class="ri-user-line"></i>
                        </div>
                        <div class="profile-dropdown-menu">
                            <div class="profile-info">
                                <div class="profile-name">Adelia</div>
                                <div class="profile-email">adeliasa@gmail.com</div>
                            </div>
                            <ul class="profile-menu">
                                <li><a href="#"><i class="ri-user-line"></i> Akun</a></li>
                                <li><a href="#"><i class="ri-shopping-bag-line"></i> Transaksi</a></li>
                                <li><a href="#"><i class="ri-heart-line"></i> Wishlist</a></li>
                                <li><a href="#"><i class="ri-logout-box-line"></i> Keluar Akun</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
        
    <main class="container">
        <div class="payment-section">
            <div class="payment-card">
                <h2>Sisa Waktu Pembayaran</h2>
                <div class="timer-wrapper">
                    <div class="timer" id="countdown">23 : 59 : 59</div>
                </div>
                <p class="deadline">Batas Waktu Pembayaran<br>
                <strong id="payment-deadline"></strong></p>
            </div>
            
            <div class="payment-card">
            <h2>Metode Pembayaran</h2>
            
            <form action="pembayaran.php" method="post" enctype="multipart/form-data" class="payment-method-container">
                <!-- Metode Pembayaran -->
                <div class="payment-method-item">
                        <p class="payment-method-title">Metode Pembayaran</p>
                        <div class="qris-method">
                            <img src="image/<?= htmlspecialchars($pesanan['metode_pembayaran']) ?>.png" alt="<?= htmlspecialchars($pesanan['metode_pembayaran']) ?>">
                            <span><?= htmlspecialchars($pesanan['metode_pembayaran']) ?></span>
                        </div>

                        <p class="payment-method-title">Nomor Rekening</p>
                        <div class="qris-method">
                            <span>12345678909</span>
                        </div>
                    </div>
                    
                    <!-- Bukti Pembayaran -->
                    <div class="payment-method-item">
                        <p class="payment-method-title">Masukan Bukti Pembayaran</p>
                        <div class="custom-file-input">
                            <div class="file-input-wrapper">
                                <input type="file" id="payment-proof" name="payment_proof" class="file-upload-input" accept="image/jpeg,image/png,image/jpg" required>
                                <button type="button" class="file-input-button" onclick="document.getElementById('payment-proof').click()">Pilih File</button>
                                <span class="file-input-text" id="file-chosen">Belum ada file dipilih</span>
                            </div>
                            <label>Format foto harus jpg, jpeg, png dan ukuran file max 2MB.</label>
                            <?php if (!empty($error)): ?>
                                <p style="color: red; font-size: 1.3rem; margin-top: 5px;"><?= htmlspecialchars($error) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                
                <!-- Total Pembayaran -->
                <div class="payment-method-item">
                        <p class="payment-method-title">Total Pembayaran</p>
                        <div class="total-payment-container">
                            <div class="total-payment">Rp<?= number_format($pesanan['total_belanja'], 0, ',', '.') ?></div>
                        </div>
                </div>
                
                <!-- Tombol-tombol -->
                <div class="payment-buttons">
                        <button type="submit" class="btn btn-primary" name="confirm-payment" id="confirm-payment-btn">Konfirmasi Pembayaran</button>
                        <a href="rincian.php?id=<?= $id_pesanan ?>"><button type="button" class="btn btn-secondary" id="check-status">Cek Status Pembayaran</button></a>
                </div>
            </form>
</div>
        </div>
    </main>

    <!-- Footer -->
    <div class="footer-brand">
        <img src="image/Navy Colorful fun Kids Book Store Logo1.png" alt="Bukabuku Logo">
        <p>Toko buku online terbesar, terlengkap dan terpercaya di Indonesia</p>
    </div>  
    
    <footer class="footer">
        <div class="footer-container">
            <!-- Grid Footer -->
            <div class="footer-grid">
                <!-- Kolom 1 -->
                <div class="footer-column">
                    <h3>Produk Bukabuku</h3>
                    <ul>
                        <li><a href="#">Buku Baru</a></li>
                        <li><a href="#">Buku Best Seller</a></li>
                    </ul>
                </div>
                
                <!-- Kolom 2 -->
                <div class="footer-column">
                    <h3>Lainnya</h3>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
                
                <!-- Kolom Kontak -->
                <div class="footer-column contact">
                    <h3>Hubungi Kami</h3>
                    <ul>
                        <li>Email: info@bukabuku.com</li>
                        <li>Telepon: (021) 12345678</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; 2025 Bukabuku.com. Semua Hak Dilindungi.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        // Countdown Timer 24 jam
        function updateCountdown() {
            const countdownElement = document.getElementById('countdown');
            let time = 24 * 60 * 60; // 24 jam dalam detik
            
            const countdown = setInterval(() => {
                const hours = Math.floor(time / 3600);
                const minutes = Math.floor((time % 3600) / 60);
                const seconds = time % 60;
                
                countdownElement.textContent = `${hours.toString().padStart(2, '0')} : ${minutes.toString().padStart(2, '0')} : ${seconds.toString().padStart(2, '0')}`;
                
                if (time <= 0) {
                    clearInterval(countdown);
                    countdownElement.textContent = "Waktu Habis";
                    countdownElement.style.backgroundColor = "#999";
                    // Disable tombol konfirmasi jika waktu habis
                    document.getElementById('confirm-payment-btn').disabled = true;
                } else {
                    time--;
                }
            }, 1000);
        }
        
        // File Upload Display
        const fileInput = document.getElementById('payment-proof');
        const fileChosen = document.getElementById('file-chosen');
        
        fileInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                fileChosen.textContent = this.files[0].name;
            } else {
                fileChosen.textContent = "Belum ada file dipilih";
            }
        });
        
        // Validasi form sebelum submit
        document.getElementById('confirm-payment-btn').addEventListener('click', function(e) {
            const fileInput = document.getElementById('payment-proof');
            
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('Harap upload bukti pembayaran terlebih dahulu');
                return false;
            }
            
            // Validasi ekstensi file
            const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            if (!allowedExtensions.exec(fileInput.value)) {
                e.preventDefault();
                alert('Format file harus JPG, JPEG, atau PNG');
                return false;
            }
            
            // Validasi ukuran file (2MB)
            if (fileInput.files[0].size > 2097152) {
                e.preventDefault();
                alert('Ukuran file maksimal 2MB');
                return false;
            }
            
            return true;
        });

        // Set payment deadline (current time + 24 jam)
        const now = new Date();
        now.setHours(now.getHours() + 24);
        
        const options = { 
            weekday: 'long', 
            day: '2-digit', 
            month: 'long', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            timeZone: 'Asia/Jakarta',
            hour12: false
        };
        
        const formatter = new Intl.DateTimeFormat('id-ID', options);
        const formattedDate = formatter.format(now);
        
        document.getElementById('payment-deadline').textContent = formattedDate + ' WIB';

        // Jalankan countdown saat halaman dimuat
        window.onload = function() {
            updateCountdown();
        };
    </script>
</body>
</html>