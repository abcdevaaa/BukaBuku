<?php
session_start();
include 'koneksi.php';
 
$email = isset($_SESSION['email']);
$username = isset($_SESSION['username']);

$sql = "SELECT * FROM buku";
$query = mysqli_query($koneksi, $sql);
$queryKategori = mysqli_query($koneksi, "SELECT * FROM kategori");
$queryKategori2 = mysqli_query($koneksi, "SELECT * FROM kategori");


?>


<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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


        /* Swiper */
        .swiper-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .swiper {
            width: 100%;
            height: 580px; /* Atur tinggi Swiper container */
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .swiper-slide img {
            width: auto;
            height: 100%;
            object-fit: cover; /* Gambar menutupi slide tanpa terdistorsi */
            border-radius: 10px;
        }

        /* Kategori Buku */
        .container2 {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            padding: 20px;
        }

        .category {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .category:hover {
            transform: scale(1.1);
        }

        .category img {
            width: 100px;
            height: 100px;
        }

        /* Rekomendasi Buku */
        .title-card {
            text-align: center;
            margin-top: 2rem;
            font-size: 2rem;
        }

        .wrapper-card {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .card {
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }

        .card img {
            width: 124px;
            height: auto;
            object-fit: cover;
        }

        .card p {
            margin: 5px 0;
        }

        .card small {
            color: #888;
        }

        .card-price {
            color: var(--purple);
            font-weight: bold;
        }

        a {
        color: inherit; /* pakai warna teks dari elemen di dalamnya */
        text-decoration: none; /* hilangin garis bawah */
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

        /* Responsive */
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-brand {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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
                    <img src="image/Navy Colorful fun Kids Book Store Logo.png" alt="Logo Bukabuku" class="logo">
                </div>
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
                
                    <form action="search.php" method="GET">
                        <div class="navbar-middle">
                        <input type="text" name="q" placeholder="Cari Produk, Judul Buku, Penulis">
                        <i class="ri-search-line"></i>
                        </div>
                    </form>
                
                <!-- <div class="navbar-middle">
                    <input type="text" placeholder="Cari Produk, Judul Buku, Penulis">
                    <i class="ri-search-line"></i>
                </div> -->
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

    <section class="swiper-container">
        <div class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="image/Group 26.png" alt="Slide 1">
                </div>
                <div class="swiper-slide">
                    <img src="image/slide2.png" alt="Slide 2">
                </div>
                <div class="swiper-slide">
                    <img src="image/gameover.png" alt="Slide 3">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Kategori Buku -->
    <section class="container2">
    <?php while($kategori = mysqli_fetch_assoc($queryKategori)) { ?>
            <a href="kategori.php?id=<?= $kategori['id_kategori'] ?>">
                <div class="category">
                    <img src="image/<?= $kategori['foto']; ?>">
                    <?= $kategori['nama_kategori'] ?>
                </div>
            </a>
        <?php } ?>
        </div>
    </section>

    <!-- Rekomendasi Buku -->
    <h2 class="title-card">Rekomendasi Untukmu</h2>
    <div class="wrapper-card">
        <?php while ($buku = mysqli_fetch_assoc($query)) { ?>
            <a href="detail.php?id_buku=<?= $buku['id_buku'] ?>">
        <div class="card">
            <img src="image/<?= $buku['gambar'] ?>" alt="<?= $buku['judul'] ?>">
            <p><small><?= $buku['penulis'] ?></small></p>
            <p><?= $buku['judul'] ?></p>
            <p class="card-price">Rp <?= number_format($buku['harga'], 0, ',', '.') ?></p>
        </div>
        <?php } ?>
        </a>
    </div>
    

    

  <!-- Footer -->
        <!-- Bagian Brand -->
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
    
    <!-- Script Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'slide',
            direction: 'horizontal',
            spaceBetween: 30,
        });

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