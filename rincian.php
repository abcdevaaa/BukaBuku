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
            width: 250px; /* Lebar dropdown */
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

        /* Main Layout */
        .main-container {
            margin: 30px auto;
            padding: 0 20px;
            display: flex;
            gap: 20px;
        }

        .left-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .right-column {
            width: 450px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: var(--text-dark);
            border-bottom: 1px solid var(--gray-medium);
            padding-bottom: 10px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-group {
            margin-bottom: 15px;
        }

        .info-label {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1.4rem;
        }

        .status {
            display: inline-block;
            background-color: #FFF2E7;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 8px;
            color: #C99E1B;
            font-weight: bold;
        }

        /* Product Item */
        .product-item {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 15px;
            border: 1px solid #CCCACA;
        }

        .product-image {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-details {
            margin-top: 15px;
            flex: 1;
        }

        .store-name {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        .product-name {
            font-size: 1.4rem;
            color: #666;
            margin-bottom: 5px;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Shipping Address */
        .shipping-address {
            margin-top: 15px;
        }

        .shipping-label{
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 5px;
        }

        .shipping-name {
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .shipping-details {
            font-size: 1.4rem;
            color: var(--text-dark);
            line-height: 1.5;
        }

        /* Payment Summary */
        .payment-summary {
            margin-top: 15px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1.4rem;
            color: #555;
        }

        .payment-label {
            color: var(--gray-dark);
        }

        .payment-value {
            font-weight: bold;
        }

        .total-payment {
            border-top: 1px solid var(--gray-medium);
            padding-top: 15px;
            margin-top: 15px;
            font-size: 1.6rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
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
        <div class="container">
            <nav class="navbar">
                <div class="logo-wrapper">
                    <img src="image/Navy Colorful fun Kids Book Store Logo.png" alt="Logo Bukabuku" class="logo">
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

    <!-- Main Content -->
    <div class="main-container">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Info Pesanan -->
            <div class="card">
                <h2 class="section-title">Info Pesanan</h2>
                <div class="info-grid">
                    <div class="info-group">
                        <div class="info-label">Status Transaksi</div>
                        <div class="info-value status">Diproses</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Tanggal Pemesanan</div>
                        <div class="info-value">03 April 2025</div>
                    </div>
                </div>
                <div class="info-grid">
                    <div class="info-group">
                        <div class="info-label">No. Pesanan</div>
                        <div class="info-value">ID00000000001</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">No. Invoice</div>
                        <div class="info-value">ID00000000001</div>
                    </div>
                </div>
            </div>

            <!-- Rincian Produk -->
            <div class="card">
                <h2 class="section-title">Rincian Produk</h2>
                <div class="product-item">
                    <img src="image/toko buku abadi.avif" alt="Product Image" class="product-image">
                    <div class="product-details">
                        <div class="store-name">Toko Buku Abadi</div>
                        <div class="product-name">1 barang</div>
                        <div class="product-price">Rp90.000</div>
                    </div>
                </div>
                <div class="payment-row">
                    <span class="payment-label">Total Harga</span>
                    <span class="payment-value">Rp90.000</span>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Rincian Pengiriman -->
            <div class="card">
                <h2 class="section-title">Rincian Pengiriman</h2>
                <div class="info-group">
                    <div class="info-label">Metode Pengiriman</div>
                    <div class="info-value">JNT – Reguler</div>
                </div>
                <div class="shipping-address">
                    <div class="shipping-label">Metode Pengiriman</div>
                    <div class="shipping-name">Kuromi | +6200000000877</div>
                    <div class="shipping-details">Toko, Purbalingga, Kab. Purbalingga, Jawa Tengah, 53371</div>
                </div>
            </div>

            <!-- Rincian Pembayaran -->
            <div class="card">
                <h2 class="section-title">Rincian Pembayaran</h2>
                <div class="payment-summary">
                    <div class="payment-row">
                        <span class="payment-label">Total Harga (1 Barang)</span>
                        <span class="payment-value">Rp90.000</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Total Biaya Pengiriman</span>
                        <span class="payment-value">Rp10.000</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Metode Pembayaran</span>
                        <span class="payment-value">QRIS</span>
                    </div>
                    <div class="total-payment">
                        <span>Total Pembayaran</span>
                        <span>Rp100.000</span>
                    </div>
                </div>
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