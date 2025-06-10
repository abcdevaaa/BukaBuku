<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.2.0/remixicon.css">
    <title>Bukabuku - Transaksi</title>
    <style>
        :root {
            --purple: #6e3482;
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
            padding-top: 150px;
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
        
        .breadcrumb {
            padding: 15px;
            font-size: 0.8rem;
            color: #666;
        }
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .sidebar {
            flex: 0 0 250px;
            background-color: white;
            border-radius: 5px;
            padding: 20px;
        }
        
        .sidebar-section {
            margin-bottom: 30px;
        }
        
        .sidebar-title {
            font-size: 1.1rem;
            color: var(--purple);
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        
        .sidebar-menu a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 8px 0;
        }
        
        .sidebar-menu a:hover {
            color: var(--purple);
            font-weight: bold;
        }
        
        .sidebar-menu a.active {
            color: var(--purple);
            font-weight: bold;
        }
        
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .sidebar {
            flex: 0 0 250px;
            background-color: white;
            border-radius: 5px;
            padding: 20px;
        }
        
        .sidebar-section {
            margin-bottom: 30px;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        
        
        
        .sidebar-menu a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 8px 0;
            font-size: 1.1rem;

        }
        
        .sidebar-menu a:hover {
            font-size: 1.1rem;
            color: var(--purple);
            color: var(--purple);
            font-weight: bold;
        }
        
        .sidebar-menu a.active {
            color: var(--purple);
            font-weight: bold;
        }
        
        .content {
            flex: 1;
            min-width: 300px;
            background-color: white;
            border-radius: 5px;
            padding: 20px;
        }
        
        .section-title {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        
        /* Transaction Styles */
        .transaction-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1.4rem;
        }
        
        .filter-dropdown {
            position: relative;
            min-width: 180px;
        }
        
        .filter-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px 15px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1.4rem;
            cursor: pointer;
            text-align: left;
        }
        
        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            z-index: 10;
            display: none;
        }
        
        .dropdown-options.show {
            display: block;
        }
        
        .dropdown-options a {
            display: block;
            padding: 10px 15px;
            color: #333;
            font-size: 1.4rem;
            text-decoration: none;
        }
        
        .dropdown-options a:hover {
            background: #f5f5f5;
            color: var(--purple);
        }
        
        /* Transaction Card Styles */
        .transaction-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .transaction-meta {
            display: flex;
            align-items: center;
            font-size: 1.3rem;
            color: #666;
        }

        .transaction-date {
            margin-right: 10px;
        }

        .transaction-id {
            margin-right: 10px;
        }

        .copy-btn {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 1.4rem;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 500;
        }

        .waiting {
            background-color: #FFF3CD;
            color: #856404;
        }

        .processing {
            background-color: #D1ECF1;
            color: #0C5460;
        }

        .shipped {
            background-color: #CCE5FF;
            color: #004085;
        }

        .completed {
            background-color: #D4EDDA;
            color: #155724;
        }

        .cancelled {
            background-color: #F8D7DA;
            color: #721C24;
        }

        .returned {
            background-color: #E2E3E5;
            color: #383D41;
        }

        .divider {
            height: 1px;
            background-color: #eee;
            margin: 10px 0;
        }

        .transaction-product {
            display: flex;
            align-items: center;
        }

        .product-image {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .product-quantity {
            font-size: 1.3rem;
            color: #666;
        }

        .transaction-price {
            text-align: right;
        }

        .price-label {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 5px;
        }

        .total-price {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--purple);
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

        /* Notification Styles */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            animation: fadeIn 0.3s, fadeOut 0.3s 2s forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(20px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 150px;
            }
            
            .navbar {
                flex-direction: column;
                height: auto;
                padding: 10px 0;
            }
            
            .navbar-middle {
                width: 100%;
                margin: 10px 0;
            }
            
            .main-content {
                flex-direction: column;
            }
            
            .sidebar {
                flex: 0 0 auto;
                width: 100%;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-brand {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-brand p {
                text-align: center;
                margin-top: 10px;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 15px;
            }
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
                    <img src="image/Navy_Colorful_fun_Kids_Book_Store_Logo.png" alt="Logo Bukabuku" class="logo">
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
                                <li><a href="#" class="active"><i class="ri-shopping-bag-line"></i> Transaksi</a></li>
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
    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Akun Saya</h3>
                    <ul class="sidebar-menu">
                        <li><a href="#">Pengaturan Profil</a></li>
                        <li><a href="#">Wishlist</a></li>
                        <li><a href="#" class="active">Transaksi</a></li>
                        <li><a href="#">Alamat</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="content">
                <h1 class="section-title">Transaksi</h1>
                
                <div class="transaction-filters">
                    <input type="text" class="search-input" placeholder="Cari nama produk atau no. pemesanan">
                    
                    <div class="filter-dropdown">
                        <button class="filter-button">
                            Status Transaksi
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                        <div class="dropdown-options">
                            <a href="#" data-status="all">Semua Status</a>
                            <a href="#" data-status="waiting">Menunggu Pembayaran</a>
                            <a href="#" data-status="processing">Pesanan Diproses</a>
                            <a href="#" data-status="shipped">Pesanan Dikirim</a>
                            <a href="#" data-status="completed">Pesanan Diterima</a>
                            <a href="#" data-status="cancelled">Pesanan Dibatalkan</a>
                            <a href="#" data-status="returned">Pesanan Dikembalikan</a>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction 1 - Menunggu Pembayaran -->
                <div class="transaction-card" data-status="waiting">
                    <div class="transaction-header">
                        <div class="transaction-meta">
                            <span class="transaction-date">05 April 2025, 10:30:15</span>
                            <span>|</span>
                            <span class="transaction-id">ID045ABXDSRT99</span>
                            <button class="copy-btn" onclick="copyTransactionId('ID045ABXDSRT99')">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <span class="status-badge waiting">Menunggu Pembayaran</span>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="transaction-product">
                        <img src="image/harry_potter.jpg" alt="Harry Potter" class="product-image">
                        <div class="product-info">
                            <h3 class="product-name">Harry Potter and the Philosopher's Stone</h3>
                            <p class="product-quantity">2 Barang</p>
                        </div>
                        <div class="transaction-price">
                            <p class="price-label">Total Pembayaran</p>
                            <p class="total-price">Rp210.500</p>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction 2 - Diproses -->
                <div class="transaction-card" data-status="processing">
                    <div class="transaction-header">
                        <div class="transaction-meta">
                            <span class="transaction-date">04 April 2025, 14:22:10</span>
                            <span>|</span>
                            <span class="transaction-id">ID044CDXDSRT88</span>
                            <button class="copy-btn" onclick="copyTransactionId('ID044CDXDSRT88')">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <span class="status-badge processing">Pesanan Diproses</span>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="transaction-product">
                        <img src="image/atomic_habits.jpg" alt="Atomic Habits" class="product-image">
                        <div class="product-info">
                            <h3 class="product-name">Atomic Habits</h3>
                            <p class="product-quantity">1 Barang</p>
                        </div>
                        <div class="transaction-price">
                            <p class="price-label">Total Pembayaran</p>
                            <p class="total-price">Rp150.000</p>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction 3 - Dikirim -->
                <div class="transaction-card" data-status="shipped">
                    <div class="transaction-header">
                        <div class="transaction-meta">
                            <span class="transaction-date">03 April 2025, 09:15:45</span>
                            <span>|</span>
                            <span class="transaction-id">ID043EFXDSRT77</span>
                            <button class="copy-btn" onclick="copyTransactionId('ID043EFXDSRT77')">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <span class="status-badge shipped">Pesanan Dikirim</span>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="transaction-product">
                        <img src="image/deep_work.jpg" alt="Deep Work" class="product-image">
                        <div class="product-info">
                            <h3 class="product-name">Deep Work</h3>
                            <p class="product-quantity">1 Barang</p>
                        </div>
                        <div class="transaction-price">
                            <p class="price-label">Total Pembayaran</p>
                            <p class="total-price">Rp175.000</p>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction 4 - Diterima -->
                <div class="transaction-card" data-status="completed">
                    <div class="transaction-header">
                        <div class="transaction-meta">
                            <span class="transaction-date">02 April 2025, 16:40:30</span>
                            <span>|</span>
                            <span class="transaction-id">ID042GHXDSRT66</span>
                            <button class="copy-btn" onclick="copyTransactionId('ID042GHXDSRT66')">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <span class="status-badge completed">Pesanan Diterima</span>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="transaction-product">
                        <img src="image/sapiens.jpg" alt="Sapiens" class="product-image">
                        <div class="product-info">
                            <h3 class="product-name">Sapiens: A Brief History of Humankind</h3>
                            <p class="product-quantity">1 Barang</p>
                        </div>
                        <div class="transaction-price">
                            <p class="price-label">Total Pembayaran</p>
                            <p class="total-price">Rp225.000</p>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction 5 - Dibatalkan -->
                <div class="transaction-card" data-status="cancelled">
                    <div class="transaction-header">
                        <div class="transaction-meta">
                            <span class="transaction-date">01 April 2025, 17:18:24</span>
                            <span>|</span>
                            <span class="transaction-id">ID032ZKXDSRT85</span>
                            <button class="copy-btn" onclick="copyTransactionId('ID032ZKXDSRT85')">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <span class="status-badge cancelled">Pesanan Dibatalkan</span>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="transaction-product">
                        <img src="image/laut_bercerita.jpg" alt="Laut Bercerita" class="product-image">
                        <div class="product-info">
                            <h3 class="product-name">Laut Bercerita</h3>
                            <p class="product-quantity">1 Barang</p>
                        </div>
                        <div class="transaction-price">
                            <p class="price-label">Total Pembayaran</p>
                            <p class="total-price">Rp105.250</p>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction 6 - Dikembalikan -->
                <div class="transaction-card" data-status="returned">
                    <div class="transaction-header">
                        <div class="transaction-meta">
                            <span class="transaction-date">31 Maret 2025, 11:05:12</span>
                            <span>|</span>
                            <span class="transaction-id">ID031IJXDSRT74</span>
                            <button class="copy-btn" onclick="copyTransactionId('ID031IJXDSRT74')">
                                <i class="ri-file-copy-line"></i>
                            </button>
                        </div>
                        <span class="status-badge returned">Pesanan Dikembalikan</span>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="transaction-product">
                        <img src="image/thinking_fast_and_slow.jpg" alt="Thinking Fast and Slow" class="product-image">
                        <div class="product-info">
                            <h3 class="product-name">Thinking, Fast and Slow</h3>
                            <p class="product-quantity">1 Barang</p>
                        </div>
                        <div class="transaction-price">
                            <p class="price-label">Total Pembayaran</p>
                            <p class="total-price">Rp195.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer-brand">
        <img src="image/Navy_Colorful_fun_Kids_Book_Store_Logo.png" alt="Bukabuku Logo">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Copy Transaction ID Function
            window.copyTransactionId = function(id) {
                navigator.clipboard.writeText(id).then(() => {
                    // Create a notification element
                    const notification = document.createElement('div');
                    notification.className = 'notification';
                    notification.textContent = 'ID Transaksi berhasil disalin!';
                    document.body.appendChild(notification);
                    
                    // Remove notification after animation
                    setTimeout(() => {
                        notification.remove();
                    }, 2500);
                }).catch(err => {
                    console.error('Gagal menyalin: ', err);
                    alert('Gagal menyalin ID transaksi');
                });
            };

            // Status Filter Dropdown
            const filterButtons = document.querySelectorAll('.filter-button');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const dropdown = this.nextElementSibling;
                    dropdown.classList.toggle('show');
                });
            });
            
            // Close dropdown when clicking an option
            document.querySelectorAll('.dropdown-options a').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdown = this.closest('.dropdown-options');
                    const button = dropdown.previousElementSibling;
                    const status = this.getAttribute('data-status');
                    
                    button.innerHTML = this.textContent + ' <i class="ri-arrow-down-s-line"></i>';
                    dropdown.classList.remove('show');
                    
                    // Filter transactions based on status
                    filterTransactions(status);
                });
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.matches('.filter-button')) {
                    document.querySelectorAll('.dropdown-options').forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                }
            });
            
            // Search Functionality
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const currentStatus = document.querySelector('.filter-button').getAttribute('data-current-status') || 'all';
                    filterTransactions(currentStatus, searchTerm);
                });
            }
            
            // Filter Transactions Function
            function filterTransactions(status = 'all', searchTerm = '') {
                const transactionCards = document.querySelectorAll('.transaction-card');
                let hasVisibleCards = false;
                
                transactionCards.forEach(card => {
                    const cardStatus = card.getAttribute('data-status');
                    const cardText = card.textContent.toLowerCase();
                    
                    const statusMatch = status === 'all' || cardStatus === status;
                    const searchMatch = searchTerm === '' || cardText.includes(searchTerm);
                    
                    if (statusMatch && searchMatch) {
                        card.style.display = 'block';
                        hasVisibleCards = true;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Update current status in filter button
                const filterButton = document.querySelector('.filter-button');
                if (filterButton) {
                    filterButton.setAttribute('data-current-status', status);
                }
                
                // Show message if no transactions found
                const noResultsMessage = document.getElementById('no-results-message');
                if (!hasVisibleCards) {
                    if (!noResultsMessage) {
                        const message = document.createElement('p');
                        message.id = 'no-results-message';
                        message.textContent = 'Tidak ada transaksi yang sesuai dengan kriteria pencarian';
                        message.style.textAlign = 'center';
                        message.style.padding = '20px';
                        message.style.fontSize = '1.4rem';
                        message.style.color = '#666';
                        
                        const content = document.querySelector('.content');
                        if (content) {
                            content.appendChild(message);
                        }
                    }
                } else if (noResultsMessage) {
                    noResultsMessage.remove();
                }
            }
            
            // Initialize with all transactions visible
            filterTransactions();
        });
    </script>
</body>
</html>