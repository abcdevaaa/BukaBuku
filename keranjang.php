<?php
session_start();
include "koneksi.php";

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$id_users = $_SESSION['id_users'];
$total = 0;

$query = mysqli_query($koneksi, "SELECT keranjang.jumlah, buku.* 
    FROM keranjang  
    JOIN buku ON keranjang.id_buku = buku.id_buku 
    WHERE keranjang.id_users = $id_users");

while ($buku = mysqli_fetch_assoc($query)) {
    $subtotal = $buku['harga'] * $buku['jumlah'];
    $total += $subtotal;

$sql = "SELECT * FROM buku";
$query = mysqli_query($koneksi, $sql);


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.2.0/remixicon.css">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="keranjang.css">
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
                          <a href="#">Buku Fiksi</a>
                          <a href="#">Buku Nonfiksi</a>
                          <a href="#">Buku Anak</a>
                          <a href="#">Buku Pelajaran</a>
                          <a href="#">Buku Agama</a>
                          <a href="#">Komik</a>
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
    
    <main class="main">
        <div class="controls">
            <div class="select-all">
                <input type="checkbox" id="select-all" class="form-checkbox h-5 w-5 text-purple-600"/>
                <label for="select-all">Semua</label>
            </div>
            <button class="delete">
                <i class="fas fa-trash"></i>
                <span>Hapus</span>
            </button>
        </div>
    </main>
    

    <section class="cart-container">
    <?php if (empty($keranjang)) : ?>
        <div class="container">
            <div class="empty-cart">
                <h1>Keranjang Kamu Kosong</h1>
                <p>Kami punya banyak buku yang siap memberi kamu kebahagiaan.<br>Yuk, belanja sekarang!</p>
                <a href="index.php" class="shop-button">Mulai Belanja</a>
            </div>
        </div>
    <?php  else : ?>
        <div class="cart-item">
            <div class="select-item">
                <input type="checkbox" class="item-checkbox form-checkbox h-5 w-5 text-purple-600">
            </div>
            <img src="image/<?= $buku['gambar'] ?>" alt="<?= $buku['judul']; ?>">
            <div class="cart-details">
                <h2><?= $buku['judul'] ?></h2>
                <p class="author"><?= $buku['penulis'] ?></p>
                <p class="price">Rp<?= number_format($buku['harga'], 0, ',', '.'); ?></p>
                <p class="quantity">Jumlah: <?= $buku['jumlah']; ?></p>
                <!-- <div class="quantity">
                    <button class="minus">-</button>
                    <input type="number" value="1" min="1" class="quantity-input">
                    <button class="plus">+</button>
                </div> -->
            </div>
            <form action="hapus_keranjang.php" method="POST">
                <input type="hidden" name="id_buku" value="<?= $id_buku ?>">
                <button type="submit" class="remove"><i class="fas fa-trash"></i></button>
            </form>
        </div>
        <?php endif; ?>

    </section>
<?php }?>
    <div class="box-check">
        <div class="cart-summary">
            <p>Total: <span id="total-price">Rp<?= number_format($total, 0, ',', '.'); ?></span></p>
            <a href="checkout.php"><button class="checkout">Checkout</button></a>
        </div>
    </div>

    <div class="wrapper-card">
        <?php while ($buku = mysqli_fetch_assoc($query)) { ?>
            <a href="detail.php?id_buku=<?= $buku['id_buku'] ?>">
        <div class="card">
            <img src="image/<?= $buku['gambar']; ?>" alt="<?= $buku['judul']; ?>">
            <p><small><?= $buku['penulis']; ?></small></p>
            <p><?= $buku['judul']; ?></p>
            <p class="card-price">Rp <?= number_format($buku['harga'], 0, ',', '.'); ?></p>
        </div>
        <?php } ?>
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

    <script src="keranjang.js"></script>
</body>
</html>
