<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: LoginRegister.php");
    exit();
}

// Initialize variables
$id_buku = 0;
$jumlah = 1;
$total_harga = 0;
$from_cart = false;
$total_quantity = 0;

// Check if this is from cart or direct purchase
if (isset($_POST['from_cart']) && $_POST['from_cart'] == 1) {
    // Cart purchase
    $from_cart = true;
    
    if (!isset($_POST['selected_items']) || !is_array($_POST['selected_items'])) {
        die("Item keranjang tidak valid.");
    }
    
    $selected_items = array_map('intval', $_POST['selected_items']);
    $total_harga = isset($_POST['total_harga']) ? (int)$_POST['total_harga'] : 0;
    
    // Store cart items in session with quantities
    $_SESSION['cart_checkout_items'] = [];
    foreach ($selected_items as $id_buku) {
        // Get quantity from POST data or session (adjust according to your cart implementation)
        $item_qty = isset($_POST['keranjang'][$id_buku]['jumlah']) ? (int)$_POST['keranjang'][$id_buku]['jumlah'] : 1;
        $_SESSION['cart_checkout_items'][$id_buku] = [
            'id' => $id_buku,
            'quantity' => $item_qty
        ];
        $total_quantity += $item_qty;
    }
    
    // Use first item for display purposes
    $id_buku = $selected_items[0] ?? 0;
} else {
    // Direct purchase
    $id_buku = isset($_POST['id_buku']) ? (int)$_POST['id_buku'] : 0;
    $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;
    $total_harga = isset($_POST['total_harga']) ? (int)$_POST['total_harga'] : 0;
    $total_quantity = $jumlah;
}

// Validate inputs
if ($id_buku <= 0) {
    die("Buku tidak valid.");
}

if ($total_harga <= 0) {
    die("Total harga tidak valid.");
}

$metode_pengiriman = isset($_POST['metode_pengiriman']) ? (int)$_POST['metode_pengiriman'] : 0;

// Query book data (for display purposes)
$query_buku = "SELECT * FROM buku WHERE id_buku = ?";
$stmt = mysqli_prepare($koneksi, $query_buku);
mysqli_stmt_bind_param($stmt, "i", $id_buku);
mysqli_stmt_execute($stmt);
$result_buku = mysqli_stmt_get_result($stmt);

if (!$result_buku || mysqli_num_rows($result_buku) == 0) {
    die("Buku tidak ditemukan.");
}

$buku = mysqli_fetch_assoc($result_buku);

// Query user address
$id_users = $_SESSION['id_users'];
$query_alamat = "SELECT * FROM alamat WHERE id_users = ?";
$stmt = mysqli_prepare($koneksi, $query_alamat);
mysqli_stmt_bind_param($stmt, "i", $id_users);
mysqli_stmt_execute($stmt);
$result_alamat = mysqli_stmt_get_result($stmt);

if (!$result_alamat || mysqli_num_rows($result_alamat) == 0) {
    die("Alamat pengiriman tidak ditemukan.");
}

$alamat = mysqli_fetch_assoc($result_alamat);

// Query shipping method
$query_pengiriman = "SELECT * FROM metode_pengiriman WHERE id_metodePengiriman = ?";
$stmt = mysqli_prepare($koneksi, $query_pengiriman);
mysqli_stmt_bind_param($stmt, "i", $metode_pengiriman);
mysqli_stmt_execute($stmt);
$result_pengiriman = mysqli_stmt_get_result($stmt);

if (!$result_pengiriman || mysqli_num_rows($result_pengiriman) == 0) {
    die("Metode pengiriman tidak valid.");
}

$pengiriman = mysqli_fetch_assoc($result_pengiriman);

// Query payment methods
$query_metode_pembayaran = "SELECT * FROM metode_pembayaran";
$result_metode_pembayaran = mysqli_query($koneksi, $query_metode_pembayaran);

if (!$result_metode_pembayaran) {
    die("Gagal mengambil metode pembayaran.");
}

$total_belanja = $total_harga + $pengiriman['biaya'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
            background-color: #fff;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
            background-color: #fff;
            padding-top: 100px;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            height: 100px;
            padding: 0 5px;
            z-index: 1000;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
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

        /* Main Content Wrapper */
        .content-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Payment Header */
        .payment-header {
            padding: 0 0 24px 0;
            width: 100%;
        }

        .payment-header h1 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
        }

        /* Container Wrapper */
        .container-wrapper {
            display: flex;
            gap: 20px;
            width: 100%;
        }
        
        /* Container Kiri */
        .left-container {
            flex: 1.5;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 24px;
        }
        
        /* Container Kanan */
        .right-container {
            flex: 1;
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 24px;
            height: fit-content;
        }
        
        /* Metode Pembayaran */
        .payment-method {
            margin-bottom: 24px;
        }
        
        .method-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 12px;
            color: #000;
            font-weight: bold;
        }
        
        .method-option {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            /* background: #f9f9f9; */
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
            /* border: 1px solid #eee; */
        }
        
        .method-option:hover {
            background: #f0f0f0;
        }
        
        .method-option.active {
            background: #e8f4ff;
            border-color: #bbdefb;
        }
        
        /* Payment Method Icons */
        .method-icon {
            width: 30px;
            height: 30px;
            margin-right: 12px;
            object-fit: contain;
        }
        
        /* Ringkasan Pembayaran */
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        
        .summary-label {
            font-size: 15px;
            color: #666;
        }
        
        .summary-value {
            font-size: 15px;
            font-weight: 500;
        }
        
        .total-summary {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #eee;
        }
        
        .total-summary .summary-label {
            font-weight: 600;
        }
        
        .total-summary .summary-value {
            font-size: 18px;
            font-weight: 600;
            color: var(--purple);
        }
        
        /* Tombol Bayar */
        .pay-button {
            width: 100%;
            padding: 14px;
            background-color: var(--purple);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 24px;
        }
        
        .pay-button:hover {
            background-color: #49225b;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .container-wrapper {
                flex-direction: column;
            }
            
            .left-container,
            .right-container {
                flex: 1;
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }
            
            header {
                height: 80px;
            }
            
            .logo-wrapper img {
                width: 100px;
            }
            
            .payment-header h1 {
                font-size: 20px;
            }
        }

        @media (max-width: 576px) {
            .content-wrapper {
                padding: 10px;
            }
            
            .left-container,
            .right-container {
                padding: 16px;
            }
            
            .method-option {
                padding: 10px 12px;
            }
            
            .method-icon {
                width: 24px;
                height: 24px;
                margin-right: 8px;
            }
            
            .pay-button {
                padding: 12px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo-wrapper">
                    <img src="image/Navy Colorful fun Kids Book Store Logo1.png" alt="Logo Bukabuku" class="logo">
                </div>
            </nav>
        </div>
    </header>

    <div class="content-wrapper">
        <!-- Header -->
        <div class="payment-header">
            <h1>Metode Pembayaran</h1>
        </div>

        <div class="container-wrapper">
            <!-- Container Kiri -->
            <div class="left-container">
                <form action="proses_pembayaran.php" method="POST">
                    <?php if ($from_cart): ?>
                        <input type="hidden" name="from_cart" value="1">
                        <input type="hidden" name="total_harga" value="<?php echo $total_harga; ?>">
                        <input type="hidden" name="metode_pengiriman" value="<?php echo $metode_pengiriman; ?>">
                        <?php foreach ($selected_items as $id_buku): ?>
                            <input type="hidden" name="selected_items[]" value="<?php echo $id_buku; ?>">
                            <input type="hidden" name="keranjang[<?php echo $id_buku; ?>][jumlah]" 
                                value="<?php echo $_POST['keranjang'][$id_buku]['jumlah']; ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <input type="hidden" name="id_buku" value="<?php echo $id_buku; ?>">
                        <input type="hidden" name="jumlah" value="<?php echo $jumlah; ?>">
                        <input type="hidden" name="total_harga" value="<?php echo $total_harga; ?>">
                        <input type="hidden" name="metode_pengiriman" value="<?php echo $metode_pengiriman; ?>">
                    <?php endif; ?>
                    
                    <div class="payment-method">
                        <h3 class="method-title">E-Wallet</h3>
                        <?php 
                        mysqli_data_seek($result_metode_pembayaran, 0);
                        while ($metode = mysqli_fetch_assoc($result_metode_pembayaran)): 
                            if ($metode['jenis'] == 'E-Wallet'): ?>
                                <div class="method-option" data-method="<?php echo htmlspecialchars($metode['nama_metode']); ?>">
                                    <input type="radio" name="metode_pembayaran" id="<?php echo strtolower(str_replace(' ', '-', $metode['nama_metode'])); ?>" 
                                           value="<?php echo $metode['id_metodePembayaran']; ?>" style="display: none;">
                                    <img src="image/<?php echo htmlspecialchars($metode['logo']); ?>" alt="<?php echo htmlspecialchars($metode['nama_metode']); ?>" class="method-icon">
                                    <span><?php echo htmlspecialchars($metode['nama_metode']); ?></span>
                                </div>
                            <?php endif;
                        endwhile; ?>
                    </div>
                    
                    <div class="payment-method">
                        <h3 class="method-title">Virtual Account</h3>
                        <?php 
                        mysqli_data_seek($result_metode_pembayaran, 0);
                        while ($metode = mysqli_fetch_assoc($result_metode_pembayaran)): 
                            if ($metode['jenis'] == 'Virtual Account'): ?>
                                <div class="method-option" data-method="<?php echo htmlspecialchars($metode['nama_metode']); ?>">
                                    <input type="radio" name="metode_pembayaran" id="<?php echo strtolower(str_replace(' ', '-', $metode['nama_metode'])); ?>" 
                                           value="<?php echo $metode['id_metodePembayaran']; ?>" style="display: none;">
                                    <img src="image/<?php echo htmlspecialchars($metode['logo']); ?>" alt="<?php echo htmlspecialchars($metode['nama_metode']); ?>" class="method-icon">
                                    <span><?php echo htmlspecialchars($metode['nama_metode']); ?></span>
                                </div>
                            <?php endif;
                        endwhile; ?>
                    </div>
                </form>
            </div>
            
            <!-- Container Kanan - Ringkasan -->
            <div class="right-container">
                <h2 class="method-title">Ringkasan Pembayaran</h2>
                
                <div class="summary-item">
                    <span class="summary-label">Total Harga </span>
                    <span class="summary-value">Rp<?php echo number_format($total_harga, 0, ',', '.'); ?></span>
                </div>
                
                <div class="summary-item">
                    <span class="summary-label">Biaya Pengiriman</span>
                    <span class="summary-value">Rp<?php echo number_format($pengiriman['biaya'], 0, ',', '.'); ?></span>
                </div>
                
                <div class="summary-item total-summary">
                    <span class="summary-label">Total Belanja</span>
                    <span class="summary-value">Rp<?php echo number_format($total_belanja, 0, ',', '.'); ?></span>
                </div>
                
                <button class="pay-button" id="pay-button">Bayar Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pilih metode pembayaran
            const methodOptions = document.querySelectorAll('.method-option');
            
            methodOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Hapus active dari semua opsi
                    methodOptions.forEach(opt => opt.classList.remove('active'));
                    
                    // Tambahkan active ke opsi yang dipilih
                    this.classList.add('active');
                    
                    // Set radio button yang sesuai
                    const radioId = this.getAttribute('data-method').toLowerCase().replace(' ', '-');
                    document.getElementById(radioId).checked = true;
                });
            });
            
            // Tombol bayar
            const payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function() {
                const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked');
                
                if (!selectedMethod) {
                    alert('Silakan pilih metode pembayaran terlebih dahulu');
                    return;
                }
                
                // Submit form
                document.querySelector('form').submit();
            });
        });
    </script>
</body>
</html>