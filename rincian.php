<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: LoginRegister.php");
    exit();
}

$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$user = mysqli_fetch_assoc($query);

$queryKategori2 = mysqli_query($koneksi, "SELECT * FROM kategori");

// Ambil ID pesanan dari parameter URL
if (!isset($_GET['id_pesanan'])) {
    die("ID Pesanan tidak ditemukan");
}
$id_pesanan = (int)$_GET['id_pesanan'];

// Query untuk mendapatkan data pesanan
$query_pesanan = "SELECT p.*, a.nama_penerima, a.no_telepon, a.alamat_lengkap, a.kabupaten, a.provinsi, a.kode_pos,
                  mp.nama_metode as metode_pembayaran_nama, mk.nama_metode as metode_pengiriman_nama, mk.biaya as biaya_pengiriman
                  FROM pesanan p
                  JOIN alamat a ON p.id_alamat = a.id_alamat
                  JOIN metode_pembayaran mp ON p.metode_pembayaran = mp.id_metodePembayaran
                  JOIN metode_pengiriman mk ON p.metode_pengiriman = mk.id_metodePengiriman
                  WHERE p.id_pesanan = $id_pesanan AND p.id_users = {$_SESSION['id_users']}";
$result_pesanan = mysqli_query($koneksi, $query_pesanan);
$pesanan = mysqli_fetch_assoc($result_pesanan);

if (!$pesanan) {
    die("Pesanan tidak ditemukan");
}

// Query untuk mendapatkan detail pesanan
$query_detail = "SELECT dp.*, b.judul, b.gambar, b.harga
                 FROM detailpesanan dp
                 JOIN buku b ON dp.id_buku = b.id_buku
                 WHERE dp.id_pesanan = $id_pesanan";
$result_detail = mysqli_query($koneksi, $query_detail);
$detail_pesanan = mysqli_fetch_all($result_detail, MYSQLI_ASSOC);

// Hitung total harga produk
$total_harga_produk = 0;
foreach ($detail_pesanan as $item) {
    $total_harga_produk += $item['harga'] * $item['jumlah'];
}

// Tentukan class status
$statusClass = '';
switch(strtolower($pesanan['status'])) {
    case 'menunggu pembayaran':
        $statusClass = 'status-menunggu';
        break;
    case 'pesanan diproses':
        $statusClass = 'status-diproses';
        break;
    case 'pesanan dikirim':
        $statusClass = 'status-dikirim';
        break;
    case 'pesanan diterima':
        $statusClass = 'status-diterima';
        break;
    default:
        $statusClass = 'status-diproses';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Transaksi</title>
    <style>
        :root {
            --purple: #6e3482;
            --primary: #6e3482;
            --secondary: #f8f8f8;
            --text: #333;
            --light-text: #666
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

        /* Main Layout */
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        
        .address {
            margin-bottom: 15px;
        }
        
        .address-name {
            /* font-weight: bold; */
            font-size: 13px; /* nama */
            margin-bottom: 5px;
            color: #666;
        }
        
        .address-details {
            color: #666;
            line-height: 1.5;
            font-size: 12px; /* alamat  */
        }
        
        /* Status Styles */
        .status {
            display: inline-block;
            /* color: white; */
            padding: 5px 10px;
            margin-bottom: 10px;
            border-radius: 12px;
            font-size: 1.4rem; /* status */
            font-weight: 500;
        }
        
        .status-menunggu {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .status-diproses {
            background-color: #D1ECF1;
            color: #0C5460;
        }
        
        .status-dikirim {
            background-color: #CCE5FF;
            color: #004085;
        }
        
        .status-diterima {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        /* Progress Bar Styles */
        .status-progress {
            margin-bottom: 20px;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
        }
        
        .progress-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .step.active .step-icon {
            background:c
            color: white;
        }
        
        .step-label {
            font-size: 12px;
            color: #666;
            text-align: center;
            max-width: 100px;
        }
        
        .step.active .step-label {
            color: var(--purple);
            font-weight: bold;
        }

        /* Produk */
        .product {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eee;
        }
        
        .product:last-child {
            border-bottom: none;
        }
        
        .product-info {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding: 10px;
        }
        .product-image {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #eee; /tepi foto/
        }

        .product-image img {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }

        .product-details {
            flex-grow: 1;
        }
        
        .product-title {
            /* font-weight: bold; */
            margin-bottom: 5px;
            font-size: 1.6rem; /* judul */
        }
        
        .product-variant {
            /* color: #666; */
            font-size: 13px;
            margin-bottom: 5px;
        }
        
        .product-price {
            font-weight: bold;
            font-size: 1.3rem; /* harga */
            color: var(--purple);
        }
        
        .summary-table {
            width: 100%;
            /* border-collapse: collapse; */
            margin-top: 15px;
        }
        
        .summary-table td {
            padding: 8px 0;
            font-size: 1.2rem; /* rincian */
            border-bottom: 1px solid #eee;
        }
        
        .summary-table .total {
            font-weight: bold;
            font-size: 16px;
        }
        
        .payment-method {
            margin-top: 15px;
            padding: 10px;
            font-size: 1.3rem; /* Metode */
        }
        
        .payment-note {
            margin-top: 15px;
            padding: 10px;
            background-color: #fff8e1;
            /* border-radius: 4px; */
            font-size: 14px;
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
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="mini-top-header">
            <i class="ri-question-fill"></i>
            <p>Hubungi Kami</p>
        </div>
        <div class="container-header">
            <nav class="navbar">
                <a href="index.php">
                <div class="logo-wrapper">
                    <img src="image/Navy Colorful fun Kids Book Store Logo.png" alt="Logo Bukabuku" class="logo">
                </div>
                </a>
                <div class="navbar-left">
                    <div class="category-dropdown">
                        <p class="category-toggle">Kategori <i class="ri-arrow-down-s-line"></i></p>
                        <div class="category-menu">
                            <?php while($kategori = mysqli_fetch_assoc($queryKategori2)) { ?>
                                <a href="kategori.php?id=<?= $kategori['id_kategori'] ?>">
                                        <?= $kategori['nama_kategori'] ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="navbar-middle">
                    <input type="text" placeholder="Cari Produk, Judul Buku, Penulis">
                    <i class="ri-search-line"></i>
                </div>
                
                <div class="navbar-right">
                    <a href="keranjang.php" class="fas fa-shopping-cart"></a>
                    <div class="profile-dropdown">
                        <div class="profile-icon">
                            <i class="ri-user-line"></i>
                        </div>
                        <div class="profile-dropdown-menu">
                            <div class="profile-info">
                            <div class="profile-name"><?= $_SESSION['username'] ?></div>
                            <div class="profile-email"><?= $_SESSION['email'] ?></div>
                        </div>
                            <ul class="profile-menu">
                                <li><a href="akunU.php"><i class="ri-user-line"></i> Akun</a></li>
                                <li><a href="transaksiU.php"><i class="ri-shopping-bag-line"></i> Transaksi</a></li>
                                <li><a href="wishlist.php"><i class="ri-heart-line"></i> Wishlist</a></li>
                                <li><a href="logout.php"><i class="ri-logout-box-line"></i> Keluar Akun</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Header -->
        <div class="header-section">
            <h1>Rincian Transaksi</h1>
        </div>
        
        <!-- Alamat Pengiriman -->
        <div class="section">
            <h2 class="section-title">Alamat Pengiriman</h2>
            <div class="address">
                <div class="address-name"><?= $pesanan['nama_penerima'] ?></div>
                <div class="address-details">
                    <?= $pesanan['no_telepon'] ?><br>
                    <?= $pesanan['alamat_lengkap'] ?>, <?= $pesanan['kabupaten'] ?><br>
                    <?= $pesanan['provinsi'] ?>, <?= $pesanan['kode_pos'] ?>
                </div>
            </div>
        </div>
        
        <!-- Status Pengiriman -->
        <div class="section">
            <!-- <div class="status-progress">
                <div class="progress-steps">
                    <div class="step <?= strtolower($pesanan['status']) == 'menunggu pembayaran' ? 'active' : '' ?>">
                        <div class="step-icon">1</div>
                        <div class="step-label">Menunggu Pembayaran</div>
                    </div>
                    <div class="step <?= strtolower($pesanan['status']) == 'pesanan diproses' ? 'active' : '' ?>">
                        <div class="step-icon">2</div>
                        <div class="step-label">Pesanan Diproses</div>
                    </div>
                    <div class="step <?= strtolower($pesanan['status']) == 'pesanan dikirim' ? 'active' : '' ?>">
                        <div class="step-icon">3</div>
                        <div class="step-label">Pesanan Dikirim</div>
                    </div>
                    <div class="step <?= strtolower($pesanan['status']) == 'pesanan diterima' ? 'active' : '' ?>">
                        <div class="step-icon">4</div>
                        <div class="step-label">Pesanan Diterima</div>
                    </div>
                </div>
            </div> -->
            
            <span class="status <?= $statusClass ?>"><?= ucfirst($pesanan['status']) ?></span>
            <!-- <p>Pesanan tiba di alamat tujuan. diterima oleh Yang bersangkutan.<br>
            Penerima: <?= $pesanan['nama_penerima'] ?></p> -->
        </div>
        
        <!-- Produk -->
        <div class="section">
            <h2 class="section-title">Produk Dipesan</h2>
            <?php foreach ($detail_pesanan as $item): ?>
            <div class="product">
                <div class="product-info">
                    <img src="image/<?= $item['gambar'] ?>" alt="<?= $item['judul'] ?>" class="product-image">
                    <div class="product-details">
                        <div class="product-title"><?= $item['judul'] ?></div>
                        <div class="product-variant">Jumlah: <?= $item['jumlah'] ?></div>
                        <div class="product-price">Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Rincian Pembayaran -->
        <div class="section">
            <h2 class="section-title">Rincian Pembayaran</h2>
            <table class="summary-table">
                <tr>
                    <td>Subtotal Produk</td>
                    <td>Rp<?= number_format($total_harga_produk, 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td>Subtotal Pengiriman</td>
                    <td>Rp<?= number_format($pesanan['biaya_pengiriman'], 0, ',', '.') ?></td>
                </tr>
                <tr class="total">
                    <td>Total Pesanan</td>
                    <td>Rp<?= number_format($pesanan['total_belanja'], 0, ',', '.') ?></td>
                </tr>
            </table>
            
            <div class="payment-method">
                <strong>Metode Pembayaran</strong><br>
                <?= $pesanan['metode_pembayaran_nama'] ?>
            </div>
        </div>
    </div>
            
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
</body>
</html>