<?php
session_start();
include "koneksi.php";

$email = isset($_SESSION['email']);
$username = isset($_SESSION['username']);

$id_users = $_SESSION['id_users'];
$total = 0;

// $keranjang = [];
$queryK = mysqli_query($koneksi, "SELECT keranjang.jumlah, 
    buku.id_buku, buku.id_kategori, buku.judul, buku.penulis, buku.penerbit,
    buku.harga, buku.stok, buku.tanggal_terbit, buku.jumlah_halaman, buku.bahasa,
    buku.isbn, buku.gambar, buku.deskripsi
    FROM keranjang  
    JOIN buku ON keranjang.id_buku = buku.id_buku 
    WHERE keranjang.id_users = $id_users");

$keranjang = [];
// Hitung total untuk item yang dicentang
while ($buku = mysqli_fetch_assoc($queryK)) {
    $subtotal = $buku['harga'] * $buku['jumlah'];
    $total += $subtotal;
    $keranjang[] = $buku;
}
// Reset pointer
mysqli_data_seek($queryK, 0);

$query = mysqli_query($koneksi,"SELECT * FROM buku ORDER BY RAND() limit 7");
$queryB = mysqli_query($koneksi, "SELECT * FROM buku");
$queryKategori2 = mysqli_query($koneksi, "SELECT * FROM kategori");

// while ($buku = mysqli_fetch_assoc($queryK)) {
//     $subtotal = $buku['harga'] * $buku['jumlah'];
//     $total += $subtotal;

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
    <link rel="stylesheet" href="css/keranjang.css">
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

    
    
    <section class="cart-container">
    <?php if (mysqli_num_rows($queryK) == 0): ?>
        
        <div class="empty-cart">
            <h1>Keranjang Kamu Kosong</h1>
            <p>Kami punya banyak buku yang siap memberi kamu kebahagiaan.<br>Yuk, belanja sekarang!</p>
            <a href="index.php" class="shop-button">Mulai Belanja</a>
        </div>
        <?php  else : ?>
            <form action="checkout2.php" method="POST" id="formCheckout">
                <?php while ($buku = mysqli_fetch_assoc($queryK)) { ?>
                <div class="cart-item">
                    <div class="select-item">
                        <input type="checkbox" class="item-checkbox form-checkbox h-5 w-5 text-purple-600" 
                            name="selected_items[]" 
                            value="<?= $buku['id_buku'] ?>" 
                            data-id="<?= $buku['id_buku'] ?>" 
                            data-harga="<?= $buku['harga'] ?>"
                            data-jumlah="<?= $buku['jumlah'] ?>">
                        <input type="hidden" name="keranjang[<?= $buku['id_buku'] ?>][jumlah]" value="<?= $buku['jumlah'] ?>">
                    </div>
                        <img src="image/<?= $buku['gambar'] ?>" alt="<?= $buku['judul'] ?>">
                        <div class="cart-details">
                            <h2><?= $buku['judul'] ?></h2>
                            <p class="author"><?= $buku['penulis'] ?></p>
                            <p class="price">Rp<?= number_format($buku['harga'], 0, ',', '.'); ?></p>
                            
                            <div class="quantity">
                                <button type="button" class="minus" data-id="<?= $buku['id_buku'] ?>">-</button>
                                <input type="number" class="quantity-input" 
                                    name="keranjang[<?= $buku['id_buku'] ?>][jumlah]" 
                                    data-id="<?= $buku['id_buku'] ?>" 
                                    value="<?= $buku['jumlah'] ?>" min="1">
                                <button type="button" class="plus" data-id="<?= $buku['id_buku'] ?>">+</button>
                            </div>
                        </div>
                        
                        <button type="button" class="remove" onclick="hapusItem(<?= $buku['id_buku'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <?php } ?>
                    
                    <input type="hidden" name="from_cart" value="1">
                    <div class="box-check">
                        <div class="cart-summary">
                            <p>Total: <span id="total-price">Rp<?= number_format($total, 0, ',', '.'); ?></span></p>                        
                            <button type="submit" class="checkout" id="checkout-button">Checkout</button>
                        </div>
                    </div>
                </div>
            </form>
    <?php endif; ?>
    </section>

    

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

 <script>
    // Update quantity functions
    document.querySelectorAll('.plus').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const idBuku = button.getAttribute('data-id');
            const input = document.querySelector(`.quantity-input[data-id="${idBuku}"]`);
            let jumlah = parseInt(input.value);
            jumlah++;
            input.value = jumlah;

            updateJumlah(idBuku, jumlah);
        });
    });

    document.querySelectorAll('.minus').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const idBuku = button.getAttribute('data-id');
            const input = document.querySelector(`.quantity-input[data-id="${idBuku}"]`);
            let jumlah = parseInt(input.value);
            if (jumlah > 1) {
                jumlah--;
                input.value = jumlah;
                updateJumlah(idBuku, jumlah);
            }
        });
    });

    function updateJumlah(idBuku, jumlah) {
        fetch('update_keranjang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id_buku=${idBuku}&jumlah=${jumlah}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            if (data !== 'ok') {
                alert('Gagal update jumlah: ' + data);
            }
            updateTotal(); // Update total after quantity changes
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupdate jumlah');
        });
    }

    // Calculate total
    function updateTotal() {
        let total = 0;
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');

        checkboxes.forEach(cb => {
            const harga = parseInt(cb.dataset.harga);
            const id = cb.dataset.id;
            const qtyInput = document.querySelector(`.quantity-input[data-id="${id}"]`);
            const jumlah = parseInt(qtyInput.value);
            total += harga * jumlah;
        });

        document.getElementById('total-price').textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    // Initialize event listeners for total calculation
    document.addEventListener('DOMContentLoaded', () => {
        // Update total when checkbox or quantity changes
        document.querySelectorAll('.item-checkbox, .quantity-input').forEach(el => {
            el.addEventListener('change', updateTotal);
        });

        // For + and - buttons
        document.querySelectorAll('.plus, .minus').forEach(button => {
            button.addEventListener('click', () => {
                setTimeout(updateTotal, 100); // Small delay to ensure input value has changed
            });
        });

        updateTotal(); // Initial calculation
    });

    // Delete item function
    function hapusItem(idBuku) {
        if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
            fetch('hapus_keranjang.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_buku=${idBuku}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Refresh page or remove element from DOM
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus item');
            });
        }
    }

    // Form validation before checkout
    document.getElementById('formCheckout').addEventListener('submit', function(e) {
        const selectedItems = document.querySelectorAll('.item-checkbox:checked');
        if (selectedItems.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu item untuk checkout');
            return false;
        }
        
        return true;
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