<?php
session_start();
include "koneksi.php";

$email = isset($_SESSION['email']);
$username = isset($_SESSION['username']);

$id_buku = $_GET['id_buku'];
$sql = "SELECT * FROM buku WHERE id_buku = $id_buku";
$query = mysqli_query($koneksi, $sql);
$queryBuku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY RAND() limit 6");
$queryBuku01 = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = $id_buku");
$queryKategori2 = mysqli_query($koneksi, "SELECT * FROM kategori");

$isInWishlist = false;
if (isset($_SESSION['id_users'])) {
    $id_users = $_SESSION['id_users'];
    $checkWishlist = mysqli_query($koneksi, "SELECT * FROM wishlist WHERE id_users = $id_users AND id_buku = $id_buku");
    $isInWishlist = mysqli_num_rows($checkWishlist) > 0;
}

if ($buku = mysqli_fetch_assoc($query)) {
    
?>

<!DOCTYPE html>
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
            position: relative;
        }

        /* Main Wrapper */
        .main-wrapper {
            position: relative;
            min-height: calc(100vh - 150px);
            padding-bottom: 40px;
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

        /* sebelum login */

        .navbar-right1 {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            color: #000;
            gap: 10px;
            cursor: pointer;
        }

        .navbar-right1 {
            gap: 130px;
        }

        .navbar-right1 a {
            color: #000;
        }

        .navbar-right1 a:hover {
            color: var(--purple);
        }

        .login {
            display: flex;
            gap: 10px;
        }

        .login .btn-masuk,
        .login .btn-daftar {
            font-size: 1.4rem;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .login .btn-masuk {
            background-color: white;
            color: black;
            border: 1px solid black;
        }

        .login .btn-masuk:hover {
            background-color: #f0f0f0;
        }

        .login .btn-daftar {
            background-color: var(--purple);
            color: white;
        }

        .login .btn-daftar:hover {
            background-color: #a56abd;
        }

        /* sesudah login */
        
        .navbar-right {
            gap: 130px;
        }

        .navbar-right a {
            color: #000;
        }

        .navbar-right a:hover {
            color: var(--purple);
        }

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

        /* Main Content */
        .main-content {
            padding: 0 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .main-content .book-details {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 50px;
            margin-bottom: 40px;
        }

        .main-content .book-details img {
            width: 300px;
            height: 428px;
            object-fit: cover;
            flex-shrink: 0;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .main-content .book-details .details {
            flex: 1;
        }

        .main-content .book-details .details h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
             color: #4b5563;
        }

        .main-content .book-details .details p {
            font-size: 1.5rem;
            color: #4b5563;
            margin-top: 8px;
        }

        .main-content .book-details .details button {
            margin-top: 13px;
            margin-bottom: 13px;
            color: #4b5563;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
        }
        
        #favorite-button {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
            /* padding: 8px 12px;
            border-radius: 5px; */
        }

        #favorite-button i {
            margin-right: 5px;
            color: #4b5563;
        }

        #favorite-button:hover {
            background-color: #f5f5f5;
        }

        #favorite-button:hover i {
            color: #6b21a8;
        }

        .main-content .book-details .details .description {
            color: #4b5563;
            margin-top: 10px;
            font-size: 1.4rem;
            line-height: 1.6;
        }

        .post {
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
            /* margin: 2rem 0; */
        }

        .read-more {
            margin-top: 10px; 
            align-self: flex-start;
            background: none; 
            border: none; 
            color: var(--purple);
            cursor: pointer; 
            /* font-size: 1rem;
            padding: 0; */
        }

        .main-content .book-details .details .book-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 2rem;
        }

        .main-content .book-details .details .book-info p{
            font-size: 1.3rem;
            margin: 5px 0;
        }

        .main-content .book-details .details .book-info .font-semibold {
            font-weight: 600;
            color: #333;
        }

        /* Produk Terkait */
        .title-card {
            margin-top: 40px;
            text-align: left;
            font-size: 2.4rem;
            padding: 0 24px;
            max-width: 1200px;
            margin: 40px auto 20px;
        }

        .wrapper-card {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            padding: 0 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            /* transition: transform 0.3s; */
        }

        /* .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
        } */

        .card img {
            width: 124px;
            height: auto;
            object-fit: cover;
            /* margin-bottom: 1rem; */
        }

        .card p {
            margin: 5px 0;
            /* font-size: 1.4rem; */
        }

        .card small {
            color: #888;
            /* font-size: 1.2rem; */
        }

        .card-price {
            color: var(--purple);
            font-weight: bold;
            /* font-size: 1.5rem; */
            /* margin-top: 5px; */
        }

        /* Cart Container */
        .cart-container {
            position: sticky;
            bottom: 0;
            /* background: white; */
            padding: 15px 24px;
            /* box-shadow: 0 -2px 10px rgba(0,0,0,0.1); */
            z-index: 999;
            margin-top: 40px;
        }

        .cart {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        } 

        .cart-details {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-grow: 1;
        }

        .cart-details img {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }

        .info {
            display: flex;
            flex-direction: column;
        }

        .info p {
            margin: 4px 0;
            color: #4b5563;
        }

        .title {
            font-weight: bold;
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .author {
            font-size: 1.2rem;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .price {
            color: var(--purple);
            font-weight: bold;
            font-size: 1.4rem;
        }

        .cart-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .add-to-cart {
            background-color: var(--purple);
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 1.4rem;
            font-weight: 500;
            transition: background-color 0.3s;
            white-space: nowrap;
        }

        .add-to-cart:hover {
            background-color: #5a2c6b;
        }

        .add-cart {
            background-color: #fff;
            color: var(--purple);
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid var(--purple);
            cursor: pointer;
            font-size: 1.4rem;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .add-cart:hover {
            background-color: #f8f0fa;
        }

        /* Footer Styles */
        .footer-brand {
            margin-top: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 30px auto 20px;
            padding: 0 24px;
            max-width: 1200px;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
            background: white;
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
            padding: 0 24px;
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

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-wrap: wrap;
                height: auto;
                padding: 10px 0;
            }

            .navbar-middle {
                order: 3;
                width: 100%;
                margin: 10px 0;
            }

            .navbar-right, .navbar-right1 {
                gap: 20px;
            }

            .main-content .book-details {
                flex-direction: column;
                gap: 20px;
            }

            .main-content .book-details img {
                width: 100%;
                height: auto;
                max-width: 300px;
                margin: 0 auto;
            }

            .wrapper-card {
                grid-template-columns: repeat(2, 1fr);
            }

            .cart {
                flex-direction: column;
                align-items: flex-start;
            }

            .cart-actions {
                width: 100%;
                justify-content: space-between;
                margin-top: 15px;
            }

            .add-cart, .add-to-cart {
                width: 48%;
                text-align: center;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-brand {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .footer-brand p {
                text-align: left;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .wrapper-card {
                grid-template-columns: 1fr;
            }

            .main-content .book-details .details .book-info {
                grid-template-columns: 1fr;
            }

            .cart-details {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-grid {
                grid-template-columns: 1fr;
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
                <?php if (!isset($_SESSION['id_users'])): ?>
                    <!-- Jika belum login -->
                <div class="navbar-right1">
                    <a href="LoginRegister.php" class="fas fa-shopping-cart"></a>
                    <div class="login">
                    <a href="LoginRegister.php"><p class="btn-masuk">Masuk</p></a>
                    <a href="LoginRegister.php"><p class="btn-daftar">Daftar</p></a>
                    </div>
                </div>

                <?php else: ?>
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
                                <li><a href="TransaksiU.php"><i class="ri-shopping-bag-line"></i> Transaksi</a></li>
                                <li><a href="wishlist.php"><i class="ri-heart-line"></i> Wishlist</a></li>
                                <li><a href="logout.php"><i class="ri-logout-box-line"></i> Keluar Akun</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
     <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <div class="main-content">
            <div class="book-details">
                <!-- Book Image -->
                <img src="image/<?= $buku['gambar']; ?>" alt="<?= $buku['judul'] ?>">
                <!-- Book Details -->
                <div class="details">
                    <h1><?= $buku['judul'] ?></h1>
                    <p class="harga">Rp <?= number_format($buku['harga'], 0, ',', '.') ?></p>
                    <?php if (isset($_SESSION['id_users'])): ?>
                        <form method="post" action="wishlist_action.php">
                            <input type="hidden" name="id_buku" value="<?= $id_buku ?>">
                            <button id="favorite-button" type="submit">
                                <i class="<?= $isInWishlist ? 'fas' : 'far' ?> fa-heart"></i> 
                                <?= $isInWishlist ? 'Hapus dari Favorit' : 'Favorit' ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="LoginRegister.php" id="favorite-button">
                            <i class="far fa-heart"></i> Favorit
                        </a>
                    <?php endif; ?>
                    <div class="post">
                        <h2>Deskripsi</h2>
                        <p><?= substr($buku['deskripsi'], 0, 300) ?></p>
                        <p class="full-content" style="display: none;"><?= substr($buku['deskripsi'], 300) ?></p>
                        <button class="read-more">Baca Selengkapnya</button>
                    </div>
                    
                    <h2>Detail Buku</h2>
                    <div class="book-info">
                        <div>
                            <p>Penerbit</p>
                            <p class="font-semibold"><?= $buku['penerbit'] ?></p>
                        </div>
                        <div>
                            <p>Tanggal Terbit</p>
                            <p class="font-semibold"><?= date('j M Y', strtotime($buku['tanggal_terbit'])) ?></p>
                        </div>
                        <div>
                            <p>ISBN</p>
                            <p class="font-semibold"><?= $buku['isbn'] ?></p>
                        </div>
                        <div>
                            <p>Halaman</p>
                            <p class="font-semibold"><?= $buku['jumlah_halaman'] ?></p>
                        </div>
                        <div>
                            <p>Bahasa</p>
                            <p class="font-semibold"><?= $buku['bahasa'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- produk terkait -->
        <h2 class="title-card">Produk Terkait</h2>
        <div class="wrapper-card">
            <?php while ($buku = mysqli_fetch_assoc($queryBuku)) { ?>
                <a href="detail.php?id_buku=<?= $buku['id_buku'] ?>">
                    <div class="card">
                        <img src="image/<?= $buku['gambar'] ?>" alt="<?= $buku['judul'] ?>">
                        <p><small><?= $buku['penulis'] ?></small></p>
                        <p><?= $buku['judul'] ?></p>
                        <p class="card-price">Rp <?= number_format($buku['harga'], 0, ',', '.') ?></p>
                    </div>
                </a>
            <?php } ?>
        </div>

        <!-- Cart Section -->
        <div class="cart-container">
            <div class="cart">
                <?php while ($buku = mysqli_fetch_assoc($queryBuku01)) { ?>
                <div class="cart-details">
                    <img src="image/<?= $buku['gambar'] ?>" alt="<?= $buku['judul'] ?>">
                    <div class="info">
                        <p class="author"><?= $buku['penulis'] ?></p>
                        <p class="title"><?= $buku['judul'] ?></p>
                        <p class="price">Rp <?= number_format($buku['harga'], 0, ',', '.') ?></p>
                    </div>
                </div>
                <div class="cart-actions">
                    <a href="tambah_keranjang.php?id_buku=<?= $buku['id_buku'] ?>" class="add-cart">Keranjang</a>
                    <a href="checkout2.php?id_buku=<?= $buku['id_buku'] ?>&jumlah=1" class="add-to-cart">Beli Sekarang</a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Footer Brand -->
    <div class="footer-brand">
        <img src="image/Navy Colorful fun Kids Book Store Logo1.png" alt="Bukabuku Logo">
        <p>Toko buku online terbesar, terlengkap dan terpercaya di Indonesia</p>
    </div>
    
    <!-- Footer -->
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
        // Favorite button functionality
        document.getElementById('favorite-button').addEventListener('click', function() {
            const heartIcon = this.querySelector('i');
            
            // Toggle class untuk mengubah status favorit
            heartIcon.classList.toggle('fas'); // Mengubah ke solid heart
            heartIcon.classList.toggle('far'); // Mengubah ke regular heart

            // Ubah teks tombol
            if (heartIcon.classList.contains('fas')) {
                this.innerHTML = '<i class="fas fa-heart"></i> Favorit';
            } else {
                this.innerHTML = '<i class="far fa-heart"></i> Favorit';
            }
        });
        
        // Read more functionality
        document.querySelector('.read-more').addEventListener('click', function() {
            const fullContent = document.querySelector('.full-content');
            if (fullContent.style.display === 'none') {
                fullContent.style.display = 'block';
                this.textContent = 'Tutup';
            } else {
                fullContent.style.display = 'none';
                this.textContent = 'Baca Selengkapnya';
            }
        });
    });
    </script>
</body>
</html>
<?php
}
?>