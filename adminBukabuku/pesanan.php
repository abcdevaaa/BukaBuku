<?php
session_start();
include('../koneksi.php');

// Query untuk mengambil data pesanan dengan join ke tabel terkait
$query = "SELECT 
            p.id_pesanan, 
            p.tanggal_pesanan, 
            p.total_belanja, 
            p.status, 
            p.bukti,
            u.username AS nama_pelanggan,
            u.email,
            a.no_telepon,
            a.alamat_lengkap,
            a.kabupaten,
            a.provinsi,
            a.kode_pos,
            mp.nama_metode AS metode_pembayaran,
            mpg.nama_metode AS metode_pengiriman,
            mpg.biaya AS biaya_pengiriman,
            mpg.estimasi
          FROM pesanan p
          JOIN users u ON p.id_users = u.id_users
          JOIN alamat a ON p.id_alamat = a.id_alamat
          LEFT JOIN metode_pembayaran mp ON p.metode_pembayaran = mp.id_metodePembayaran
          LEFT JOIN metode_pengiriman mpg ON p.metode_pengiriman = mpg.id_metodePengiriman
          ORDER BY p.tanggal_pesanan DESC";
$result = mysqli_query($koneksi, $query);

// Query untuk filter status
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
if ($status_filter) {
    $query .= " WHERE p.status = '$status_filter'";
}

// Query untuk filter tanggal
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
if ($date_filter) {
    $query .= ($status_filter ? " AND" : " WHERE") . " DATE(p.tanggal_pesanan) = '$date_filter'";
}

// Query untuk filter pelanggan
$customer_filter = isset($_GET['customer']) ? $_GET['customer'] : '';
if ($customer_filter) {
    $query .= ($status_filter || $date_filter ? " AND" : " WHERE") . " u.nama LIKE '%$customer_filter%'";
}

$result = mysqli_query($koneksi, $query);

$admin_id = $_SESSION['id_users']; // Asumsikan id admin disimpan di session
$query_admin = "SELECT profil FROM users WHERE id_users = '$admin_id'";
$result_admin = mysqli_query($koneksi, $query_admin);
$admin = mysqli_fetch_assoc($result_admin);
?>

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
        /* Tambahan untuk modal */
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
            <li>
                <a href="laporan.php">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Laporan</span>
                </a>
            </li>
            <li>
                <a href="pelanggan.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Pelanggan</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
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
                <a href="#" class="profile" id="profile-btn">
                    <?php if (!empty($admin['profil'])) : ?>
                        <img src="image/<?= htmlspecialchars($admin['profil']) ?>" alt="Profile Image">
                    <?php else : ?>
                        <img src="image/profile-picture.jpg" alt="Profile Image">
                    <?php endif; ?>
                </a>
            </div> 
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown" id="profile-dropdown">
                <a href="profilA.php"><i class='bx bxs-user'></i> Profil Saya</a>
                <a href="../logout.php" id="logout-dropdown-btn"><i class='bx bxs-log-out-circle'></i> Logout</a>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Manajemen Pesanan</h1>
                    
                </div>
            </div>

            <!-- Alert Message -->
            <?php if (isset($_SESSION['alert_message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['alert_type']; ?>">
                    <i class='bx <?php echo $_SESSION['alert_type'] == 'success' ? 'bx-check-circle' : 'bx-error'; ?>'></i>
                    <span><?php echo $_SESSION['alert_message']; ?></span>
                </div>
                <?php unset($_SESSION['alert_message']); unset($_SESSION['alert_type']); ?>
            <?php endif; ?>

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="filter-status">Status</label>
                            <select id="filter-status" name="status">
                                <option value="">Semua Status</option>
                                <option value="menunggu pembayaran" <?php echo $status_filter == 'menunggu pembayaran' ? 'selected' : ''; ?>>Menunggu Pembayaran</option>
                                <option value="pesanan diproses" <?php echo $status_filter == 'pesanan diproses' ? 'selected' : ''; ?>>Pesanan Diproses</option>
                                <option value="pesanan dikirim" <?php echo $status_filter == 'pesanan dikirim' ? 'selected' : ''; ?>>Pesanan Dikirim</option>
                                <option value="pesanan diterima" <?php echo $status_filter == 'pesanan diterima' ? 'selected' : ''; ?>>Pesanan Diterima</option>
                                <option value="pesanan dibatalkan" <?php echo $status_filter == 'pesanan dibatalkan' ? 'selected' : ''; ?>>Pesanan Dibatalkan</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter-date">Tanggal</label>
                            <input type="date" id="filter-date" name="date" value="<?php echo $date_filter; ?>">
                        </div>
                        <div class="filter-group">
                            <label for="filter-customer">Pelanggan</label>
                            <input type="text" id="filter-customer" name="customer" placeholder="Nama pelanggan" value="<?php echo $customer_filter; ?>">
                        </div>
                    </div>
                    <div class="filter-row">
                        <button type="submit" class="btn btn-primary" name="apply-filter">
                            <i class='bx bx-filter-alt'></i> Terapkan Filter
                        </button>
                        <a href="pesanan.php" class="btn btn-outline">
                            <i class='bx bx-reset'></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- <div class="action-buttons">
                <a href="export_pesanan.php" class="btn btn-outline">
                    <i class='bx bx-export'></i> Export
                </a>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class='bx bx-printer'></i> Cetak
                </button>
            </div> -->

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
                        <?php while ($pesanan = mysqli_fetch_assoc($result)): ?>
                            <tr data-order-id="<?php echo $pesanan['id_pesanan']; ?>">
                                <td>ORD-<?php echo str_pad($pesanan['id_pesanan'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $pesanan['nama_pelanggan']; ?></td>
                                <td><?php echo date('d M Y', strtotime($pesanan['tanggal_pesanan'])); ?></td>
                                <td>Rp <?php echo number_format($pesanan['total_belanja'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                        $status_class = '';
                                        switch($pesanan['status']) {
                                            case 'menunggu pembayaran': $status_class = 'pending'; break;
                                            case 'pesanan diproses': $status_class = 'processing'; break;
                                            case 'pesanan dikirim': $status_class = 'shipped'; break;
                                            case 'pesanan diterima': $status_class = 'completed'; break;
                                            case 'pesanan dibatalkan': $status_class = 'cancelled'; break;
                                        }
                                    ?>
                                    <span class="status-badge status-<?php echo $status_class; ?>">
                                        <?php 
                                            switch($pesanan['status']) {
                                                case 'menunggu pembayaran': echo 'Menunggu pembayaran'; break;
                                                case 'pesanan diproses': echo 'Pesanan diproses'; break;
                                                case 'pesanan dikirim': echo 'Pesanan dikirim'; break;
                                                case 'pesanan diterima': echo 'Pesanan diterima'; break;
                                                case 'pesanan dibatalkan': echo 'Pesanan dibatalkan'; break;
                                            }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="order-actions">
                                        <a href="#order-details-<?php echo $pesanan['id_pesanan']; ?>" class="btn-icon view">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        <?php if ($pesanan['status'] != 'pesanan diterima' && $pesanan['status'] != 'pesanan dibatalkan'): ?>
                                            <a href="#edit-order-<?php echo $pesanan['id_pesanan']; ?>" class="btn-icon edit">
                                                <i class='bx bx-edit'></i>
                                            </a>
                                            <?php if ($pesanan['status'] != 'pesanan dibatalkan'): ?>
                                                <a href="#cancel-order-<?php echo $pesanan['id_pesanan']; ?>" class="btn-icon delete">
                                                    <i class='bx bx-x'></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Order Details Modals -->
    <?php 
    // Query ulang untuk modal
    $result = mysqli_query($koneksi, $query);
    while ($pesanan = mysqli_fetch_assoc($result)): 
        // Ambil detail pesanan
        $detail_query = "SELECT dp.*, b.judul, b.harga, b.gambar 
                         FROM detailpesanan dp
                         JOIN buku b ON dp.id_buku = b.id_buku
                         WHERE dp.id_pesanan = " . $pesanan['id_pesanan'];
        $detail_result = mysqli_query($koneksi, $detail_query);
        $detail_items = mysqli_fetch_all($detail_result, MYSQLI_ASSOC);
    ?>
        <div class="modal" id="order-details-<?php echo $pesanan['id_pesanan']; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Detail Pesanan ORD-<?php echo str_pad($pesanan['id_pesanan'], 4, '0', STR_PAD_LEFT); ?></h3>
                    <a href="#" class="close">&times;</a>
                </div>
                <div class="modal-body">
                    <div class="order-details">
                        <div class="order-details-section">
                            <h4>Informasi Pesanan</h4>
                            <div class="order-detail-item">
                                <div class="order-detail-label">ID Pesanan</div>
                                <div class="order-detail-value">ORD-<?php echo str_pad($pesanan['id_pesanan'], 4, '0', STR_PAD_LEFT); ?></div>
                            </div>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Tanggal</div>
                                <div class="order-detail-value"><?php echo date('d M Y H:i', strtotime($pesanan['tanggal_pesanan'])); ?></div>
                            </div>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Status</div>
                                <div class="order-detail-value">
                                    <span class="status-badge status-<?php echo $status_class; ?>">
                                        <?php 
                                            switch($pesanan['status']) {
                                                case 'menunggu pembayaran': echo 'Menunggu pembayaran'; break;
                                                case 'pesanan diproses': echo 'Pesanan diproses'; break;
                                                case 'pesanan dikirim': echo 'Pesanan dikirim'; break;
                                                case 'pesanan diterima': echo 'Pesanan diterima'; break;
                                                case 'pesanan dibatalkan': echo 'Pesanan dibatalkan'; break;
                                            }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Total</div>
                                <div class="order-detail-value">Rp <?php echo number_format($pesanan['total_belanja'], 0, ',', '.'); ?></div>
                            </div>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Metode Pembayaran</div>
                                <div class="order-detail-value"><?php echo $pesanan['metode_pembayaran'] ? $pesanan['metode_pembayaran'] : '-'; ?></div>
                            </div>
                            <?php if ($pesanan['bukti']): ?>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Bukti Pembayaran</div>
                                <div class="order-detail-value">
                                    <a href="../bukti_pembayaran/<?php echo $pesanan['bukti']; ?>" target="_blank">
                                        Lihat Bukti
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="order-details-section">
                            <h4>Informasi Pelanggan</h4>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Nama</div>
                                <div class="order-detail-value"><?php echo $pesanan['nama_pelanggan']; ?></div>
                            </div>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Email</div>
                                <div class="order-detail-value"><?php echo $pesanan['email']; ?></div>
                            </div>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Telepon</div>
                                <div class="order-detail-value"><?php echo $pesanan['no_telepon']; ?></div>
                            </div>
                        </div>
                        
                        <div class="order-details-section">
                            <h4>Alamat Pengiriman</h4>
                            <div class="order-detail-item">
                                <div class="order-detail-value">
                                    <?php echo $pesanan['alamat_lengkap']; ?><br>
                                    <?php echo $pesanan['kabupaten']; ?>, <?php echo $pesanan['provinsi']; ?><br>
                                    <?php echo $pesanan['kode_pos']; ?><br>
                                    Indonesia
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-details-section">
                            <h4>Informasi Pengiriman</h4>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Kurir</div>
                                <div class="order-detail-value"><?php echo $pesanan['metode_pengiriman'] ? $pesanan['metode_pengiriman'] : '-'; ?></div>
                            </div>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Biaya</div>
                                <div class="order-detail-value">Rp <?php echo number_format($pesanan['biaya_pengiriman'], 0, ',', '.'); ?></div>
                            </div>
                            <div class="order-detail-item">
                                <div class="order-detail-label">Estimasi</div>
                                <div class="order-detail-value"><?php echo $pesanan['estimasi'] ? $pesanan['estimasi'] : '-'; ?></div>
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
                        <tbody>
                            <?php foreach ($detail_items as $item): ?>
                                <tr>
                                    <td><?php echo $item['judul']; ?></td>
                                    <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo $item['jumlah']; ?></td>
                                    <td>Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: 500;">Subtotal</td>
                                <td>Rp <?php echo number_format($pesanan['total_belanja'] - $pesanan['biaya_pengiriman'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: 500;">Ongkos Kirim</td>
                                <td>Rp <?php echo number_format($pesanan['biaya_pengiriman'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right; font-weight: 500;">Total</td>
                                <td>Rp <?php echo number_format($pesanan['total_belanja'], 0, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-outline">Tutup</a>
                  
                </div>
            </div>
        </div>

        <!-- Edit Order Modal -->
        <div class="modal" id="edit-order-<?php echo $pesanan['id_pesanan']; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Edit Pesanan ORD-<?php echo str_pad($pesanan['id_pesanan'], 4, '0', STR_PAD_LEFT); ?></h3>
                    <a href="#" class="close">&times;</a>
                </div>
                <form action="update_pesanan.php" method="POST">
                    <input type="hidden" name="id_pesanan" value="<?php echo $pesanan['id_pesanan']; ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit-order-status">Status Pesanan</label>
                            <select id="edit-order-status" name="status" class="form-control" required>
                                <option value="menunggu pembayaran" <?php echo $pesanan['status'] == 'menunggu pembayaran' ? 'selected' : ''; ?>>Menunggu Pembayaran</option>
                                <option value="pesanan diproses" <?php echo $pesanan['status'] == 'pesanan diproses' ? 'selected' : ''; ?>>Pesanan Diproses</option>
                                <option value="pesanan dikirim" <?php echo $pesanan['status'] == 'pesanan dikirim' ? 'selected' : ''; ?>>Pesanan Dikirim</option>
                                <option value="pesanan diterima" <?php echo $pesanan['status'] == 'pesanan diterima' ? 'selected' : ''; ?>>Pesanan Diterima</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cancel Order Modal -->
        <div class="modal" id="cancel-order-<?php echo $pesanan['id_pesanan']; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Batalkan Pesanan ORD-<?php echo str_pad($pesanan['id_pesanan'], 4, '0', STR_PAD_LEFT); ?></h3>
                    <a href="#" class="close">&times;</a>
                </div>
                <form action="cancel_pesanan.php" method="POST">
                    <input type="hidden" name="id_pesanan" value="<?php echo $pesanan['id_pesanan']; ?>">
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin membatalkan pesanan ini?</p>
                        <div class="form-group">
                            <label for="cancel-reason">Alasan Pembatalan</label>
                            <textarea id="cancel-reason" name="alasan" class="form-control" rows="3" placeholder="Masukkan alasan pembatalan" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-danger">Konfirmasi Pembatalan</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endwhile; ?>

    <script>
        // Fungsi untuk menangani modal
        document.addEventListener('DOMContentLoaded', function() {
            // Buka modal ketika link dengan hash diklik
            document.querySelectorAll('a[href^="#"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const modalId = this.getAttribute('href');
                    document.querySelector(modalId).classList.add('show');
                });
            });

            // Tutup modal ketika klik tombol close atau area luar
            document.querySelectorAll('.modal, .close').forEach(element => {
                element.addEventListener('click', function(e) {
                    if (e.target === this || e.target.classList.contains('close')) {
                        this.classList.remove('show');
                    }
                });
            });

            // Mencegah modal tertutup ketika konten modal diklik
            document.querySelectorAll('.modal-content').forEach(content => {
                content.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });

            // Toggle sidebar
            document.getElementById('sidebar-toggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('hide');
            });

            // Dark/light mode toggle
            const switchMode = document.getElementById('switch-mode');
            switchMode.addEventListener('change', function() {
                if(this.checked) {
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

            // Toggle profile dropdown
            document.getElementById('profile-btn').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('profile-dropdown').classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if(!document.getElementById('profile-btn').contains(e.target) && 
                   !document.getElementById('profile-dropdown').contains(e.target)) {
                    document.getElementById('profile-dropdown').classList.remove('show');
                }
            });

            // Logout buttons
            document.getElementById('logout-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Apakah Anda yakin ingin logout?')) {
                    window.location.href = '../logout.php';
                }
            });

            document.getElementById('logout-dropdown-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Apakah Anda yakin ingin logout?')) {
                    window.location.href = '../logout.php';
                }
            });
        });
    </script>
</body>
</html>