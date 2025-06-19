<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_users'])) {
    header("Location: LoginRegister.php");
    exit();
}

// Ambil data buku dari database berdasarkan parameter GET
$id_buku = isset($_GET['id_buku']) ? (int)$_GET['id_buku'] : 0;
$jumlah = isset($_GET['jumlah']) ? (int)$_GET['jumlah'] : 1;

// Query data buku
$query_buku = "SELECT * FROM buku WHERE id_buku = $id_buku";
$result_buku = mysqli_query($koneksi, $query_buku);
$buku = mysqli_fetch_assoc($result_buku);

// Hitung total harga
$total_harga = $buku['harga'] * $jumlah;

// Query alamat pengguna
$id_users = $_SESSION['id_users'];
$query_alamat = "SELECT * FROM alamat WHERE id_users = $id_users";
$result_alamat = mysqli_query($koneksi, $query_alamat);
$alamat = mysqli_fetch_assoc($result_alamat);

// Query metode pengiriman
$query_pengiriman = "SELECT * FROM metode_pengiriman";
$result_pengiriman = mysqli_query($koneksi, $query_pengiriman);
$metode_pengiriman = [];
while ($row = mysqli_fetch_assoc($result_pengiriman)) {
    $metode_pengiriman[] = $row;
}

// Set default shipping method (first one in the list)
$default_shipping = $metode_pengiriman[0] ?? null;
$biaya_pengiriman = $default_shipping['biaya'] ?? 0;
$total_belanja = $total_harga + $biaya_pengiriman;
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
            --border: 1px solid #e0e0e0;
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
            padding-top: 120px;
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
        
        .checkout-content {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #000;
            margin-left: 20px;
            max-width: 1200px;
            margin: 0 auto 20px;
            padding: 0 20px;
        }

        h2 {
            font-size: 20px;
            margin: 20px 0 15px;
        }

        .order-section {
            width: 60%;
            margin: 0;
            padding: 20px;
        }
        
        .order-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-title {
            font-weight: bold;
            font-size: 16px;
        }

        .item-price {
            font-weight: bold;
            color: #595959;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 15px 0;
        }

        .summary-section {
            width: 35%;
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            height: fit-content;
            position: sticky;
            top: 140px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total-row {
            font-weight: bold;
            font-size: 18px;
            margin-top: 15px;
            padding-top: 15px;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            background-color: var(--purple);
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 15px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .checkout-btn:hover {
            background-color: #5a2d6a;
        }

        .checkout-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
                
        .address-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            width: 60%;
        }

        .add-address-btn {
            display: inline-block;
            background-color: #fff;
            color: #595959;
            padding: 10px 15px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            font-size: 13px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            cursor: pointer;
        }

        .add-address-btn:hover {
            background-color: #f5f5f5;
        }

        .address-card {
            display: none;
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .address-card.active {
            display: block;
        }

        .address-form {
            display: none;
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .address-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-secondary {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }

        .btn-primary {
            background-color: var(--purple);
            color: white;
            border: 1px solid var(--purple);
        }

        .address-info {
            margin-bottom: 10px;
            font-size: 13px
        }

        .address-actions {
            display: flex;
            gap: 10px;
        }

        .address-action-btn {
            background: none;
            border: none;
            color: var(--purple);
            text-decoration: underline;
            cursor: pointer;
            font-size: 13px;
        }

        .shipping-methods {
            margin-top: 20px;
        }

        .shipping-method {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .shipping-method.selected {
            border-color: var(--purple);
            background-color: #f5f0f7;
        }

        .shipping-method input {
            margin-right: 10px;
        }

        .shipping-method-details {
            display: none;
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .shipping-method-details.active {
            display: block;
        }

        .shipping-estimate {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .jne-service-option {
            display: flex;
            align-items: center;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .jne-service-option input {
            margin-right: 10px;
        }

        .jne-service-option label {
            display: flex;
            justify-content: space-between;
            width: 100%;
            cursor: pointer;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .error-message.active {
            display: block;
        }

        @media (max-width: 768px) {
            .checkout-content {
                flex-direction: column;
            }
            
            .order-section,
            .summary-section,
            .address-section {
                width: 100%;
            }
            
            .summary-section {
                position: static;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo-wrapper">
                    <img src="image/Navy Colorful fun Kids Book Store Logo.png" alt="Logo Bukabuku" class="logo">
                </div>
            </nav>
        </div>
    </header>
    
    <h1>Checkout</h1>
    
    <div class="checkout-content">
        <div class="order-section">
            <div class="order-card">
                <h2>Pesanan 1</h2>
                <div class="order-item">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="image/<?php echo htmlspecialchars($buku['gambar']); ?>" alt="Book" style="width: 50px; height: 70px; object-fit: cover; border-radius: 5px;">
                        <div>
                            <div class="item-title"><?php echo htmlspecialchars($buku['judul']); ?></div>
                            <div><?php echo $jumlah; ?> barang</div>
                        </div>
                    </div>
                    <div class="item-price">Rp<?php echo number_format($buku['harga'], 0, ',', '.'); ?></div>
                </div>
                
                <div class="divider"></div>
                
                <div class="order-item total-row">
                    <div>Total Pesanan</div>
                    <div class="item-price">Rp<?php echo number_format($total_harga, 0, ',', '.'); ?></div>
                </div>
            </div>
            
            <div class="address-section">
                <h2>Alamat Pengiriman</h2>
                <?php if (!$alamat): ?>
                <div id="no-address-message">Belum ada alamat terdaftar</div>
                <button id="add-address-btn" class="add-address-btn">Buat Alamat</button>
                
                <div id="address-form" class="address-form">
                    <form id="address-data-form">
                        <input type="hidden" name="id_users" value="<?= $id_users ?>">
                        
                        <div class="form-group">
                            <label for="name">Nama Penerima</label>
                            <input type="text" id="name" name="nama_penerima" placeholder="Nama lengkap">
                            <div class="error-message" id="name-error">Nama penerima harus diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="tel" id="phone" name="no_telepon" placeholder="081234567890">
                            <div class="error-message" id="phone-error">Nomor telepon harus diisi dan valid (minimal 10 digit)</div>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat Lengkap</label>
                            <textarea id="address" name="alamat_lengkap" rows="3" placeholder="Jl. Contoh No. 123" required></textarea>
                            <div class="error-message" id="address-error">Alamat lengkap harus diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="city">Kota/Kabupaten</label>
                            <input type="text" id="city" name="kabupaten" placeholder="Kota Contoh">
                            <div class="error-message" id="city-error">Kota/Kabupaten harus diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="province">Provinsi</label>
                            <input type="text" id="province" name="provinsi" placeholder="Provinsi Contoh">
                            <div class="error-message" id="province-error">Provinsi harus diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="postal-code">Kode Pos</label>
                            <input type="text" id="postal-code" name="kode_pos" placeholder="12345">
                            <div class="error-message" id="postal-code-error">Kode pos harus diisi (5 digit)</div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" id="cancel-address-btn">Batal</button>
                            <button type="button" class="btn btn-primary" id="save-address-btn">Simpan Alamat</button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div id="address-card" class="address-card active">
                    <div class="address-info">
                        <p><strong id="address-name"><?=$alamat['nama_penerima']?></strong></p>
                        <p id="address-phone"><?=$alamat['no_telepon']?></p>
                        <p id="address-full"><?=$alamat['alamat_lengkap']?>, 
                        <?=$alamat['kabupaten']?>, <?=$alamat['provinsi']?>, 
                        <?=$alamat['kode_pos']?></p>
                    </div>
                    <div class="address-actions">
                        <button class="address-action-btn" id="edit-address-btn">Ubah</button>
                        <button class="address-action-btn" id="delete-address-btn">Hapus</button>
                    </div>
                </div>

                <div id="address-form" class="address-form">
                    <form id="address-data-form">
                        <input type="hidden" name="id_users" value="<?= $id_users ?>">
                        <input type="hidden" name="id_alamat" value="<?= $alamat['id_alamat'] ?>">

                        <div class="form-group">
                            <label for="name">Nama Penerima</label>
                            <input type="text" id="name" name="nama_penerima" placeholder="Nama lengkap" value="<?= $alamat['nama_penerima'] ?>">
                            <div class="error-message" id="name-error">Nama penerima harus diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="tel" id="phone" name="no_telepon" placeholder="081234567890" value="<?= $alamat['no_telepon'] ?>">
                            <div class="error-message" id="phone-error">Nomor telepon harus diisi dan valid (minimal 10 digit)</div>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat Lengkap</label>
                            <textarea id="address" name="alamat_lengkap" rows="3" placeholder="Jl. Contoh No. 123" required><?= $alamat['alamat_lengkap'] ?></textarea>
                            <div class="error-message" id="address-error">Alamat lengkap harus diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="city">Kota/Kabupaten</label>
                            <input type="text" id="city" name="kabupaten" placeholder="Kota Contoh" value="<?= $alamat['kabupaten'] ?>">
                            <div class="error-message" id="city-error">Kota/Kabupaten harus diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="province">Provinsi</label>
                            <input type="text" id="province" name="provinsi" placeholder="Provinsi Contoh" value="<?= $alamat['provinsi'] ?>">
                            <div class="error-message" id="province-error">Provinsi harus diisi</div>
                        </div>
                        <div class="form-group">
                            <label for="postal-code">Kode Pos</label>
                            <input type="text" id="postal-code" name="kode_pos" placeholder="12345" value="<?= $alamat['kode_pos'] ?>">
                            <div class="error-message" id="postal-code-error">Kode pos harus diisi (5 digit)</div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" id="cancel-address-btn">Batal</button>
                            <button type="button" class="btn btn-primary" id="save-address-btn">Simpan Alamat</button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="shipping-methods">
                <h2>Metode Pengiriman</h2>
                <form id="shipping-form" action="metode_pembayaran.php" method="POST">
                    <input type="hidden" name="id_buku" value="<?php echo $id_buku; ?>">
                    <input type="hidden" name="jumlah" value="<?php echo $jumlah; ?>">
                    
                    <?php foreach ($metode_pengiriman as $pengiriman): ?>
                        <div class="shipping-method <?php echo $pengiriman['id_metodePengiriman'] == $default_shipping['id_metodePengiriman'] ? 'selected' : ''; ?>" 
                             id="<?php echo strtolower(str_replace(' ', '-', $pengiriman['nama_metode'])); ?>-shipping"
                             data-shipping-id="<?php echo $pengiriman['id_metodePengiriman']; ?>"
                             data-shipping-cost="<?php echo $pengiriman['biaya']; ?>">
                            <input type="radio" name="metode_pengiriman" id="<?php echo strtolower(str_replace(' ', '-', $pengiriman['nama_metode'])); ?>-radio" 
                                   value="<?php echo $pengiriman['id_metodePengiriman']; ?>" 
                                   <?php echo $pengiriman['id_metodePengiriman'] == $default_shipping['id_metodePengiriman'] ? 'checked' : ''; ?>>
                            <label for="<?php echo strtolower(str_replace(' ', '-', $pengiriman['nama_metode'])); ?>-radio">
                                <?php echo htmlspecialchars($pengiriman['nama_metode']); ?>
                                <span class="shipping-price">(Rp<?php echo number_format($pengiriman['biaya'], 0, ',', '.'); ?>)</span>
                            </label>
                        </div>
                        
                        <div id="<?php echo strtolower(str_replace(' ', '-', $pengiriman['nama_metode'])); ?>-shipping-details" 
                             class="shipping-method-details <?php echo $pengiriman['id_metodePengiriman'] == $default_shipping['id_metodePengiriman'] ? 'active' : ''; ?>">
                            <h3><?php echo htmlspecialchars($pengiriman['nama_metode']); ?></h3>
                            <p><?php echo htmlspecialchars($pengiriman['deskripsi']); ?></p>
                            <p>Estimasi pengiriman: <?php echo htmlspecialchars($pengiriman['estimasi']); ?></p>
                            <p>Biaya pengiriman: Rp<?php echo number_format($pengiriman['biaya'], 0, ',', '.'); ?></p>
                            <p>Dapat dilacak melalui nomor resi yang akan dikirim via SMS/email</p>
                        </div>
                    <?php endforeach; ?>
                    
                    <input type="hidden" name="total_harga" value="<?php echo $total_harga; ?>">
                    <input type="hidden" id="shipping-fee-input" name="biaya_pengiriman" value="<?php echo $biaya_pengiriman; ?>">
                    <input type="hidden" id="total-belanja-input" name="total_belanja" value="<?php echo $total_belanja; ?>">
                </form>
            </div>
        </div>
        
        <div class="summary-section">
            <h2>Ringkasan</h2>
            <div class="summary-row">
                <div>Total Harga (<?php echo $jumlah; ?> Barang)</div>
                <div>Rp<?php echo number_format($total_harga, 0, ',', '.'); ?></div>
            </div>
            <div class="summary-row">
                <div>Biaya Pengiriman</div>
                <div id="shipping-cost">Rp<?php echo number_format($biaya_pengiriman, 0, ',', '.'); ?></div>
            </div>
            
            <div class="divider"></div>
            
            <div class="summary-row total-row">
                <div>Total Belanja</div>
                <div id="total-price">Rp<?php echo number_format($total_belanja, 0, ',', '.'); ?></div>
            </div>
            <button class="checkout-btn" id="checkout-btn" <?php echo !$alamat ? 'disabled' : ''; ?>>Lanjut Pembayaran</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // DOM Elements
            const addAddressBtn = document.getElementById('add-address-btn');
            const addressForm = document.getElementById('address-form');
            const addressCard = document.getElementById('address-card');
            const noAddressMessage = document.getElementById('no-address-message');
            const saveAddressBtn = document.getElementById('save-address-btn');
            const cancelAddressBtn = document.getElementById('cancel-address-btn');
            const editAddressBtn = document.getElementById('edit-address-btn');
            const deleteAddressBtn = document.getElementById('delete-address-btn');
            const checkoutBtn = document.getElementById('checkout-btn');
            
            // Form fields and error messages
            const nameInput = document.getElementById('name');
            const phoneInput = document.getElementById('phone');
            const addressInput = document.getElementById('address');
            const cityInput = document.getElementById('city');
            const provinceInput = document.getElementById('province');
            const postalCodeInput = document.getElementById('postal-code');
            
            const nameError = document.getElementById('name-error');
            const phoneError = document.getElementById('phone-error');
            const addressError = document.getElementById('address-error');
            const cityError = document.getElementById('city-error');
            const provinceError = document.getElementById('province-error');
            const postalCodeError = document.getElementById('postal-code-error');
            
            // Shipping method elements
            const shippingMethods = document.querySelectorAll('.shipping-method');
            const shippingCostElement = document.getElementById('shipping-cost');
            const totalPriceElement = document.getElementById('total-price');
            const shippingFeeInput = document.getElementById('shipping-fee-input');
            const totalBelanjaInput = document.getElementById('total-belanja-input');
            
            // Get the item price and quantity from PHP
            const itemPrice = <?php echo $total_harga; ?>;
            const quantity = <?php echo $jumlah; ?>;
            
            // Initialize with default values
            let shippingCost = <?php echo $biaya_pengiriman; ?>;
            let totalPrice = <?php echo $total_belanja; ?>;
            
            // Event Listeners
            if (addAddressBtn) addAddressBtn.addEventListener('click', showAddressForm);
            if (saveAddressBtn) saveAddressBtn.addEventListener('click', saveAddress);
            if (cancelAddressBtn) cancelAddressBtn.addEventListener('click', cancelAddress);
            if (editAddressBtn) editAddressBtn.addEventListener('click', editAddress);
            if (deleteAddressBtn) deleteAddressBtn.addEventListener('click', deleteAddress);
            
            // Form validation
            if (nameInput) nameInput.addEventListener('input', () => validateField(nameInput, nameError, 'Nama penerima harus diisi'));
            if (phoneInput) phoneInput.addEventListener('input', () => validatePhone(phoneInput, phoneError));
            if (addressInput) addressInput.addEventListener('input', () => validateField(addressInput, addressError, 'Alamat lengkap harus diisi'));
            if (cityInput) cityInput.addEventListener('input', () => validateField(cityInput, cityError, 'Kota/Kabupaten harus diisi'));
            if (provinceInput) provinceInput.addEventListener('input', () => validateField(provinceInput, provinceError, 'Provinsi harus diisi'));
            if (postalCodeInput) postalCodeInput.addEventListener('input', () => validatePostalCode(postalCodeInput, postalCodeError));
            
            // Shipping method selection
            shippingMethods.forEach(method => {
                method.addEventListener('click', function() {
                    // Update selected style
                    shippingMethods.forEach(m => m.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    // Hide all shipping details
                    document.querySelectorAll('.shipping-method-details').forEach(detail => {
                        detail.classList.remove('active');
                    });
                    
                    // Show corresponding details
                    const methodId = this.id;
                    const detailsElement = document.getElementById(`${methodId}-details`);
                    if (detailsElement) {
                        detailsElement.classList.add('active');
                    }
                    
                    // Update shipping cost
                    const newShippingCost = parseFloat(this.dataset.shippingCost);
                    updateShippingCost(newShippingCost);
                });
            });
            
            // Checkout button click
            checkoutBtn.addEventListener('click', function() {
                document.getElementById('shipping-form').submit();
            });
            
            // Functions
            function showAddressForm() {
                if (addressForm) addressForm.classList.add('active');
                if (addAddressBtn) addAddressBtn.style.display = 'none';
                if (noAddressMessage) noAddressMessage.style.display = 'none';
                
                // If editing, populate form with existing data
                if (addressCard && addressCard.classList.contains('active')) {
                    // Data is already populated by PHP
                } else {
                    // Clear form for new address
                    if (nameInput) nameInput.value = '';
                    if (phoneInput) phoneInput.value = '';
                    if (addressInput) addressInput.value = '';
                    if (cityInput) cityInput.value = '';
                    if (provinceInput) provinceInput.value = '';
                    if (postalCodeInput) postalCodeInput.value = '';
                    
                    // Hide all error messages
                    document.querySelectorAll('.error-message').forEach(el => {
                        el.classList.remove('active');
                    });
                }
            }
            
            function hideAddressForm() {
                if (addressForm) addressForm.classList.remove('active');
                if (!addressCard || !addressCard.classList.contains('active')) {
                    if (addAddressBtn) addAddressBtn.style.display = 'inline-block';
                    if (noAddressMessage) noAddressMessage.style.display = 'block';
                }
            }
            
            function validateField(input, errorElement, message) {
                if (!input.value.trim()) {
                    errorElement.textContent = message;
                    errorElement.classList.add('active');
                    return false;
                } else {
                    errorElement.classList.remove('active');
                    return true;
                }
            }
            
            function validatePhone(input, errorElement) {
                const phoneRegex = /^[0-9]{10,13}$/;
                if (!input.value.trim()) {
                    errorElement.textContent = 'Nomor telepon harus diisi';
                    errorElement.classList.add('active');
                    return false;
                } else if (!phoneRegex.test(input.value)) {
                    errorElement.textContent = 'Nomor telepon harus valid (10-13 digit)';
                    errorElement.classList.add('active');
                    return false;
                } else {
                    errorElement.classList.remove('active');
                    return true;
                }
            }
            
            function validatePostalCode(input, errorElement) {
                const postalRegex = /^[0-9]{5}$/;
                if (!input.value.trim()) {
                    errorElement.textContent = 'Kode pos harus diisi';
                    errorElement.classList.add('active');
                    return false;
                } else if (!postalRegex.test(input.value)) {
                    errorElement.textContent = 'Kode pos harus valid (5 digit)';
                    errorElement.classList.add('active');
                    return false;
                } else {
                    errorElement.classList.remove('active');
                    return true;
                }
            }
            
            function validateAddressForm() {
                const isNameValid = validateField(nameInput, nameError, 'Nama penerima harus diisi');
                const isPhoneValid = validatePhone(phoneInput, phoneError);
                const isAddressValid = validateField(addressInput, addressError, 'Alamat lengkap harus diisi');
                const isCityValid = validateField(cityInput, cityError, 'Kota/Kabupaten harus diisi');
                const isProvinceValid = validateField(provinceInput, provinceError, 'Provinsi harus diisi');
                const isPostalCodeValid = validatePostalCode(postalCodeInput, postalCodeError);
                
                return isNameValid && isPhoneValid && isAddressValid && 
                       isCityValid && isProvinceValid && isPostalCodeValid;
            }
            
            function saveAddress() {
                if (!validateAddressForm()) {
                    return;
                }
                
                // Prepare form data
                const formData = new FormData(document.getElementById('address-data-form'));
                
                // Send AJAX request to save address
                fetch('save_address.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show updated address
                        window.location.reload();
                    } else {
                        alert('Gagal menyimpan alamat: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan alamat');
                });
            }
            
            function cancelAddress() {
                hideAddressForm();
            }
            
            function editAddress() {
                showAddressForm();
            }
            
            function deleteAddress() {
                if (confirm('Apakah Anda yakin ingin menghapus alamat ini?')) {
                    const idAlamat = document.querySelector('#address-form input[name="id_alamat"]').value;
                    
                    // Send AJAX request to delete address
                    fetch('delete_address.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id_alamat=${idAlamat}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to show changes
                            window.location.reload();
                        } else {
                            alert('Gagal menghapus alamat: ' + (data.message || 'Terjadi kesalahan'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus alamat');
                    });
                }
            }
            
            function updateShippingCost(cost) {
                shippingCost = parseFloat(cost);
                totalPrice = itemPrice + shippingCost;
                
                // Update display
                shippingCostElement.textContent = formatCurrency(shippingCost);
                totalPriceElement.textContent = formatCurrency(totalPrice);
                
                // Update hidden inputs
                shippingFeeInput.value = shippingCost;
                totalBelanjaInput.value = totalPrice;
            }
            
            function formatCurrency(amount) {
                return 'Rp' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        });
    </script>
</body>
</html>