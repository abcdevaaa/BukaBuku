<?php
session_start();
include "koneksi.php";

// Periksa apakah user sudah login
if (!isset($_SESSION['id_users'])) {
    header("Location: LoginRegister.php");
    exit();
}


$checkedItems = [];
// Hanya memproses item yang dicentang
if (isset($_POST['keranjang']) && is_array($_POST['keranjang'])) {
    $checkedItems = $_POST['keranjang'];
}

// Jika tidak ada item yang dipilih, redirect kembali
if (empty($checkedItems)) {
    header("Location: keranjang.php?error=no_items_selected");
    exit();
}

$id_users = $_SESSION['id_users'];

// Ambil data alamat pengiriman dari database
$queryAlamat = mysqli_query($koneksi, "SELECT * FROM alamat WHERE id_users = $id_users limit 1");
$alamat = mysqli_fetch_assoc($queryAlamat);

// Konversi array checkedItems ke string untuk query SQL
$checkedItemsStr = implode(",", array_map('intval', $checkedItems));

// if (empty($checkedItemsStr)) {
//     die("Tidak ada item yang dipilih untuk checkout");
// }

// Ambil item yang dipilih dari keranjang
$queryKeranjang = mysqli_query($koneksi, 
    "SELECT keranjang.jumlah, buku.* 
     FROM keranjang  
     JOIN buku ON keranjang.id_buku = buku.id_buku 
     WHERE keranjang.id_users = $id_users
     AND keranjang.id_buku IN ($checkedItemsStr)");

$total_harga = 0;
$keranjang = [];

while ($buku = mysqli_fetch_assoc($queryKeranjang)) {
    $subtotal = $buku['harga'] * $buku['jumlah'];
    $total_harga += $subtotal;
    $keranjang[] = $buku;
}

// Ambil semua metode pengiriman yang tersedia
$queryMetode = mysqli_query($koneksi, "SELECT * FROM metode_pengiriman");
$ongkir = []; 
$firstMethod = null;

while ($metode = mysqli_fetch_assoc($queryMetode)) {
    $ongkir[] = $metode;
    if (!$firstMethod) {
        $firstMethod = $metode; // Simpan metode pertama sebagai default
    }
}

// Gunakan metode pertama sebagai default jika tidak ada yang dipilih
$selectedShipping = $firstMethod;
$selectedShippingId = $_POST['selected_shipping'] ?? $firstMethod['id_metodePengiriman'];

// Cari metode yang dipilih
foreach ($ongkir as $metode) {
    if ($metode['id_metodePengiriman'] == $selectedShippingId) {
        $selectedShipping = $metode;
        break;
    }
}

// menyimpan data penting ke session untuk digunakan di halaman berikutnya
$_SESSION['checkout_data'] = [
    'items' => $checkedItems,
    'total_harga' => $total_harga,
    'ongkir' => $ongkir,
    'alamat' => $alamat,
];

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
                <h2>Pesanan</h2>
                <?php foreach ($keranjang as $buku): ?>
                <div class="order-item">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="image/<?= $buku['gambar'] ?>" alt="<?= $buku['judul'] ?>" style="width: 50px; height: 70px; object-fit: cover; border-radius: 5px;">
                        <div>
                            <div class="item-title"><?= $buku['judul'] ?></div>
                            <div><?= $buku['jumlah'] ?>barang</div>
                        </div>
                    </div>
                    <div class="item-price">Rp<?= number_format($buku['harga'], 0, ',', '.')?></div>
                </div>
                <?php endforeach; ?>
                <div class="divider"></div>
                
                <div class="order-item total-row">
                    <div>Total Pesanan</div>
                    <div class="item-price">Rp<?= number_format($total_harga, 0, ',', '.') ?></div>
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
                <?php foreach ($ongkir as $index => $metode): 
                    $isSelected = $metode['id_metodePengiriman'] == $selectedShippingId;
                    $clean_id = 'metode-' . $metode['id_metodePengiriman'];
                ?>
                    <div class="shipping-method <?= $isSelected ? 'selected' : '' ?>" 
                        id="<?= $clean_id ?>"
                        onclick="selectShippingMethod('<?= $clean_id ?>', <?= $metode['biaya'] ?>, <?= $metode['id_metodePengiriman'] ?>)">
                        <input type="radio" name="shipping-method" id="<?= $clean_id ?>-radio" 
                            <?= $isSelected ? 'checked' : '' ?> 
                            value="<?= $metode['id_metodePengiriman'] ?>"
                            data-cost="<?= $metode['biaya'] ?>">
                        <label for="<?= $clean_id ?>-radio">
                            <?= htmlspecialchars($metode['nama_metode']) ?> 
                            (Rp<?= number_format($metode['biaya'], 0, ',', '.') ?>)
                        </label>
                    </div>

                    <div id="<?= $clean_id ?>-details" class="shipping-method-details <?= $isSelected ? 'active' : '' ?>">
                        <h3><?= htmlspecialchars($metode['nama_metode']) ?></h3>
                        <p><?= htmlspecialchars($metode['deskripsi']) ?></p>
                        <p>Estimasi: <?= htmlspecialchars($metode['estimasi']) ?></p>
                        <p>Biaya: Rp<?= number_format($metode['biaya'], 0, ',', '.') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>


            <div class="summary-section">
                <h2>Ringkasan</h2>
                <div class="summary-row">
                    <div>Total Harga</div>
                    <div>Rp<?= number_format($total_harga, 0, ',', '.') ?></div>
                </div>
                <div class="summary-row">
                    <div>Biaya Pengiriman</div>
                    <div id="shipping-cost">  Rp<?= number_format($selectedShipping['biaya'], 0, ',', '.') ?>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="summary-row total-row">
                    <div>Total Belanja</div>
                    <div id="total-price">Rp<?= number_format($total_harga + $selectedShipping['biaya'], 0, ',', '.') ?></div>
                </div>
                <form action="metode.php" method="post" id="checkout-form">
                    <input type="hidden" name="selected_shipping" id="selected-shipping" value="<?= $selectedShipping['id_metodePengiriman'] ?>">
                    <input type="hidden" name="shipping_cost" id="shipping-cost-value" value="<?= $selectedShipping['biaya'] ?>">
                    <input type="hidden" name="total_harga" value="<?= $total_harga ?>">
                    <input type="hidden" name="alamat_id" value="<?= $alamat['id_alamat'] ?? '' ?>">
                    <?php foreach($checkedItems as $item): ?>
                        <input type="hidden" name="items[]" value="<?= $item ?>">
                    <?php endforeach; ?>
                    <button type="submit" class="checkout-btn" id="checkout-btn" <?= !isset($alamat) ? 'disabled' : '' ?>>Lanjut Pembayaran</button>
                </form>
            </div>
                
         </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // ==================== DOM ELEMENTS ====================
        const addAddressBtn = document.getElementById('add-address-btn');
        const editAddressBtn = document.getElementById('edit-address-btn');
        const deleteAddressBtn = document.getElementById('delete-address-btn');
        const saveAddressBtn = document.getElementById('save-address-btn');
        const cancelAddressBtn = document.getElementById('cancel-address-btn');
        
        const addressForm = document.getElementById('address-form');
        const addressCard = document.getElementById('address-card');
        const noAddressMessage = document.getElementById('no-address-message');
        
        // Form fields
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        const addressInput = document.getElementById('address');
        const cityInput = document.getElementById('city');
        const provinceInput = document.getElementById('province');
        const postalCodeInput = document.getElementById('postal-code');
        
        // Error messages
        const nameError = document.getElementById('name-error');
        const phoneError = document.getElementById('phone-error');
        const addressError = document.getElementById('address-error');
        const cityError = document.getElementById('city-error');
        const provinceError = document.getElementById('province-error');
        const postalCodeError = document.getElementById('postal-code-error');

        // ==================== INITIAL STATE ====================
        const hasAddress = <?= isset($alamat) ? 'true' : 'false' ?>;
        
        // Sembunyikan form alamat saat pertama kali load
        if (addressForm) addressForm.style.display = 'none';
        updateUIState();

        // ==================== EVENT LISTENERS ====================
        if (addAddressBtn) {
            addAddressBtn.addEventListener('click', function() {
                noAddressMessage.style.display = 'none';
                addAddressBtn.style.display = 'none';
                addressForm.style.display = 'block';
            });
        }

        if (editAddressBtn) {
            editAddressBtn.addEventListener('click', function() {
                addressCard.style.display = 'none';
                addressForm.style.display = 'block';
            });
        }

        if (cancelAddressBtn) {
            cancelAddressBtn.addEventListener('click', function() {
                addressForm.style.display = 'none';
                if (hasAddress) {
                    addressCard.style.display = 'block';
                } else {
                    noAddressMessage.style.display = 'block';
                    addAddressBtn.style.display = 'inline-block';
                }
            });
        }

        if (deleteAddressBtn) {
            deleteAddressBtn.addEventListener('click', deleteAddress);
        }

        if (saveAddressBtn) {
            saveAddressBtn.addEventListener('click', saveAddress);
        }
        
        // validasi
        if (nameInput) nameInput.addEventListener('input', () => validateField(nameInput, nameError, 'Nama penerima harus diisi'));
        if (phoneInput) phoneInput.addEventListener('input', validatePhone);
        if (addressInput) addressInput.addEventListener('input', () => validateField(addressInput, addressError, 'Alamat lengkap harus diisi'));
        if (cityInput) cityInput.addEventListener('input', () => validateField(cityInput, cityError, 'Kota/Kabupaten harus diisi'));
        if (provinceInput) provinceInput.addEventListener('input', () => validateField(provinceInput, provinceError, 'Provinsi harus diisi'));
        if (postalCodeInput) postalCodeInput.addEventListener('input', validatePostalCode);

        // ==================== VALIDATION FUNCTIONS ====================
        function validateField(input, errorElement, message) {
            if (!input.value.trim()) {
                errorElement.textContent = message;
                errorElement.classList.add('active');
                return false;
            }
            errorElement.classList.remove('active');
            return true;
        }

        function validatePhone() {
            const phoneRegex = /^[0-9]{10,13}$/;
            if (!phoneInput.value.trim()) {
                phoneError.textContent = 'Nomor telepon harus diisi';
                phoneError.classList.add('active');
                return false;
            } else if (!phoneRegex.test(phoneInput.value)) {
                phoneError.textContent = 'Nomor telepon harus 10-13 digit angka';
                phoneError.classList.add('active');
                return false;
            }
            phoneError.classList.remove('active');
            return true;
        }

        function validatePostalCode() {
            const postalRegex = /^[0-9]{5}$/;
            if (!postalCodeInput.value.trim()) {
                postalCodeError.textContent = 'Kode pos harus diisi';
                postalCodeError.classList.add('active');
                return false;
            } else if (!postalRegex.test(postalCodeInput.value)) {
                postalCodeError.textContent = 'Kode pos harus 5 digit angka';
                postalCodeError.classList.add('active');
                return false;
            }
            postalCodeError.classList.remove('active');
            return true;
        }

        function validateAllFields() {
            const isValidName = validateField(nameInput, nameError, 'Nama penerima harus diisi');
            const isValidPhone = validatePhone();
            const isValidAddress = validateField(addressInput, addressError, 'Alamat lengkap harus diisi');
            const isValidCity = validateField(cityInput, cityError, 'Kota/Kabupaten harus diisi');
            const isValidProvince = validateField(provinceInput, provinceError, 'Provinsi harus diisi');
            const isValidPostalCode = validatePostalCode();

            return isValidName && isValidPhone && isValidAddress && 
                   isValidCity && isValidProvince && isValidPostalCode;
        }

        async function saveAddress() {
            if (!validateAllFields()) return;

            const formData = new FormData(document.getElementById('address-data-form'));
            const addressData = {
                id_users: formData.get('id_users'),
                nama_penerima: formData.get('nama_penerima'),
                no_telepon: formData.get('no_telepon'),
                alamat_lengkap: formData.get('alamat_lengkap'),
                kabupaten: formData.get('kabupaten'),
                provinsi: formData.get('provinsi'),
                kode_pos: formData.get('kode_pos')
            };

            // Jika edit alamat, tambahkan id_alamat
            if (hasAddress && formData.get('id_alamat')) {
                addressData.id_alamat = formData.get('id_alamat');
            }

            try {
                saveAddressBtn.disabled = true;
                saveAddressBtn.textContent = 'Menyimpan...';

                const endpoint = hasAddress ? 'update_alamat.php' : 'buat_alamat.php';
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(addressData)
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Gagal menyimpan alamat');

                if (data.success) {
                    window.location.reload(); // Refresh untuk tampilkan data terbaru
                } else {
                    throw new Error(data.message || 'Gagal menyimpan alamat');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(`Error: ${error.message}`);
            } finally {
                saveAddressBtn.disabled = false;
                saveAddressBtn.textContent = 'Simpan';
            }
        }

        async function deleteAddress() {
            if (!confirm('Apakah Anda yakin ingin menghapus alamat ini?')) return;

            try {
                deleteAddressBtn.disabled = true;
                deleteAddressBtn.textContent = 'Menghapus...';

                const response = await fetch('hapus_alamat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_alamat: <?= isset($alamat['id_alamat']) ? $alamat['id_alamat'] : 'null' ?>
                    })
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Gagal menghapus alamat');

                if (data.success) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Gagal menghapus alamat');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(`Error: ${error.message}`);
            } finally {
                deleteAddressBtn.disabled = false;
                deleteAddressBtn.textContent = 'Hapus';
            }
        }

        function updateUIState() {
            // Fungsi ini menyesuaikan tampilan berdasarkan apakah ada alamat atau tidak
            if (hasAddress) {
                if (addressCard) addressCard.style.display = 'block';
                if (noAddressMessage) noAddressMessage.style.display = 'none';
                if (addAddressBtn) addAddressBtn.style.display = 'none';
            } else {
                if (addressCard) addressCard.style.display = 'none';
                if (noAddressMessage) noAddressMessage.style.display = 'block';
                if (addAddressBtn) addAddressBtn.style.display = 'inline-block';
            }
            if (addressForm) addressForm.style.display = 'none';
        }
    });

        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="shipping-method"]');
            
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Hapus class selected dari semua method
                    document.querySelectorAll('.shipping-method').forEach(method => {
                        method.classList.remove('selected');
                    });
                    
                    // Tambahkan class selected ke method yang dipilih
                    this.closest('.shipping-method').classList.add('selected');
                    
                    // Sembunyikan semua detail
                    document.querySelectorAll('.shipping-method-details').forEach(detail => {
                        detail.classList.remove('active');
                    });
                    
                    // Tampilkan detail yang dipilih
                    const detailsId = this.id.replace('-radio', '-details');
                    document.getElementById(detailsId).classList.add('active');
                });
            });
        });

        // Fungsi untuk memilih metode pengiriman
        function selectShippingMethod(elementId, cost, methodId) {
            // Update tampilan metode pengiriman
            document.querySelectorAll('.shipping-method').forEach(el => {
                el.classList.remove('selected');
            });
            document.getElementById(elementId).classList.add('selected');
            
            // Update radio button
            document.getElementById(elementId + '-radio').checked = true;
            
            // Update biaya pengiriman dan total
            updateShippingCost(cost, methodId);
        }

        function updateShippingCost(cost, methodId) {
            const formatRupiah = (number) => {
                return 'Rp' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            };

            // Update tampilan
            document.getElementById('shipping-cost').textContent = formatRupiah(cost);
            document.getElementById('total-price').textContent = formatRupiah(<?= $total_harga ?> + cost);
            
            // Update form hidden values
            document.getElementById('shipping-cost-value').value = cost;
            document.getElementById('selected-shipping').value = methodId;
            
            // Update session via AJAX
            updateSessionShipping(cost, methodId);
            
            // Enable tombol checkout jika ada alamat
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn) {
                checkoutBtn.disabled = !<?= isset($alamat) ? 'true' : 'false' ?>;
            }
        }

        async function updateSessionShipping(cost, methodId) {
            try {
                const response = await fetch('update_ongkir.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        shipping_method: methodId,
                        shipping_cost: cost
                    })
                });
                
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Gagal update session');
            } catch (error) {
                console.error('Error updating session:', error);
            }

            
            // Update tampilan
            document.getElementById('shipping-cost').textContent = formatRupiah(cost);
            document.getElementById('total-price').textContent = formatRupiah(<?= $total_harga ?> + cost);
            
            // Update form hidden values
            document.getElementById('shipping-cost-value').value = cost;
            document.getElementById('selected-shipping').value = 
                document.querySelector('input[name="shipping-method"]:checked').value;
            
            // Enable tombol checkout
            document.getElementById('checkout-btn').disabled = false;
        }



        // Event listener untuk radio buttons
        document.querySelectorAll('input[name="shipping-method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    const cost = parseInt(this.getAttribute('data-cost'));
                    updateShippingCost(cost);
                }
            });
        });

        // Validasi sebelum submit form
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const alamatId = document.querySelector('input[name="alamat_id"]').value;
            const shippingMethod = document.querySelector('input[name="shipping-method"]:checked');
            
            if (!alamatId) {
                e.preventDefault();
                alert('Silakan tambahkan atau pilih alamat pengiriman terlebih dahulu');
                return;
            }
            
            if (!shippingMethod) {
                e.preventDefault();
                alert('Silakan pilih metode pengiriman terlebih dahulu');
                return;
            }
            
            // Simpan data ke sessionStorage untuk digunakan di halaman berikutnya
            sessionStorage.setItem('shipping_method', shippingMethod.value);
            sessionStorage.setItem('shipping_cost', shippingMethod.dataset.cost);
        });

        // Fungsi untuk update tombol checkout berdasarkan ketersediaan alamat
        function updateCheckoutButton() {
            const hasAddress = <?= isset($alamat) ? 'true' : 'false' ?>;
            const checkoutBtn = document.getElementById('checkout-btn');
            
            if (checkoutBtn) {
                checkoutBtn.disabled = !hasAddress;
            }
        }
        
        // Panggil saat pertama kali load
        updateCheckoutButton();

        async function updateSessionShipping(cost) {
            try {
                const response = await fetch('update_ongkir.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        shipping_cost: cost,
                        shipping_method: document.querySelector('input[name="shipping-method"]:checked').value
                    })
                });
                
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Gagal update session');
            } catch (error) {
                console.error('Error updating session:', error);
            }
        }
        


    </script>
</body>
</html>