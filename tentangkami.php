<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: LoginRegister.php");
    exit();
}

$email = $_SESSION['email'];
$username = $_SESSION['username'];

$sql = "SELECT * FROM buku";
$query = mysqli_query($koneksi, $sql);

$queryKategori2 = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Bukabuku</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.2.0/remixicon.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/tentangkami.css">
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

    <!-- Main Content -->
    <main>
        <div class="about-header">
            <div class="container">
                <h1>Tentang Bukabuku</h1>
                <p>Toko buku online terpercaya yang menyediakan berbagai koleksi buku berkualitas dari berbagai genre untuk memenuhi kebutuhan literasi Anda</p>
            </div>
        </div>
        
        <!-- About Content -->
        <section class="about-section">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <h2>Cerita Kami</h2>
                        <p>Bukabuku didirikan dengan misi sederhana: membuat buku berkualitas lebih mudah diakses oleh semua orang. Berawal dari kecintaan terhadap dunia literasi, kami membangun toko ini dengan penuh semangat untuk berbagi pengetahuan.</p>
                        <p>Kami percaya bahwa setiap buku yang kami jual bukan hanya produk, tetapi sebuah jendela pengetahuan yang dapat mengubah hidup pembacanya.</p>
                    </div>
                    <div class="about-image">
                        <img src="https://images.unsplash.com/photo-1589998059171-988d887df646?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Toko Buku">
                    </div>
                </div>
            </div>
        </section>

        <!-- Vision Mission -->
        <section class="vm-section">
            <div class="container">
                <h2 class="section-title">Visi & Misi Kami</h2>
                <div class="vm-container">
                    <div class="vision">
                        <h3 class="vm-title"><i class="fas fa-eye"></i> Visi Kami</h3>
                        <p>Menjadi platform terdepan dalam menyebarkan pengetahuan dan kegemaran membaca melalui buku-buku berkualitas di Indonesia.</p>
                    </div>
                    <div class="mission">
                        <h3 class="vm-title"><i class="fas fa-bullseye"></i> Misi Kami</h3>
                        <ul class="mission-list">
                            <li>Menyediakan koleksi buku terlengkap dari berbagai penerbit ternama</li>
                            <li>Memberikan pelayanan terbaik dengan proses transaksi aman dan pengiriman cepat</li>
                            <li>Menjaga kualitas produk dan originalitas setiap buku yang kami jual</li>
                            <li>Menciptakan komunitas pecinta buku yang aktif dan inspiratif</li>
                            <li>Mendukung perkembangan literasi di Indonesia</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Advantages -->
        <section class="advantages">
            <div class="container">
                <h2 class="section-title">Keunggulan Bukabuku</h2>
                <div class="advantages-grid">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>Koleksi Lengkap</h3>
                        <p>Ribuan judul buku dari berbagai genre dan penerbit ternama</p>
                    </div>
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3>Pengiriman Cepat</h3>
                        <p>Diproses dalam 1x24 jam setelah pembayaran</p>
                    </div>
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3>Transaksi Aman</h3>
                        <p>Sistem pembayaran terpercaya dengan berbagai pilihan metode</p>
                    </div>
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>Layanan Pelanggan</h3>
                        <p>Siap membantu 7 hari seminggu melalui berbagai channel</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team -->
        <section class="team-section">
            <div class="container">
                <h2 class="section-title">Tim Kami</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <img src="image/ndun.jpeg" alt="Team Member" class="team-photo">
                        <h3>Adelia Safitri</h3>
                        <p>18470</p>
                        <h3>Frontend</h3>
                    </div>
                    <div class="team-member">
                        <img src="image/echan.jpeg" alt="Team Member" class="team-photo">
                        <h3>Eva Nur Aisyah</h3>
                        <p>18481</p>
                        <h3>Backend</h3>
                    </div>
                </div>
            </div>
        </section>
    </main>
          
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
    <script>
    // JavaScript to handle the dropdown behavior
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