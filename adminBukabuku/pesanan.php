<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manajemen Pesanan</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        a {
            text-decoration: none;
        }

        li {
            list-style: none;
        }

        :root {
            --poppins: 'Poppins', sans-serif;
            --lato: 'Lato', sans-serif;

            --light: #F9F9F9;
            --purple: #8E3482;
            --light-purple: #E7DBEF;
            --grey: #eee;
            --dark-grey: #AAAAAA;
            --dark: #342E37;
            --red: #DB504A;
            --yellow: #FFCE26;
            --light-yellow: #FFF2C6;
            --orange: #FD7238;
            --light-orange: #FFE0D3;
            --green: #28a745;
            --light-green: #d4edda;
        }

        html {
            overflow-x: hidden;
        }

        body.dark {
            --light: #0C0C1E;
            --grey: #060714;
            --dark: #FBFBFB;
        }

        body {
            background: var(--grey);
            overflow-x: hidden;
            font-family: var(--lato);
            transition: background 0.3s ease;
        }

        /* SIDEBAR */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100%;
            background: var(--light);
            z-index: 2000;
            transition: .3s ease;
            overflow-x: hidden;
            scrollbar-width: none;
        }
        #sidebar::-webkit-scrollbar {
            display: none;
        }
        #sidebar.hide {
            width: 60px;
        }
        #sidebar .brand {
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0;
            box-sizing: content-box;
        }
        #sidebar .brand img {
            width: 80%;
            max-width: 180px;
            transition: width 0.3s ease;
        }
        #sidebar.hide .brand img {
            width: 80%;
            max-width: 40px;
        }
        #sidebar .side-menu {
            width: 100%;
            margin-top: 48px;
        }
        #sidebar .side-menu li {
            height: 48px;
            background: transparent;
            margin-left: 6px;
            border-radius: 48px 0 0 48px;
            padding: 4px;
        }
        #sidebar .side-menu li.active {
            background: var(--grey);
            position: relative;
        }
        #sidebar .side-menu li.active::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            top: -40px;
            right: 0;
            box-shadow: 20px 20px 0 var(--grey);
            z-index: -1;
        }
        #sidebar .side-menu li.active::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            bottom: -40px;
            right: 0;
            box-shadow: 20px -20px 0 var(--grey);
            z-index: -1;
        }
        #sidebar .side-menu li a {
            width: 100%;
            height: 100%;
            background: var(--light);
            display: flex;
            align-items: center;
            border-radius: 48px;
            font-size: 16px;
            color: var(--dark);
            white-space: nowrap;
            overflow-x: hidden;
            transition: all 0.3s ease;
        }
        #sidebar .side-menu.top li.active a {
            color: var(--purple);
        }
        #sidebar.hide .side-menu li a {
            width: calc(48px - (4px * 2));
            transition: width .3s ease;
        }
        #sidebar .side-menu li a.logout {
            color: var(--red);
        }
        #sidebar .side-menu.top li a:hover {
            color: var(--purple);
        }
        #sidebar .side-menu li a .bx {
            min-width: calc(60px  - ((4px + 6px) * 2));
            display: flex;
            justify-content: center;
            font-size: 1.5rem;
        }
        #sidebar.hide .side-menu li a .text {
            display: none;
        }

        /* CONTENT */
        #content {
            position: relative;
            width: calc(100% - 280px);
            left: 280px;
            transition: .3s ease;
        }
        #sidebar.hide ~ #content {
            width: calc(100% - 60px);
            left: 60px;
        }

        /* NAVBAR */
        #content nav {
            height: 56px;
            background: var(--light);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        #content nav::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            bottom: -40px;
            left: 0;
            border-radius: 50%;
            box-shadow: -20px -20px 0 var(--light);
        }
        
        /* Navbar Left */
        .nav-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        
        /* Navbar Right */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-left: auto;
        }
        
        #content nav a {
            color: var(--dark);
        }
        #content nav .bx.bx-menu {
            cursor: pointer;
            color: var(--dark);
            font-size: 1.5rem;
        }
        #content nav .nav-link {
            font-size: 16px;
            transition: .3s ease;
        }
        #content nav .nav-link:hover {
            color: var(--purple);
        }
        #content nav .notification {
            font-size: 20px;
            position: relative;
            cursor: pointer;
        }
        #content nav .notification .num {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--light);
            background: var(--red);
            color: var(--light);
            font-weight: 700;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #content nav .profile {
            cursor: pointer;
            position: relative;
        }
        #content nav .profile img {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 50%;
        }
        #content nav .switch-mode {
            display: block;
            min-width: 50px;
            height: 25px;
            border-radius: 25px;
            background: var(--grey);
            cursor: pointer;
            position: relative;
        }
        #content nav .switch-mode::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            bottom: 2px;
            width: calc(25px - 4px);
            background: var(--purple);
            border-radius: 50%;
            transition: all .3s ease;
        }
        #content nav #switch-mode:checked + .switch-mode::before {
            left: calc(100% - (25px - 4px) - 2px);
        }
        #content nav #switch-mode {
            display: none;
        }

        /* Profile dropdown */
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--light);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 10px 0;
            min-width: 200px;
            display: none;
            z-index: 1001;
        }
        .profile-dropdown.show {
            display: block;
        }
        .profile-dropdown a {
            display: block;
            padding: 10px 20px;
            color: var(--dark);
            transition: all 0.3s ease;
        }
        .profile-dropdown a:hover {
            background: var(--grey);
            color: var(--purple);
        }

        /* Notification dropdown */
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--light);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 10px 0;
            min-width: 300px;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 1001;
        }
        .notification-dropdown.show {
            display: block;
        }
        .notification-item {
            padding: 10px 20px;
            border-bottom: 1px solid var(--grey);
            transition: all 0.3s ease;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
        .notification-item:hover {
            background: var(--grey);
        }
        .notification-item.unread {
            background: var(--light-purple);
        }
        .notification-time {
            font-size: 12px;
            color: var(--dark-grey);
            margin-top: 5px;
        }
        .mark-all-read {
            text-align: center;
            padding: 10px;
            border-top: 1px solid var(--grey);
            cursor: pointer;
            color: var(--purple);
            font-weight: 600;
        }

        /* MAIN */
        #content main {
            width: 100%;
            padding: 36px 24px;
            font-family: var(--poppins);
            max-height: calc(100vh - 56px);
            overflow-y: auto;
        }
        #content main .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            grid-gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        #content main .head-title .left h1 {
            font-size: 36px;
            font-weight: 600;
            color: var(--dark);
        }
        #content main .head-title .left .breadcrumb {
            display: flex;
            align-items: center;
            grid-gap: 16px;
        }
        #content main .head-title .left .breadcrumb li {
            color: var(--dark);
        }
        #content main .head-title .left .breadcrumb li a {
            color: var(--dark-grey);
        }
        #content main .head-title .left .breadcrumb li a.active {
            color: var(--purple);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: var(--purple);
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background: #7c2d72;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--purple);
            color: var(--purple);
        }
        .btn-outline:hover {
            background: var(--light-purple);
        }
        .btn-danger {
            background: var(--red);
            color: white;
            border: none;
        }
        .btn-danger:hover {
            background: #c4413b;
        }
        .btn-success {
            background: var(--green);
            color: white;
            border: none;
        }
        .btn-success:hover {
            background: #218838;
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .orders-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table th {
            background: var(--light-purple);
            color: var(--purple);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        .orders-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--grey);
            color: var(--dark);
        }
        .orders-table tr:last-child td {
            border-bottom: none;
        }
        .orders-table tr:hover td {
            background: rgba(142, 52, 130, 0.05);
        }
        .order-actions {
            display: flex;
            gap: 8px;
        }
        .order-actions .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .order-actions .btn-icon.edit {
            background: rgba(255, 206, 38, 0.2);
            color: var(--yellow);
        }
        .order-actions .btn-icon.edit:hover {
            background: rgba(255, 206, 38, 0.3);
        }
        .order-actions .btn-icon.delete {
            background: rgba(219, 80, 74, 0.2);
            color: var(--red);
        }
        .order-actions .btn-icon.delete:hover {
            background: rgba(219, 80, 74, 0.3);
        }
        .order-actions .btn-icon.view {
            background: rgba(56, 182, 255, 0.2);
            color: #38b6ff;
        }
        .order-actions .btn-icon.view:hover {
            background: rgba(56, 182, 255, 0.3);
        }

        /* Status Badges */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }
        .status-pending {
            background-color: var(--light-yellow);
            color: #856404;
        }
        .status-processing {
            background-color: var(--light-orange);
            color: #721c24;
        }
        .status-shipped {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-completed {
            background-color: var(--light-green);
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background: var(--light);
            padding: 24px;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .modal-header h3 {
            font-size: 1.5rem;
            color: var(--dark);
        }
        .modal-header .close {
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--dark-grey);
        }
        .modal-body {
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
        }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--grey);
            border-radius: 6px;
            font-family: var(--lato);
            transition: border 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--purple);
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Order Details */
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .order-details-section {
            background: var(--light);
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .order-details-section h4 {
            margin-bottom: 15px;
            color: var(--purple);
            border-bottom: 1px solid var(--grey);
            padding-bottom: 8px;
        }
        .order-detail-item {
            display: flex;
            margin-bottom: 10px;
        }
        .order-detail-label {
            font-weight: 500;
            width: 120px;
            color: var(--dark-grey);
        }
        .order-detail-value {
            flex: 1;
        }
        
        /* Order Items */
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .order-items th {
            background: var(--light-purple);
            color: var(--purple);
            padding: 10px;
            text-align: left;
        }
        .order-items td {
            padding: 10px;
            border-bottom: 1px solid var(--grey);
        }
        .order-items tr:last-child td {
            border-bottom: none;
        }
        
        /* Alert Message */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert i {
            font-size: 1.2rem;
        }

        /* Filter Section */
        .filter-section {
            background: var(--light);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .filter-row {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark);
        }
        .filter-group select, 
        .filter-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--grey);
            border-radius: 6px;
            font-family: var(--lato);
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            #sidebar {
                width: 200px;
            }
            #content {
                width: calc(100% - 60px);
                left: 200px;
            }
            .orders-table {
                overflow-x: auto;
                display: block;
            }
            .order-details {
                grid-template-columns: 1fr;
            }
        }

        @media screen and (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }
            .btn {
                justify-content: center;
            }
            #content nav .profile .name {
                display: none;
            }
            .modal-content {
                width: 95%;
                padding: 16px;
            }
            .filter-row {
                flex-direction: column;
                gap: 10px;
            }
            .filter-group {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <img src="../image/Navy Colorful fun Kids Book Store Logo1.png" alt="Book Store Logo">
        </a>
        <ul class="side-menu top">
            <li>
                <a href="dashboard.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="buku.php">
                    <i class='bx bxs-book'></i>
                    <span class="text">Manajemen Buku</span>
                </a>
            </li>
            <li>
                <a href="kategori.php">
                    <i class='bx bxs-purchase-tag-alt'></i>
                    <span class="text">Kategori</span>
                </a>
            </li>
            <li class="active">
                <a href="pesanan.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Pesanan</span>
                </a>
            </li>
            <!-- <li>
                <a href="laporan.html">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Laporan</span>
                </a>
            </li> -->
            <li>
                <a href="pelanggan.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Pelanggan</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="settings.html">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="#" class="logout" id="logout-btn">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <div class="nav-left">
                <i class='bx bx-menu' id="sidebar-toggle"></i>
            </div>
            
            <div class="nav-right">
                <input type="checkbox" id="switch-mode" hidden>
                <label for="switch-mode" class="switch-mode"></label>
                <a href="#" class="notification" id="notification-btn">
                    <i class='bx bxs-bell'></i>
                    <span class="num">8</span>
                </a>
                <a href="#" class="profile" id="profile-btn">
                    <img src="image/4530368-200.png" alt="Profile Image">
                </a>
            </div>
            
            <!-- Notification Dropdown -->
            <div class="notification-dropdown" id="notification-dropdown">
                <div class="notification-item unread">
                    <p>Pesanan baru dari John Doe</p>
                    <p class="notification-time">2 menit yang lalu</p>
                </div>
                <div class="notification-item unread">
                    <p>Pembayaran diterima untuk pesanan #ORD-2023-001</p>
                    <p class="notification-time">1 jam yang lalu</p>
                </div>
                <div class="notification-item">
                    <p>Pesanan #ORD-2023-005 telah dikirim</p>
                    <p class="notification-time">3 jam yang lalu</p>
                </div>
                <div class="notification-item">
                    <p>Stok buku "Belajar JavaScript" hampir habis</p>
                    <p class="notification-time">5 jam yang lalu</p>
                </div>
                <div class="mark-all-read" id="mark-all-read">
                    Tandai semua telah dibaca
                </div>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown" id="profile-dropdown">
                <a href="profile.html"><i class='bx bxs-user'></i> Profil Saya</a>
                <a href="settings.html"><i class='bx bxs-cog'></i> Pengaturan</a>
                <a href="#" id="logout-dropdown-btn"><i class='bx bxs-log-out-circle'></i> Logout</a>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Manajemen Pesanan</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="dashboard.html">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Pesanan</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Alert Message -->
            <div class="alert alert-success" id="success-alert" style="display: none;">
                <i class='bx bx-check-circle'></i>
                <span id="alert-message"></span>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="filter-status">Status</label>
                        <select id="filter-status">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-date">Tanggal</label>
                        <input type="date" id="filter-date">
                    </div>
                    <div class="filter-group">
                        <label for="filter-customer">Pelanggan</label>
                        <input type="text" id="filter-customer" placeholder="Nama pelanggan">
                    </div>
                </div>
                <div class="filter-row">
                    <button class="btn btn-primary" id="apply-filter">
                        <i class='bx bx-filter-alt'></i> Terapkan Filter
                    </button>
                    <button class="btn btn-outline" id="reset-filter">
                        <i class='bx bx-reset'></i> Reset
                    </button>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn btn-outline" id="export-orders-btn">
                    <i class='bx bx-export'></i> Export
                </button>
                <button class="btn btn-primary" id="print-orders-btn">
                    <i class='bx bx-printer'></i> Cetak
                </button>
            </div>

            <div class="orders-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-order-id="ORD-2023-001">
                            <td>ORD-2023-001</td>
                            <td>John Doe</td>
                            <td>12 Jan 2023</td>
                            <td>Rp 250.000</td>
                            <td><span class="status-badge status-completed">Completed</span></td>
                            <td>
                                <div class="order-actions">
                                    <div class="btn-icon view" onclick="viewOrderDetails('ORD-2023-001')">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    <div class="btn-icon edit" onclick="editOrder('ORD-2023-001')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr data-order-id="ORD-2023-002">
                            <td>ORD-2023-002</td>
                            <td>Jane Smith</td>
                            <td>15 Jan 2023</td>
                            <td>Rp 180.000</td>
                            <td><span class="status-badge status-shipped">Shipped</span></td>
                            <td>
                                <div class="order-actions">
                                    <div class="btn-icon view" onclick="viewOrderDetails('ORD-2023-002')">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    <div class="btn-icon edit" onclick="editOrder('ORD-2023-002')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr data-order-id="ORD-2023-003">
                            <td>ORD-2023-003</td>
                            <td>Robert Johnson</td>
                            <td>20 Feb 2023</td>
                            <td>Rp 320.000</td>
                            <td><span class="status-badge status-processing">Processing</span></td>
                            <td>
                                <div class="order-actions">
                                    <div class="btn-icon view" onclick="viewOrderDetails('ORD-2023-003')">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    <div class="btn-icon edit" onclick="editOrder('ORD-2023-003')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr data-order-id="ORD-2023-004">
                            <td>ORD-2023-004</td>
                            <td>Sarah Williams</td>
                            <td>5 Mar 2023</td>
                            <td>Rp 150.000</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td>
                                <div class="order-actions">
                                    <div class="btn-icon view" onclick="viewOrderDetails('ORD-2023-004')">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    <div class="btn-icon edit" onclick="editOrder('ORD-2023-004')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                    <div class="btn-icon delete" onclick="cancelOrder('ORD-2023-004')">
                                        <i class='bx bx-x'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr data-order-id="ORD-2023-005">
                            <td>ORD-2023-005</td>
                            <td>Michael Brown</td>
                            <td>10 Apr 2023</td>
                            <td>Rp 210.000</td>
                            <td><span class="status-badge status-cancelled">Cancelled</span></td>
                            <td>
                                <div class="order-actions">
                                    <div class="btn-icon view" onclick="viewOrderDetails('ORD-2023-005')">
                                        <i class='bx bx-show'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Order Details Modal -->
    <div class="modal" id="order-details-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Pesanan <span id="order-id-header"></span></h3>
                <span class="close" onclick="closeModal('order-details-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="order-details">
                    <div class="order-details-section">
                        <h4>Informasi Pesanan</h4>
                        <div class="order-detail-item">
                            <div class="order-detail-label">ID Pesanan</div>
                            <div class="order-detail-value" id="detail-order-id">ORD-2023-001</div>
                        </div>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Tanggal</div>
                            <div class="order-detail-value" id="detail-order-date">12 Jan 2023</div>
                        </div>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Status</div>
                            <div class="order-detail-value" id="detail-order-status"><span class="status-badge status-completed">Completed</span></div>
                        </div>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Total</div>
                            <div class="order-detail-value" id="detail-order-total">Rp 250.000</div>
                        </div>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Metode Pembayaran</div>
                            <div class="order-detail-value" id="detail-payment-method">Transfer Bank (BCA)</div>
                        </div>
                    </div>
                    
                    <div class="order-details-section">
                        <h4>Informasi Pelanggan</h4>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Nama</div>
                            <div class="order-detail-value" id="detail-customer-name">John Doe</div>
                        </div>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Email</div>
                            <div class="order-detail-value" id="detail-customer-email">john.doe@example.com</div>
                        </div>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Telepon</div>
                            <div class="order-detail-value" id="detail-customer-phone">081234567890</div>
                        </div>
                    </div>
                    
                    <div class="order-details-section">
                        <h4>Alamat Pengiriman</h4>
                        <div class="order-detail-item">
                            <div class="order-detail-value" id="detail-shipping-address">
                                Jl. Merdeka No. 123<br>
                                Jakarta Selatan<br>
                                DKI Jakarta 12345<br>
                                Indonesia
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-details-section">
                        <h4>Informasi Pengiriman</h4>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Kurir</div>
                            <div class="order-detail-value" id="detail-shipping-method">JNE Reguler</div>
                        </div>
                        <div class="order-detail-item">
                            <div class="order-detail-label">No. Resi</div>
                            <div class="order-detail-value" id="detail-tracking-number">1234567890</div>
                        </div>
                        <div class="order-detail-item">
                            <div class="order-detail-label">Estimasi</div>
                            <div class="order-detail-value" id="detail-shipping-estimate">2-3 hari kerja</div>
                        </div>
                    </div>
                </div>
                
                <h4>Daftar Produk</h4>
                <table class="order-items">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="order-items-list">
                        <tr>
                            <td>Laskar Pelangi - Andrea Hirata</td>
                            <td>Rp 75.000</td>
                            <td>2</td>
                            <td>Rp 150.000</td>
                        </tr>
                        <tr>
                            <td>Bumi Manusia - Pramoedya Ananta Toer</td>
                            <td>Rp 100.000</td>
                            <td>1</td>
                            <td>Rp 100.000</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 500;">Subtotal</td>
                            <td>Rp 250.000</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 500;">Ongkos Kirim</td>
                            <td>Rp 15.000</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 500;">Total</td>
                            <td>Rp 265.000</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('order-details-modal')">Tutup</button>
                <button class="btn btn-primary" onclick="printOrder()">
                    <i class='bx bx-printer'></i> Cetak Invoice
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div class="modal" id="edit-order-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Pesanan <span id="edit-order-id-header"></span></h3>
                <span class="close" onclick="closeModal('edit-order-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-order-status">Status Pesanan</label>
                    <select id="edit-order-status" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-tracking-number">Nomor Resi</label>
                    <input type="text" id="edit-tracking-number" class="form-control" placeholder="Masukkan nomor resi">
                </div>
                <div class="form-group">
                    <label for="edit-shipping-method">Metode Pengiriman</label>
                    <input type="text" id="edit-shipping-method" class="form-control" placeholder="Masukkan metode pengiriman">
                </div>
                <div class="form-group">
                    <label for="edit-order-notes">Catatan</label>
                    <textarea id="edit-order-notes" class="form-control" rows="3" placeholder="Masukkan catatan pesanan"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('edit-order-modal')">Batal</button>
                <button class="btn btn-primary" id="save-order-btn">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal" id="cancel-order-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Batalkan Pesanan</h3>
                <span class="close" onclick="closeModal('cancel-order-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <p id="cancel-order-message">Apakah Anda yakin ingin membatalkan pesanan ini?</p>
                <div class="form-group">
                    <label for="cancel-reason">Alasan Pembatalan</label>
                    <textarea id="cancel-reason" class="form-control" rows="3" placeholder="Masukkan alasan pembatalan"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('cancel-order-modal')">Batal</button>
                <button class="btn btn-danger" id="confirm-cancel-btn">Konfirmasi Pembatalan</button>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const switchMode = document.getElementById('switch-mode');
        const exportOrdersBtn = document.getElementById('export-orders-btn');
        const printOrdersBtn = document.getElementById('print-orders-btn');
        const applyFilterBtn = document.getElementById('apply-filter');
        const resetFilterBtn = document.getElementById('reset-filter');
        const successAlert = document.getElementById('success-alert');
        const alertMessage = document.getElementById('alert-message');
        const notificationBtn = document.getElementById('notification-btn');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        const markAllReadBtn = document.getElementById('mark-all-read');
        const logoutBtn = document.getElementById('logout-btn');
        const logoutDropdownBtn = document.getElementById('logout-dropdown-btn');

        // Current order id for edit/cancel
        let currentOrderId = null;

        // Sample order data
        const ordersData = {
            'ORD-2023-001': {
                id: 'ORD-2023-001',
                customer: {
                    name: 'John Doe',
                    email: 'john.doe@example.com',
                    phone: '081234567890'
                },
                date: '12 Jan 2023',
                status: 'completed',
                total: 250000,
                paymentMethod: 'Transfer Bank (BCA)',
                shippingAddress: 'Jl. Merdeka No. 123<br>Jakarta Selatan<br>DKI Jakarta 12345<br>Indonesia',
                shippingMethod: 'JNE Reguler',
                trackingNumber: '1234567890',
                shippingEstimate: '2-3 hari kerja',
                items: [
                    { name: 'Laskar Pelangi - Andrea Hirata', price: 75000, quantity: 2 },
                    { name: 'Bumi Manusia - Pramoedya Ananta Toer', price: 100000, quantity: 1 }
                ],
                shippingCost: 15000,
                notes: 'Pesanan sudah diterima dengan baik oleh pelanggan'
            },
            'ORD-2023-002': {
                id: 'ORD-2023-002',
                customer: {
                    name: 'Jane Smith',
                    email: 'jane.smith@example.com',
                    phone: '082345678901'
                },
                date: '15 Jan 2023',
                status: 'shipped',
                total: 180000,
                paymentMethod: 'Credit Card',
                shippingAddress: 'Jl. Sudirman No. 456<br>Jakarta Pusat<br>DKI Jakarta 10110<br>Indonesia',
                shippingMethod: 'SiCepat',
                trackingNumber: 'ABCD123456',
                shippingEstimate: '1-2 hari kerja',
                items: [
                    { name: 'Harry Potter and the Philosopher\'s Stone', price: 120000, quantity: 1 },
                    { name: 'The Hobbit', price: 60000, quantity: 1 }
                ],
                shippingCost: 20000,
                notes: 'Pesanan sedang dalam pengiriman'
            },
            'ORD-2023-003': {
                id: 'ORD-2023-003',
                customer: {
                    name: 'Robert Johnson',
                    email: 'robert.j@example.com',
                    phone: '083456789012'
                },
                date: '20 Feb 2023',
                status: 'processing',
                total: 320000,
                paymentMethod: 'Transfer Bank (Mandiri)',
                shippingAddress: 'Jl. Thamrin No. 789<br>Jakarta Pusat<br>DKI Jakarta 10350<br>Indonesia',
                shippingMethod: 'JNE OKE',
                trackingNumber: '',
                shippingEstimate: '3-5 hari kerja',
                items: [
                    { name: 'Atomic Habits - James Clear', price: 90000, quantity: 1 },
                    { name: 'Deep Work - Cal Newport', price: 85000, quantity: 1 },
                    { name: 'The Psychology of Money', price: 95000, quantity: 1 },
                    { name: 'Bookmark Premium', price: 50000, quantity: 1 }
                ],
                shippingCost: 25000,
                notes: 'Menunggu konfirmasi pembayaran'
            },
            'ORD-2023-004': {
                id: 'ORD-2023-004',
                customer: {
                    name: 'Sarah Williams',
                    email: 'sarah.w@example.com',
                    phone: '084567890123'
                },
                date: '5 Mar 2023',
                status: 'pending',
                total: 150000,
                paymentMethod: 'Transfer Bank (BNI)',
                shippingAddress: 'Jl. Gatot Subroto No. 321<br>Jakarta Selatan<br>DKI Jakarta 12950<br>Indonesia',
                shippingMethod: 'J&T Express',
                trackingNumber: '',
                shippingEstimate: '2-4 hari kerja',
                items: [
                    { name: 'Filosofi Teras - Henry Manampiring', price: 75000, quantity: 2 }
                ],
                shippingCost: 15000,
                notes: 'Pesanan baru, menunggu proses'
            },
            'ORD-2023-005': {
                id: 'ORD-2023-005',
                customer: {
                    name: 'Michael Brown',
                    email: 'michael.b@example.com',
                    phone: '085678901234'
                },
                date: '10 Apr 2023',
                status: 'cancelled',
                total: 210000,
                paymentMethod: 'Transfer Bank (BRI)',
                shippingAddress: 'Jl. MH Thamrin No. 1<br>Jakarta Pusat<br>DKI Jakarta 10310<br>Indonesia',
                shippingMethod: '',
                trackingNumber: '',
                shippingEstimate: '',
                items: [
                    { name: 'Sapiens - Yuval Noah Harari', price: 105000, quantity: 2 }
                ],
                shippingCost: 0,
                notes: 'Dibatalkan oleh pelanggan'
            }
        };

        // Toggle sidebar
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('hide');
        });
        
        // Dark/light mode toggle
        switchMode.addEventListener('change', () => {
            if(switchMode.checked) {
                document.body.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        });
        
        // Check for saved theme preference
        if(localStorage.getItem('theme') === 'dark') {
            switchMode.checked = true;
            document.body.classList.add('dark');
        }

        // Toggle notification dropdown
        notificationBtn.addEventListener('click', (e) => {
            e.preventDefault();
            notificationDropdown.classList.toggle('show');
            profileDropdown.classList.remove('show');
        });

        // Toggle profile dropdown
        profileBtn.addEventListener('click', (e) => {
            e.preventDefault();
            profileDropdown.classList.toggle('show');
            notificationDropdown.classList.remove('show');
        });

        // Mark all notifications as read
        markAllReadBtn.addEventListener('click', () => {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            document.querySelector('.notification .num').textContent = '0';
            notificationDropdown.classList.remove('show');
        });

        // Logout buttons
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if(confirm('Apakah Anda yakin ingin logout?')) {
                // Redirect to login page
                window.location.href = 'login.html';
            }
        });

        logoutDropdownBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if(confirm('Apakah Anda yakin ingin logout?')) {
                // Redirect to login page
                window.location.href = 'login.html';
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if(!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.remove('show');
            }
            if(!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });

        // Export orders button click
        exportOrdersBtn.addEventListener('click', () => {
            showAlert('Data pesanan berhasil diexport dalam format CSV', 'success');
        });

        // Print orders button click
        printOrdersBtn.addEventListener('click', () => {
            showAlert('Daftar pesanan akan dicetak', 'success');
            // In real implementation, this would open print dialog
            setTimeout(() => {
                window.print();
            }, 500);
        });

        // Apply filter button click
        applyFilterBtn.addEventListener('click', () => {
            const status = document.getElementById('filter-status').value;
            const date = document.getElementById('filter-date').value;
            const customer = document.getElementById('filter-customer').value.toLowerCase();
            
            document.querySelectorAll('.orders-table tbody tr').forEach(row => {
                const rowStatus = row.querySelector('td:nth-child(5) span').className.includes(status);
                const rowDate = row.querySelector('td:nth-child(3)').textContent;
                const rowCustomer = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                const statusMatch = !status || rowStatus;
                const dateMatch = !date || rowDate.includes(new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }));
                const customerMatch = !customer || rowCustomer.includes(customer);
                
                row.style.display = (statusMatch && dateMatch && customerMatch) ? '' : 'none';
            });
            
            showAlert('Filter telah diterapkan', 'success');
        });

        // Reset filter button click
        resetFilterBtn.addEventListener('click', () => {
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-date').value = '';
            document.getElementById('filter-customer').value = '';
            
            document.querySelectorAll('.orders-table tbody tr').forEach(row => {
                row.style.display = '';
            });
            
            showAlert('Filter telah direset', 'success');
        });

        // View order details
        function viewOrderDetails(orderId) {
            const order = ordersData[orderId];
            if (!order) return;
            
            // Set order details
            document.getElementById('order-id-header').textContent = order.id;
            document.getElementById('detail-order-id').textContent = order.id;
            document.getElementById('detail-order-date').textContent = order.date;
            document.getElementById('detail-order-total').textContent = formatCurrency(order.total);
            document.getElementById('detail-payment-method').textContent = order.paymentMethod;
            document.getElementById('detail-customer-name').textContent = order.customer.name;
            document.getElementById('detail-customer-email').textContent = order.customer.email;
            document.getElementById('detail-customer-phone').textContent = order.customer.phone;
            document.getElementById('detail-shipping-address').innerHTML = order.shippingAddress;
            document.getElementById('detail-shipping-method').textContent = order.shippingMethod;
            document.getElementById('detail-tracking-number').textContent = order.trackingNumber || '-';
            document.getElementById('detail-shipping-estimate').textContent = order.shippingEstimate || '-';
            
            // Set status badge
            const statusBadge = document.getElementById('detail-order-status');
            statusBadge.innerHTML = '';
            const badge = document.createElement('span');
            badge.className = `status-badge status-${order.status}`;
            badge.textContent = getStatusText(order.status);
            statusBadge.appendChild(badge);
            
            // Set order items
            const itemsList = document.getElementById('order-items-list');
            itemsList.innerHTML = '';
            
            order.items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${formatCurrency(item.price)}</td>
                    <td>${item.quantity}</td>
                    <td>${formatCurrency(item.price * item.quantity)}</td>
                `;
                itemsList.appendChild(row);
            });
            
            // Set order totals
            const tfoot = itemsList.parentElement.querySelector('tfoot');
            tfoot.querySelector('tr:nth-child(1) td:nth-child(4)').textContent = formatCurrency(order.total);
            tfoot.querySelector('tr:nth-child(2) td:nth-child(4)').textContent = formatCurrency(order.shippingCost);
            tfoot.querySelector('tr:nth-child(3) td:nth-child(4)').textContent = formatCurrency(order.total + order.shippingCost);
            
            // Show modal
            document.getElementById('order-details-modal').classList.add('show');
        }

        // Edit order
        function editOrder(orderId) {
            const order = ordersData[orderId];
            if (!order) return;
            
            currentOrderId = orderId;
            document.getElementById('edit-order-id-header').textContent = order.id;
            document.getElementById('edit-order-status').value = order.status;
            document.getElementById('edit-tracking-number').value = order.trackingNumber || '';
            document.getElementById('edit-shipping-method').value = order.shippingMethod || '';
            document.getElementById('edit-order-notes').value = order.notes || '';
            
            document.getElementById('edit-order-modal').classList.add('show');
        }

        // Cancel order
        function cancelOrder(orderId) {
            currentOrderId = orderId;
            document.getElementById('cancel-order-message').textContent = `Apakah Anda yakin ingin membatalkan pesanan ${orderId}?`;
            document.getElementById('cancel-reason').value = '';
            document.getElementById('cancel-order-modal').classList.add('show');
        }

        // Save order changes
        document.getElementById('save-order-btn').addEventListener('click', () => {
            if (!currentOrderId) return;
            
            const status = document.getElementById('edit-order-status').value;
            const trackingNumber = document.getElementById('edit-tracking-number').value;
            const shippingMethod = document.getElementById('edit-shipping-method').value;
            const notes = document.getElementById('edit-order-notes').value;
            
            // Update order data
            ordersData[currentOrderId].status = status;
            ordersData[currentOrderId].trackingNumber = trackingNumber;
            ordersData[currentOrderId].shippingMethod = shippingMethod;
            ordersData[currentOrderId].notes = notes;
            
            // Update table row
            const row = document.querySelector(`tr[data-order-id="${currentOrderId}"]`);
            if (row) {
                const statusBadge = row.querySelector('td:nth-child(5) span');
                statusBadge.className = `status-badge status-${status}`;
                statusBadge.textContent = getStatusText(status);
            }
            
            showAlert(`Pesanan ${currentOrderId} berhasil diperbarui`, 'success');
            closeModal('edit-order-modal');
            currentOrderId = null;
        });

        // Confirm cancel order
        document.getElementById('confirm-cancel-btn').addEventListener('click', () => {
            if (!currentOrderId) return;
            
            const reason = document.getElementById('cancel-reason').value;
            
            // Update order data
            ordersData[currentOrderId].status = 'cancelled';
            ordersData[currentOrderId].notes = reason ? `Dibatalkan: ${reason}` : 'Dibatalkan';
            
            // Update table row
            const row = document.querySelector(`tr[data-order-id="${currentOrderId}"]`);
            if (row) {
                const statusBadge = row.querySelector('td:nth-child(5) span');
                statusBadge.className = 'status-badge status-cancelled';
                statusBadge.textContent = 'Cancelled';
                
                // Remove edit and cancel buttons
                const actions = row.querySelector('.order-actions');
                actions.innerHTML = `
                    <div class="btn-icon view" onclick="viewOrderDetails('${currentOrderId}')">
                        <i class='bx bx-show'></i>
                    </div>
                `;
            }
            
            showAlert(`Pesanan ${currentOrderId} telah dibatalkan`, 'success');
            closeModal('cancel-order-modal');
            currentOrderId = null;
        });

        // Print order
        function printOrder() {
            showAlert(`Mencetak invoice pesanan`, 'success');
            // In real implementation, this would open print dialog for the order
            setTimeout(() => {
                window.print();
            }, 500);
        }

        // Close modal function
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // Show alert message
        function showAlert(message, type) {
            alertMessage.textContent = message;
            successAlert.className = `alert alert-${type}`;
            successAlert.style.display = 'flex';
            
            // Change icon based on type
            const icon = successAlert.querySelector('i');
            icon.className = type === 'success' ? 'bx bx-check-circle' : 'bx bx-error';
            
            // Hide after 5 seconds
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 5000);
        }

        // Helper function to format currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Helper function to get status text
        function getStatusText(status) {
            const statusMap = {
                'pending': 'Pending',
                'processing': 'Processing',
                'shipped': 'Shipped',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            };
            return statusMap[status] || status;
        }

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if(event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        });
    </script>
</body>
</html>