<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: Loginregister.php");
    exit();
}


// Pastikan data checkout ada
if (!isset($_SESSION['checkout_data'])) {
    header("Location: checkout.php");
    exit();
}

$checkout_data = $_SESSION['checkout_data'];

// Hitung total
$ongkir = isset($checkout_data['ongkir']['biaya']) ? $checkout_data['ongkir']['biaya'] : 0;
$total_harga = isset($checkout_data['total_harga']) ? $checkout_data['total_harga'] : 0;
$total_belanja = $total_harga + $ongkir;

// Ambil metode pembayaran
$query = mysqli_query($koneksi, "SELECT * FROM metode_pembayaran");
$metode = [];
while ($row = $query->fetch_assoc()) {
    $metode[$row['jenis']][] = $row;
}

// Pastikan data ongkir lengkap sebelum proses
if (!isset($_SESSION['checkout_data']['ongkir']) || !isset($_SESSION['checkout_data']['ongkir']['biaya'])) {
    header("Location: checkout.php");
    exit();
}

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
                <?php foreach ($metode as $tipe => $items): ?>
                <div class="payment-method">
                        <h3 class="method-title"><?=($tipe) ?></h3>
                        <?php foreach ($items as $item): ?>
                            <div class="method-option" data-method="<?=($item['nama_metode']) ?>">
                                <img src="image/<?=($item['logo']) ?>" alt="<?=($item['nama_metode']) ?>" class="method-icon">
                                <span><?=($item['nama_metode']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Container Kanan - Ringkasan -->
                <div class="right-container">
                    <h2 class="method-title">Ringkasan Pembayaran</h2>
                    
                    <div class="summary-item">
                        <span class="summary-label">Total Harga</span>
                        <span class="summary-value">Rp<?= number_format($total_harga, 0, ',', '.') ?></span>
                    </div>
                    
                    <div class="summary-item">
                        <span class="summary-label">Biaya Pengiriman</span>
                        <span class="summary-value">Rp<?= number_format($checkout_data['ongkir']['biaya'], 0, ',', '.') ?>
                        </span>
                    </div>
                    
                    <div class="summary-item total-summary">
                        <span class="summary-label">Total Belanja</span>
                        <span class="summary-value">Rp<?= number_format($total_belanja, 0, ',', '.') ?></span>
                    </div>
                    <form action="proses_metode.php" method="post" id="paymentForm">
                        <input type="hidden" name="metode_pembayaran" id="selectedMethod" value="">
                        <input type="hidden" name="total_pembayaran" value="<?= $total_belanja ?>">
                        <button type="submit" class="pay-button">Bayar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pilih metode pembayaran
            const methodOptions = document.querySelectorAll('.method-option');
            const selectedMethodInput = document.getElementById('selectedMethod');
            const paymentForm = document.getElementById('paymentForm');
            
            methodOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Hapus active dari semua opsi
                    methodOptions.forEach(opt => opt.classList.remove('active'));
                    
                    // Tambahkan active ke opsi yang dipilih
                    this.classList.add('active');
                   selectedMethodInput.value = this.getAttribute('data-method');
                });
            });
            
            // Validasi sebelum submit
            paymentForm.addEventListener('submit', function(e) {
                if (selectedMethodInput.value === '') {
                    e.preventDefault();
                    alert('Silakan pilih metode pembayaran terlebih dahulu');
                }
            });
        });
    </script>
</body>
</html>