<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: LoginRegister.php");
    exit();
}

$id_users = $_SESSION['id_users'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$queryKategori2 = mysqli_query($koneksi, "SELECT * FROM kategori");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                // Tambah alamat
                $nama_penerima = mysqli_real_escape_string($koneksi, $_POST['recipientName']);
                $no_telepon = mysqli_real_escape_string($koneksi, $_POST['phoneNumber']);
                $alamat_lengkap = mysqli_real_escape_string($koneksi, $_POST['fullAddress']);
                $kabupaten = mysqli_real_escape_string($koneksi, $_POST['city']);
                $provinsi = mysqli_real_escape_string($koneksi, $_POST['province']);
                $kode_pos = mysqli_real_escape_string($koneksi, $_POST['postalCode']);
                
                $query = "INSERT INTO alamat (id_users, nama_penerima, no_telepon, alamat_lengkap, kabupaten, provinsi, kode_pos) 
                          VALUES ('$id_users', '$nama_penerima', '$no_telepon', '$alamat_lengkap', '$kabupaten', '$provinsi', '$kode_pos')";
                mysqli_query($koneksi, $query);
                break;
                
            case 'edit':
                // Edit alamat
                $id_alamat = mysqli_real_escape_string($koneksi, $_POST['addressId']);
                $nama_penerima = mysqli_real_escape_string($koneksi, $_POST['recipientName']);
                $no_telepon = mysqli_real_escape_string($koneksi, $_POST['phoneNumber']);
                $alamat_lengkap = mysqli_real_escape_string($koneksi, $_POST['fullAddress']);
                $kabupaten = mysqli_real_escape_string($koneksi, $_POST['city']);
                $provinsi = mysqli_real_escape_string($koneksi, $_POST['province']);
                $kode_pos = mysqli_real_escape_string($koneksi, $_POST['postalCode']);
                
                $query = "UPDATE alamat SET 
                          nama_penerima = '$nama_penerima',
                          no_telepon = '$no_telepon',
                          alamat_lengkap = '$alamat_lengkap',
                          kabupaten = '$kabupaten',
                          provinsi = '$provinsi',
                          kode_pos = '$kode_pos'
                          WHERE id_alamat = '$id_alamat' AND id_users = '$id_users'";
                mysqli_query($koneksi, $query);
                break;
                
            case 'delete':
                // Delete alamat
                $id_alamat = mysqli_real_escape_string($koneksi, $_POST['addressId']);
                $query = "DELETE FROM alamat WHERE id_alamat = '$id_alamat' AND id_users = '$id_users'";
                mysqli_query($koneksi, $query);
                break;
        }
        
        header("Location: alamat.php");
        exit();
    }
}

// mengambil alamat dari database
$queryAlamat = mysqli_query($koneksi, "SELECT * FROM alamat WHERE id_users = '$id_users' ORDER BY id_alamat DESC");
$addresses = [];
while ($alamat = mysqli_fetch_assoc($queryAlamat)) {
    $addresses[] = $alamat;
}
?>

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

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Akun Saya</h3>
                    <ul class="sidebar-menu">
                        <li><a href="akunU.php">Pengaturan Profil</a></li>
                        <li><a href="wishlist.php">Wishlist</a></li>
                        <li><a href="transaksiU.php">Transaksi</a></li>
                        <li><a href="alamat.php" class="active">Alamat</a></li>
                    </ul>
                </div>
            </div>

            <div class="content">
                <div class="address-header">
                    <h1 class="section-title">Daftar Alamat</h1>
                    <button class="btn-add" id="addAddressBtn">+ Tambah Alamat Baru</button>
                </div>
                
                <div class="address-list" id="addressList">
                    <?php if (empty($addresses)): ?>
                        <div class="no-address">
                            <p>Anda belum memiliki alamat yang tersimpan.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($addresses as $address): ?>
                            <div class="address-card">
                                <div class="address-name">
                                    Alamat Rumah
                                </div>
                                <div class="address-detail">
                                    <?= htmlspecialchars($address['nama_penerima']) ?><br>
                                    <?= nl2br(htmlspecialchars($address['alamat_lengkap'])) ?><br>
                                    <?= htmlspecialchars($address['kabupaten']) ?>, <?= htmlspecialchars($address['provinsi']) ?> <?= htmlspecialchars($address['kode_pos']) ?><br>
                                    No. HP: <?= htmlspecialchars($address['no_telepon']) ?>
                                </div>
                                <div class="address-actions">
                                    <button class="edit-btn" data-id="<?= $address['id_alamat'] ?>">Ubah</button>
                                    <button class="delete-btn" data-id="<?= $address['id_alamat'] ?>">Hapus</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Address Modal -->
    <div id="addressModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Tambah Alamat Baru</h2>
            <form id="addressForm" method="POST">
                <input type="hidden" id="addressId" name="addressId">
                <input type="hidden" name="action" id="formAction" value="add">
                <div class="form-group">
                    <label for="recipientName">Nama Penerima</label>
                    <input type="text" id="recipientName" name="recipientName" required>
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Nomor Telepon</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" required>
                </div>
                <div class="form-group">
                    <label for="province">Provinsi</label>
                    <input type="text" id="province" name="province" required>
                </div>
                <div class="form-group">
                    <label for="city">Kota/Kabupaten</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="form-group">
                    <label for="postalCode">Kode Pos</label>
                    <input type="text" id="postalCode" name="postalCode" required>
                </div>
                <div class="form-group">
                    <label for="fullAddress">Alamat Lengkap</label>
                    <textarea id="fullAddress" name="fullAddress" rows="3" required></textarea>
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
            <form id="deleteForm" method="POST">
                <input type="hidden" id="deleteAddressId" name="addressId">
                <input type="hidden" name="action" value="delete">
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelConfirmBtn">Batal</button>
                    <button type="submit" class="btn btn-primary" id="confirmBtn">Ya, Hapus</button>
                </div>
            </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const addressModal = document.getElementById('addressModal');
        const confirmModal = document.getElementById('confirmModal');
        const addressForm = document.getElementById('addressForm');
        const deleteForm = document.getElementById('deleteForm');
        const modalTitle = document.getElementById('modalTitle');
        const closeButtons = document.querySelectorAll('.close');
        const cancelBtn = document.getElementById('cancelBtn');
        const cancelConfirmBtn = document.getElementById('cancelConfirmBtn');
        const formAction = document.getElementById('formAction');
        const addressIdInput = document.getElementById('addressId');
        const deleteAddressIdInput = document.getElementById('deleteAddressId');

        // Open modal for adding new address
        document.getElementById('addAddressBtn').addEventListener('click', () => {
            openAddressModal('add');
        });

        // Handle edit and delete buttons using event delegation
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('edit-btn')) {
                e.preventDefault();
                const id = e.target.getAttribute('data-id');
                openAddressModal('edit', id);
            }
            
            if (e.target.classList.contains('delete-btn')) {
                e.preventDefault();
                const id = e.target.getAttribute('data-id');
                confirmDeleteAddress(id);
            }
        });

        // Close modals
        closeButtons.forEach(button => {
            button.addEventListener('click', closeAllModals);
        });
        
        cancelBtn.addEventListener('click', closeAllModals);
        cancelConfirmBtn.addEventListener('click', closeAllModals);
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === addressModal) {
                closeAllModals();
            }
            if (event.target === confirmModal) {
                closeAllModals();
            }
        });

        function openAddressModal(action, id = null) {
            formAction.value = action;
            
            if (action === 'add') {
                modalTitle.textContent = 'Tambah Alamat Baru';
                addressForm.reset();
                addressIdInput.value = '';
            } else if (action === 'edit' && id) {
                modalTitle.textContent = 'Ubah Alamat';
                addressIdInput.value = id;
                
                // Get address data from PHP array
                const addresses = <?php echo json_encode($addresses); ?>;
                const address = addresses.find(addr => addr.id_alamat == id);
                
                if (address) {
                    document.getElementById('recipientName').value = address.nama_penerima;
                    document.getElementById('phoneNumber').value = address.no_telepon;
                    document.getElementById('province').value = address.provinsi;
                    document.getElementById('city').value = address.kabupaten;
                    document.getElementById('postalCode').value = address.kode_pos;
                    document.getElementById('fullAddress').value = address.alamat_lengkap;
                }
            }
            
            addressModal.style.display = 'block';
        }

        function confirmDeleteAddress(id) {
            deleteAddressIdInput.value = id;
            confirmModal.style.display = 'block';
        }

        function closeAllModals() {
            addressModal.style.display = 'none';
            confirmModal.style.display = 'none';
        }

        // Profile dropdown behavior
        const profileDropdown = document.querySelector('.profile-dropdown');
        const dropdownMenu = document.querySelector('.profile-dropdown-menu');
        
        let dropdownTimeout;
        
        profileDropdown.addEventListener('mouseenter', function() {
            clearTimeout(dropdownTimeout);
            dropdownMenu.style.display = 'block';
        });
        
        profileDropdown.addEventListener('mouseleave', function() {
            dropdownTimeout = setTimeout(() => {
                dropdownMenu.style.display = 'none';
            }, 300);
        });
        
        dropdownMenu.addEventListener('mouseenter', function() {
            clearTimeout(dropdownTimeout);
        });
        
        dropdownMenu.addEventListener('mouseleave', function() {
            dropdownTimeout = setTimeout(() => {
                dropdownMenu.style.display = 'none';
            }, 300);
        });
    });
</script>
</body>
</html>