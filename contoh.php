<!DOCTYPE html>
<html lang="en">
<head>
    <!-- fix -->
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
                        <img src="image/toko buku abadi.avif" alt="Book" style="width: 50px; height: 70px; object-fit: cover; border-radius: 5px;">
                        <div>
                            <div class="item-title">Toko Buku Abadi</div>
                            <div>1 barang</div>
                        </div>
                    </div>
                    <div class="item-price">Rp90.000</div>
                </div>
                
                <div class="divider"></div>
                
                <div class="order-item total-row">
                    <div>Total Pesanan</div>
                    <div class="item-price">Rp90.000</div>
                </div>
            </div>
            
            <div class="address-section">
                <h2>Alamat Pengiriman</h2>
                <div id="no-address-message">Belum ada alamat terdaftar</div>
                <button id="add-address-btn" class="add-address-btn">Buat Alamat</button>
                
                <div id="address-card" class="address-card">
                    <div class="address-info">
                        <p><strong id="address-name">John Doe</strong></p>
                        <p id="address-phone">081234567890</p>
                        <p id="address-full">Jl. Contoh No. 123, Kota Contoh, Provinsi Contoh, 12345</p>
                    </div>
                    <div class="address-actions">
                        <button class="address-action-btn" id="edit-address-btn">Ubah</button>
                        <button class="address-action-btn" id="delete-address-btn">Hapus</button>
                    </div>
                </div>
                
                <div id="address-form" class="address-form">
                    <div class="form-group">
                        <label for="name">Nama Penerima</label>
                        <input type="text" id="name" placeholder="Nama lengkap">
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="tel" id="phone" placeholder="081234567890">
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat Lengkap</label>
                        <textarea id="address" rows="3" placeholder="Jl. Contoh No. 123"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="city">Kota/Kabupaten</label>
                        <input type="text" id="city" placeholder="Kota Contoh">
                    </div>
                    <div class="form-group">
                        <label for="province">Provinsi</label>
                        <input type="text" id="province" placeholder="Provinsi Contoh">
                    </div>
                    <div class="form-group">
                        <label for="postal-code">Kode Pos</label>
                        <input type="text" id="postal-code" placeholder="12345">
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-secondary" id="cancel-address-btn">Batal</button>
                        <button class="btn btn-primary" id="save-address-btn">Simpan Alamat</button>
                    </div>
                </div>
            </div>
            
            <div class="order-card shipping-methods">
                <h2>Metode Pengiriman</h2>
                <div class="shipping-method" id="regular-shipping">
                    <input type="radio" name="shipping-method" id="shipping-regular" checked>
                    <label for="shipping-regular">
                        <div>Reguler</div>
                        <div class="shipping-estimate">Estimasi: 3-5 hari kerja</div>
                    </label>
                    <div class="item-price">Rp15.000</div>
                </div>
                <div class="shipping-method" id="express-shipping">
                    <input type="radio" name="shipping-method" id="shipping-express">
                    <label for="shipping-express">
                        <div>Express</div>
                        <div class="shipping-estimate">Estimasi: 1-2 hari kerja</div>
                    </label>
                    <div class="item-price">Rp30.000</div>
                </div>
                <div class="shipping-method" id="pickup">
                    <input type="radio" name="shipping-method" id="shipping-pickup">
                    <label for="shipping-pickup">
                        <div>Ambil di Toko</div>
                        <div class="shipping-estimate">Estimasi: Siap diambil hari ini</div>
                    </label>
                    <div class="item-price">Rp0</div>
                </div>
                
                <div id="regular-shipping-details" class="shipping-method-details active">
                    <h3>Pengiriman Reguler</h3>
                    <p>Paket akan dikirim melalui jasa pengiriman standar</p>
                    <p>Dapat dilacak melalui nomor resi yang akan dikirim via SMS/email</p>
                </div>
                
                <div id="express-shipping-details" class="shipping-method-details">
                    <h3>Pengiriman Express</h3>
                    <p>Paket akan dikirim melalui jasa pengiriman kilat</p>
                    <p>Prioritas pengiriman dengan pelacakan real-time</p>
                </div>
                
                <div id="pickup-details" class="shipping-method-details">
                    <h3>Ambil di Toko</h3>
                    <p>Anda dapat mengambil pesanan di toko kami</p>
                    <p>Alamat toko: Jl. Toko Buku No. 123, Kota Anda</p>
                    <p>Jam operasional: 09:00 - 17:00 (Senin-Minggu)</p>
                </div>
            </div>
        </div>
        
        <div class="summary-section">
            <h2>Ringkasan</h2>
            <div class="summary-row">
                <div>Total Harga (1 Barang)</div>
                <div>Rp90.000</div>
            </div>
            <div class="summary-row">
                <div>Total Biaya Pengiriman</div>
                <div id="shipping-cost">Rp15.000</div>
            </div>
            
            <div class="divider"></div>
            
            <div class="summary-row total-row">
                <div>Total Belanja</div>
                <div id="total-price">Rp105.000</div>
            </div>
            <button class="checkout-btn" id="checkout-btn" disabled>Lanjut Pembayaran</button>
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
            
            // Shipping method elements
            const shippingMethods = document.querySelectorAll('.shipping-method');
            const regularShippingDetails = document.getElementById('regular-shipping-details');
            const expressShippingDetails = document.getElementById('express-shipping-details');
            const pickupDetails = document.getElementById('pickup-details');
            const shippingCostElement = document.getElementById('shipping-cost');
            const totalPriceElement = document.getElementById('total-price');
            
            // Address data
            let savedAddress = null;
            
            // Shipping costs
            const shippingCosts = {
                'regular-shipping': 15000,
                'express-shipping': 30000,
                'pickup': 0
            };
            
            // Event Listeners
            addAddressBtn.addEventListener('click', showAddressForm);
            saveAddressBtn.addEventListener('click', saveAddress);
            cancelAddressBtn.addEventListener('click', cancelAddress);
            editAddressBtn.addEventListener('click', editAddress);
            deleteAddressBtn.addEventListener('click', deleteAddress);
            
            // Shipping method selection
            shippingMethods.forEach(method => {
                method.addEventListener('click', function() {
                    // Update selected style
                    shippingMethods.forEach(m => m.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    // Show corresponding details
                    regularShippingDetails.classList.remove('active');
                    expressShippingDetails.classList.remove('active');
                    pickupDetails.classList.remove('active');
                    
                    const methodId = this.id;
                    if (methodId === 'regular-shipping') {
                        regularShippingDetails.classList.add('active');
                        updateShippingCost(shippingCosts['regular-shipping']);
                    } else if (methodId === 'express-shipping') {
                        expressShippingDetails.classList.add('active');
                        updateShippingCost(shippingCosts['express-shipping']);
                    } else if (methodId === 'pickup') {
                        pickupDetails.classList.add('active');
                        updateShippingCost(shippingCosts['pickup']);
                    }
                });
            });
            
            // Functions
            function showAddressForm() {
                addressForm.classList.add('active');
                addAddressBtn.style.display = 'none';
                noAddressMessage.style.display = 'none';
                
                // If editing, populate form with existing data
                if (savedAddress) {
                    document.getElementById('name').value = savedAddress.name;
                    document.getElementById('phone').value = savedAddress.phone;
                    document.getElementById('address').value = savedAddress.address;
                    document.getElementById('city').value = savedAddress.city;
                    document.getElementById('province').value = savedAddress.province;
                    document.getElementById('postal-code').value = savedAddress.postalCode;
                }
            }
            
            function hideAddressForm() {
                addressForm.classList.remove('active');
                if (!savedAddress) {
                    addAddressBtn.style.display = 'inline-block';
                    noAddressMessage.style.display = 'block';
                }
            }
            
            function saveAddress() {
                const name = document.getElementById('name').value;
                const phone = document.getElementById('phone').value;
                const address = document.getElementById('address').value;
                const city = document.getElementById('city').value;
                const province = document.getElementById('province').value;
                const postalCode = document.getElementById('postal-code').value;
                
                // Simple validation
                if (!name || !phone || !address || !city || !province || !postalCode) {
                    alert('Harap isi semua field alamat');
                    return;
                }
                
                // Save address data
                savedAddress = {
                    name,
                    phone,
                    address,
                    city,
                    province,
                    postalCode
                };
                
                // Update address card
                document.getElementById('address-name').textContent = name;
                document.getElementById('address-phone').textContent = phone;
                document.getElementById('address-full').textContent = `${address}, ${city}, ${province}, ${postalCode}`;
                
                // Show address card
                addressCard.classList.add('active');
                noAddressMessage.style.display = 'none';
                addAddressBtn.style.display = 'none';
                
                // Hide form
                hideAddressForm();
                
                // Enable checkout button
                checkoutBtn.disabled = false;
            }
            
            function cancelAddress() {
                hideAddressForm();
            }
            
            function editAddress() {
                showAddressForm();
            }
            
            function deleteAddress() {
                if (confirm('Apakah Anda yakin ingin menghapus alamat ini?')) {
                    savedAddress = null;
                    addressCard.classList.remove('active');
                    noAddressMessage.style.display = 'block';
                    addAddressBtn.style.display = 'inline-block';
                    
                    // Disable checkout button if no address
                    checkoutBtn.disabled = true;
                }
            }
            
            function updateShippingCost(cost) {
                const formattedCost = formatCurrency(cost);
                shippingCostElement.textContent = formattedCost;
                
                // Update total price
                const itemPrice = 90000;
                const totalPrice = itemPrice + cost;
                totalPriceElement.textContent = formatCurrency(totalPrice);
            }
            
            function formatCurrency(amount) {
                return 'Rp' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            
            // Checkout button click
            checkoutBtn.addEventListener('click', function() {
                if (!savedAddress) {
                    alert('Harap tambahkan alamat pengiriman terlebih dahulu');
                    return;
                }
                
                // // Simulate checkout process
                // this.textContent = 'Memproses...';
                // this.disabled = true;
                
                setTimeout(() => {
                    alert('Metode Pengiriman berhasil diproses! Selanjutnya metode Pembayan.');
                    // In a real app, you would redirect to a confirmation page
                }, 1500);
            });
            
            // Initialize
            if (savedAddress) {
                addressCard.classList.add('active');
                noAddressMessage.style.display = 'none';
                addAddressBtn.style.display = 'none';
                checkoutBtn.disabled = false;
            } else {
                checkoutBtn.disabled = true;
            }
            
            // Set default shipping method
            document.getElementById('regular-shipping').classList.add('selected');
        });
    </script>
</body>
</html>