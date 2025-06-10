<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.2.0/remixicon.css">
    <title>Bukabuku</title>
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
            background: white;
            border-radius: 8px;
            padding: 20px;
        }

        .section-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        .address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-add {
            background-color: var(--purple);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.4rem;
            transition: background-color 0.3s;
        }

        .btn-add:hover {
            background-color: #5a2a6e;
        }

        .address-list {
            display: grid;
            gap: 15px;
        }

        .address-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            position: relative;
            transition: box-shadow 0.3s;
        }

        .address-card:hover {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .address-name {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        .address-detail {
            color: #666;
            font-size: 1.3rem;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .address-actions {
            display: flex;
            gap: 10px;
        }

        .address-actions button {
            background: none;
            border: none;
            color: var(--purple);
            font-size: 1.3rem;
            cursor: pointer;
            text-decoration: underline;
            padding: 5px 0;
        }

        .address-actions button:hover {
            color: #5a2a6e;
        }

        .default-badge {
            background-color: #e6f7ee;
            color: #00a65a;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 1.2rem;
            margin-left: 10px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 1.4rem;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1.4rem;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.4rem;
        }

        .btn-primary {
            background-color: var(--purple);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #5a2a6e;
        }

        .btn-secondary {
            background-color: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
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

        /* Responsive Styles */
        @media (max-width: 992px) {
            .navbar-right {
                gap: 15px;
            }
            
            .main-content {
                flex-direction: column;
            }
            
            .sidebar {
                flex: 0 0 100%;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 180px;
            }
            
            .navbar {
                flex-direction: row;
                padding: 10px 0;
            }
            
            .navbar-middle {
                order: 3;
                margin: 10px 0;
                max-width: 100%;
            }
            
            .navbar-left {
                margin-left: auto;
            }
            
            .address-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .footer-brand {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-brand p {
                text-align: center;
                margin-top: 15px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding-top: 200px;
            }
            
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .navbar-left,
            .navbar-right {
                width: 100%;
                justify-content: space-between;
                padding: 10px 0;
            }
            
            .logo-wrapper {
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
            
            .logo-wrapper img {
                margin: 0 auto;
            }
            
            .address-actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 15px;
                text-align: center;
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

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Akun Saya</h3>
                    <ul class="sidebar-menu">
                        <li><a href="#">Akun</a></li>
                        <li><a href="#">Wishlist</a></li>
                        <li><a href="#">Transaksi</a></li>
                        <li><a href="#" class="active">Alamat</a></li>
                    </ul>
                </div>
            </div>

            <div class="content">
                <div class="address-header">
                    <h1 class="section-title">Daftar Alamat</h1>
                    <button class="btn-add" id="addAddressBtn">+ Tambah Alamat Baru</button>
                </div>
                
                <div class="address-list" id="addressList">
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Address Modal -->
    <div id="addressModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Tambah Alamat Baru</h2>
            <form id="addressForm">
                <input type="hidden" id="addressId">
                <div class="form-group">
                    <label for="addressName">Nama Alamat</label>
                    <input type="text" id="addressName" required>
                </div>
                <div class="form-group">
                    <label for="recipientName">Nama Penerima</label>
                    <input type="text" id="recipientName" required>
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Nomor Telepon</label>
                    <input type="tel" id="phoneNumber" required>
                </div>
                <div class="form-group">
                    <label for="province">Provinsi</label>
                    <select id="province" required>
                        <option value="">Pilih Provinsi</option>
                        <option value="Jawa Barat">Jawa Barat</option>
                        <option value="Jawa Tengah">Jawa Tengah</option>
                        <option value="Jawa Timur">Jawa Timur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">Kota/Kabupaten</label>
                    <select id="city" required>
                        <option value="">Pilih Kota/Kabupaten</option>
                        <option value="Bandung">Bandung</option>
                        <option value="Jakarta">Jakarta</option>
                        <option value="Surabaya">Surabaya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="district">Kecamatan</label>
                    <input type="text" id="district" required>
                </div>
                <div class="form-group">
                    <label for="postalCode">Kode Pos</label>
                    <input type="text" id="postalCode" required>
                </div>
                <div class="form-group">
                    <label for="fullAddress">Alamat Lengkap</label>
                    <textarea id="fullAddress" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="isDefault"> Jadikan alamat utama
                    </label>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <span class="close">&times;</span>
            <h2>Konfirmasi</h2>
            <p id="confirmMessage">Apakah Anda yakin ingin menghapus alamat ini?</p>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="cancelConfirmBtn">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Ya, Hapus</button>
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
            <div class="footer-grid">
                <div class="footer-column">
                    <h3>Produk Bukabuku</h3>
                    <ul>
                        <li><a href="#">Buku Baru</a></li>
                        <li><a href="#">Buku Best Seller</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Lainnya</h3>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
                
                <div class="footer-column contact">
                    <h3>Hubungi Kami</h3>
                    <ul>
                        <li>Email: info@bukabuku.com</li>
                        <li>Telepon: (021) 12345678</li>
                    </ul>
                </div>
            </div>
            
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
        // Sample data for addresses
        let addresses = [
            {
                id: 1,
                name: "Alamat Rumah",
                recipient: "Adelia Putri",
                phone: "081234567890",
                province: "Jawa Barat",
                city: "Bandung",
                district: "Coblong",
                postalCode: "40132",
                address: "Jl. Melati No. 123, RT 05/RW 02\nKel. Sukajadi, Kec. Coblong\nKota Bandung",
                isDefault: true
            },
            {
                id: 2,
                name: "Alamat Kantor",
                recipient: "Adelia Putri",
                phone: "081234567890",
                province: "Jawa Barat",
                city: "Bandung",
                district: "Bandung Wetan",
                postalCode: "40115",
                address: "Gedung Buku Indah Lt. 5\nJl. Merdeka No. 45\nKota Bandung",
                isDefault: false
            }
        ];

        // DOM Elements
        const addressList = document.getElementById('addressList');
        const addAddressBtn = document.getElementById('addAddressBtn');
        const addressModal = document.getElementById('addressModal');
        const confirmModal = document.getElementById('confirmModal');
        const addressForm = document.getElementById('addressForm');
        const modalTitle = document.getElementById('modalTitle');
        const closeButtons = document.getElementsByClassName('close');
        const cancelBtn = document.getElementById('cancelBtn');
        const cancelConfirmBtn = document.getElementById('cancelConfirmBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const confirmMessage = document.getElementById('confirmMessage');

        // Variables for tracking state
        let currentAction = 'add';
        let addressToDelete = null;

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            renderAddresses();
            
            // Event listeners for modal buttons
            addAddressBtn.addEventListener('click', () => openAddressModal('add'));
            
            Array.from(closeButtons).forEach(button => {
                button.addEventListener('click', closeAllModals);
            });
            
            cancelBtn.addEventListener('click', closeAllModals);
            cancelConfirmBtn.addEventListener('click', closeAllModals);
            
            confirmBtn.addEventListener('click', confirmDelete);
            
            addressForm.addEventListener('submit', handleAddressSubmit);
            
            // Close modal when clicking outside
            window.addEventListener('click', (event) => {
                if (event.target === addressModal) {
                    closeAllModals();
                }
                if (event.target === confirmModal) {
                    closeAllModals();
                }
            });
        });

        // Render addresses to the page
        function renderAddresses() {
            addressList.innerHTML = '';
            
            if (addresses.length === 0) {
                addressList.innerHTML = `
                    <div class="no-address">
                        <p>Anda belum memiliki alamat yang tersimpan.</p>
                    </div>
                `;
                return;
            }
            
            addresses.forEach(address => {
                const addressCard = document.createElement('div');
                addressCard.className = 'address-card';
                addressCard.innerHTML = `
                    <div class="address-name">
                        ${address.name}
                        ${address.isDefault ? '<span class="default-badge">Utama</span>' : ''}
                    </div>
                    <div class="address-detail">
                        ${address.recipient}<br>
                        ${address.address}<br>
                        ${address.city}, ${address.province} ${address.postalCode}<br>
                        No. HP: ${address.phone}
                    </div>
                    <div class="address-actions">
                        <button class="edit-btn" data-id="${address.id}">Ubah</button>
                        <button class="delete-btn" data-id="${address.id}">Hapus</button>
                        ${!address.isDefault ? `<button class="set-default-btn" data-id="${address.id}">Jadikan Utama</button>` : ''}
                    </div>
                `;
                addressList.appendChild(addressCard);
            });
            
            // Add event listeners to action buttons
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const id = parseInt(e.target.getAttribute('data-id'));
                    openAddressModal('edit', id);
                });
            });
            
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const id = parseInt(e.target.getAttribute('data-id'));
                    confirmDeleteAddress(id);
                });
            });
            
            document.querySelectorAll('.set-default-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const id = parseInt(e.target.getAttribute('data-id'));
                    setDefaultAddress(id);
                });
            });
        }

        // Open address modal for adding or editing
        function openAddressModal(action, id = null) {
            currentAction = action;
            
            if (action === 'add') {
                modalTitle.textContent = 'Tambah Alamat Baru';
                addressForm.reset();
                document.getElementById('addressId').value = '';
                document.getElementById('isDefault').checked = addresses.length === 0;
            } else if (action === 'edit' && id) {
                modalTitle.textContent = 'Ubah Alamat';
                const address = addresses.find(addr => addr.id === id);
                if (address) {
                    document.getElementById('addressId').value = address.id;
                    document.getElementById('addressName').value = address.name;
                    document.getElementById('recipientName').value = address.recipient;
                    document.getElementById('phoneNumber').value = address.phone;
                    document.getElementById('province').value = address.province;
                    document.getElementById('city').value = address.city;
                    document.getElementById('district').value = address.district;
                    document.getElementById('postalCode').value = address.postalCode;
                    document.getElementById('fullAddress').value = address.address;
                    document.getElementById('isDefault').checked = address.isDefault;
                }
            }
            
            addressModal.style.display = 'block';
        }

        // Handle form submission
        function handleAddressSubmit(e) {
            e.preventDefault();
            
            const addressData = {
                id: currentAction === 'add' ? Date.now() : parseInt(document.getElementById('addressId').value),
                name: document.getElementById('addressName').value,
                recipient: document.getElementById('recipientName').value,
                phone: document.getElementById('phoneNumber').value,
                province: document.getElementById('province').value,
                city: document.getElementById('city').value,
                district: document.getElementById('district').value,
                postalCode: document.getElementById('postalCode').value,
                address: document.getElementById('fullAddress').value,
                isDefault: document.getElementById('isDefault').checked
            };
            
            if (addressData.isDefault) {
                addresses.forEach(addr => addr.isDefault = false);
            }
            
            if (currentAction === 'add') {
                addresses.push(addressData);
            } else {
                const index = addresses.findIndex(addr => addr.id === addressData.id);
                if (index !== -1) {
                    addresses[index] = addressData;
                }
            }
            
            renderAddresses();
            closeAllModals();
        }

        // Confirm address deletion
        function confirmDeleteAddress(id) {
            addressToDelete = id;
            confirmMessage.textContent = 'Apakah Anda yakin ingin menghapus alamat ini?';
            confirmModal.style.display = 'block';
        }

        // Handle delete confirmation
        function confirmDelete() {
            if (addressToDelete) {
                addresses = addresses.filter(addr => addr.id !== addressToDelete);
                
                // If we deleted the default address and there are other addresses, set the first one as default
                if (addresses.length > 0 && !addresses.some(addr => addr.isDefault)) {
                    addresses[0].isDefault = true;
                }
                
                renderAddresses();
                addressToDelete = null;
                closeAllModals();
            }
        }

        // Set default address
        function setDefaultAddress(id) {
            addresses.forEach(addr => {
                addr.isDefault = addr.id === id;
            });
            renderAddresses();
        }

        // Close all modals
        function closeAllModals() {
            addressModal.style.display = 'none';
            confirmModal.style.display = 'none';
        }

        // Initialize with sample data if empty (for demo purposes)
        if (addresses.length === 0) {
            addresses = [
                {
                    id: 1,
                    name: "Alamat Rumah",
                    recipient: "Adelia ",
                    phone: "081234567890",
                    province: "Jawa Barat",
                    city: "Bandung",
                    district: "Coblong",
                    postalCode: "40132",
                    address: "Jl. Melati No. 123, RT 05/RW 02\nKel. Sukajadi, Kec. Coblong\nKota Bandung",
                    isDefault: true
                },
                {
                    id: 2,
                    name: "Alamat Kantor",
                    recipient: "Adelia ",
                    phone: "081234567890",
                    province: "Jawa Barat",
                    city: "Bandung",
                    district: "Bandung Wetan",
                    postalCode: "40115",
                    address: "Gedung Buku Indah Lt. 5\nJl. Merdeka No. 45\nKota Bandung",
                    isDefault: false
                }
            ];
            renderAddresses();
        }
                
        // JavaScript to handle the dropdown behavior
        document.addEventListener('DOMContentLoaded', function() {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const dropdownMenu = document.querySelector('.profile-dropdown-menu');
            
            // Keep dropdown open when moving between icon and menu
            let dropdownTimeout;
            
            profileDropdown.addEventListener('mouseenter', function() {
                clearTimeout(dropdownTimeout);
                dropdownMenu.style.display = 'block';
                setTimeout(() => {
                    dropdownMenu.style.opacity = '1';
                    dropdownMenu.style.visibility = 'visible';
                }, 10);
            });
            
            profileDropdown.addEventListener('mouseleave', function() {
                // Start timeout when leaving the dropdown area
                dropdownTimeout = setTimeout(() => {
                    dropdownMenu.style.opacity = '0';
                    dropdownMenu.style.visibility = 'hidden';
                    setTimeout(() => {
                        dropdownMenu.style.display = 'none';
                    }, 200);
                }, 300); // 300ms delay before closing
            });
            
            dropdownMenu.addEventListener('mouseenter', function() {
                clearTimeout(dropdownTimeout);
            });
            
            dropdownMenu.addEventListener('mouseleave', function() {
                dropdownTimeout = setTimeout(() => {
                    dropdownMenu.style.opacity = '0';
                    dropdownMenu.style.visibility = 'hidden';
                    setTimeout(() => {
                        dropdownMenu.style.display = 'none';
                    }, 200);
                }, 300);
            });
        });
    </script>
</body>
</html>