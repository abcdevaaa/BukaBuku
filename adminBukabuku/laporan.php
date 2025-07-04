<?php
session_start();
include('../koneksi.php');

// Base query without ORDER BY
$query = "SELECT 
            p.id_pesanan, 
            p.tanggal_pesanan, 
            p.total_belanja, 
            p.status, 
            u.username AS nama_pelanggan,
            mp.nama_metode AS metode_pembayaran
          FROM pesanan p
          JOIN users u ON p.id_users = u.id_users
          LEFT JOIN metode_pembayaran mp ON p.metode_pembayaran = mp.id_metodePembayaran";

// Get filter values
$month_filter = isset($_GET['month-filter']) ? $_GET['month-filter'] : date('m');
$year_filter = isset($_GET['year-filter']) ? $_GET['year-filter'] : date('Y');

// Build WHERE conditions array
$where_conditions = [];

// Add month filter
if ($month_filter != 'all') {
    $where_conditions[] = "MONTH(p.tanggal_pesanan) = '$month_filter'";
}

// Add year filter
if ($year_filter != 'all') {
    $where_conditions[] = "YEAR(p.tanggal_pesanan) = '$year_filter'";
}

// Combine WHERE conditions if any exist
if (!empty($where_conditions)) {
    $query .= " WHERE " . implode(" AND ", $where_conditions);
}

// Add ORDER BY at the end
$query .= " ORDER BY p.tanggal_pesanan DESC";

// Execute query with error handling
$result = mysqli_query($koneksi, $query);
if (!$result) {
    die('Query error: ' . mysqli_error($koneksi));
}

// Query untuk menghitung total
$total_query = "SELECT 
                COUNT(CASE WHEN p.status != 'pesanan dibatalkan' THEN 1 ELSE NULL END) as total_orders,
                SUM(CASE WHEN p.status != 'pesanan dibatalkan' THEN p.total_belanja ELSE 0 END) as total_revenue,
                COUNT(CASE WHEN p.status = 'pesanan dibatalkan' THEN 1 ELSE NULL END) as canceled_orders
                FROM pesanan p";
                
// Tambahkan kondisi WHERE yang sama dengan query utama
if (!empty($where_conditions)) {
    $total_query .= " WHERE " . implode(" AND ", $where_conditions);
}

$total_result = mysqli_query($koneksi, $total_query);
$total_data = mysqli_fetch_assoc($total_result);

// Query for all orders (for modals)
$all_orders_query = "SELECT 
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

$all_orders_result = mysqli_query($koneksi, $all_orders_query);
if (!$all_orders_result) {
    die('Query error: ' . mysqli_error($koneksi));
}

$all_orders = [];
while ($row = mysqli_fetch_assoc($all_orders_result)) {
    $all_orders[$row['id_pesanan']] = $row;
}

// Get order items
$items_query = "SELECT dp.id_pesanan, dp.*, b.judul, b.harga 
                FROM detailpesanan dp
                JOIN buku b ON dp.id_buku = b.id_buku";
$items_result = mysqli_query($koneksi, $items_query);
if (!$items_result) {
    die('Query error: ' . mysqli_error($koneksi));
}

$all_items = [];
while ($item = mysqli_fetch_assoc($items_result)) {
    $all_items[$item['id_pesanan']][] = $item;
}

// Get distinct years for filter
$years_query = "SELECT DISTINCT YEAR(tanggal_pesanan) as year FROM pesanan ORDER BY year DESC";
$years_result = mysqli_query($koneksi, $years_query);
$years = [];
while ($row = mysqli_fetch_assoc($years_result)) {
    $years[] = $row['year'];
}

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
    <title>Laporan Penjualan - Toko Buku</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <style>
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
        
        /* Filter Section */
        .filter-section {
            background: var(--light);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .filter-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
        }
        .filter-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--grey);
            border-radius: 6px;
            font-family: var(--lato);
            transition: border 0.3s ease;
        }

        .filter-control:focus {
            outline: none;
            border-color: var(--purple);
        }

        .btn-primary {
            margin-top: 24px;
            width: 100%;
            padding: 10px 12px;
            border: 0px solid var(--grey);
            background: var(--grey);
            border-radius: 6px;
            font-family: var(--lato);
        }

        .btn-primary:hover {
            background: var(--purple);
            color: #fff;
        }

        /* Summary Cards */
        .summary-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
        }
        .summary-card {
            flex: 1;
            background: var(--light);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .summary-card h3 {
            font-size: 16px;
            color: var(--dark-grey);
            margin-bottom: 10px;
        }
        .summary-card p {
            font-size: 24px;
            font-weight: 600;
            color: var(--purple);
        }

        /* Sales Table */
        .sales-table {
            width: 100%;
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .sales-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .sales-table th {
            background: var(--light-purple);
            color: var(--purple);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        .sales-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--grey);
            color: var(--dark);
        }
        .sales-table tr:last-child td {
            border-bottom: none;
        }
        .sales-table tr:hover td {
            background: rgba(142, 52, 130, 0.05);
        }
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status.completed {
            background: #d4edda;
            color: #155724;
        }
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .status.canceled {
            background: #f8d7da;
            color: #721c24;
        }
        .status.processing {
            background: #cce5ff;
            color: #004085;
        }
        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-icon.edit {
            background: rgba(255, 206, 38, 0.2);
            color: var(--yellow);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .btn-icon.edit:hover {
            background: rgba(255, 206, 38, 0.3);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 5px;
        }
        .page-item {
            list-style: none;
        }
        .page-link {
            display: block;
            padding: 8px 12px;
            border: 1px solid var(--grey);
            border-radius: 4px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .page-link:hover {
            background: var(--light-purple);
        }
        .page-item.active .page-link {
            background: var(--purple);
            color: white;
            border-color: var(--purple);
        }
        .page-item.disabled .page-link {
            color: var(--dark-grey);
            pointer-events: none;
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
            max-height: 70vh;
            overflow-y: auto;
        }
        .order-details {
            margin-bottom: 20px;
        }
        .order-details h4 {
            margin-bottom: 10px;
            color: var(--dark);
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .order-summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--grey);
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .summary-row.total {
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--grey);
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
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

        /* Responsive */
        @media screen and (max-width: 768px) {
            #sidebar {
                width: 200px;
            }
            #content {
                width: calc(100% - 60px);
                left: 200px;
            }
            .sales-table {
                overflow-x: auto;
                display: block;
            }
            .filter-row {
                flex-direction: column;
                gap: 15px;
            }
            .filter-group {
                width: 100%;
            }
            .summary-cards {
                flex-direction: column;
            }
        }

        @media screen and (max-width: 576px) {
            #content nav .profile .name {
                display: none;
            }
            .modal-content {
                width: 95%;
                padding: 16px;
            }
        }

        .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            grid-gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .head-title .left {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .head-title .right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-family: var(--lato);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--purple);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #7a2d70;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn i {
            font-size: 1.1rem;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #content, #content * {
                visibility: visible;
            }
            #content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                margin: 0;
            }
            nav, #sidebar, .filter-section, .head-title, .summary-cards, .modal {
                display: none !important;
            }
            .sales-table {
                width: 100% !important;
                margin-top: 0;
            }
            .print-header, .print-footer {
                display: block !important;
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
            <li>
                <a href="pesanan.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Pesanan</span>
                </a>
            </li>
            <li class="active">
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
                    <h1>Laporan Penjualan</h1>
                </div>

                <div class="right">
                    <button onclick="printReport()" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 5px;">
                        <i class='bx bx-printer'></i> Cetak Laporan
                    </button>
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
                            <label for="month-filter">Bulan</label>
                            <select id="month-filter" name="month-filter" class="filter-control">
                                <option value="all" <?php echo $month_filter == 'all' ? 'selected' : ''; ?>>Semua Bulan</option>
                                <option value="01" <?php echo $month_filter == '01' ? 'selected' : ''; ?>>Januari</option>
                                <option value="02" <?php echo $month_filter == '02' ? 'selected' : ''; ?>>Februari</option>
                                <option value="03" <?php echo $month_filter == '03' ? 'selected' : ''; ?>>Maret</option>
                                <option value="04" <?php echo $month_filter == '04' ? 'selected' : ''; ?>>April</option>
                                <option value="05" <?php echo $month_filter == '05' ? 'selected' : ''; ?>>Mei</option>
                                <option value="06" <?php echo $month_filter == '06' ? 'selected' : ''; ?>>Juni</option>
                                <option value="07" <?php echo $month_filter == '07' ? 'selected' : ''; ?>>Juli</option>
                                <option value="08" <?php echo $month_filter == '08' ? 'selected' : ''; ?>>Agustus</option>
                                <option value="09" <?php echo $month_filter == '09' ? 'selected' : ''; ?>>September</option>
                                <option value="10" <?php echo $month_filter == '10' ? 'selected' : ''; ?>>Oktober</option>
                                <option value="11" <?php echo $month_filter == '11' ? 'selected' : ''; ?>>November</option>
                                <option value="12" <?php echo $month_filter == '12' ? 'selected' : ''; ?>>Desember</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="year-filter">Tahun</label>
                            <select id="year-filter" name="year-filter" class="filter-control">
                                <option value="all" <?php echo $year_filter == 'all' ? 'selected' : ''; ?>>Semua Tahun</option>
                                <?php foreach ($years as $year): ?>
                                    <option value="<?php echo $year; ?>" <?php echo $year_filter == $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="filter-group">
                            <button type="submit" class="btn btn-primary" style="margin-top: 24px;">
                                <i class='bx bx-filter-alt'></i> Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Total Summary -->
            <div class="summary-cards">
                <div class="summary-card">
                    <h3>Total Pesanan</h3>
                    <p><?php echo $total_data['total_orders']; ?></p>
                </div>
                <div class="summary-card">
                    <h3>Pesanan Dibatalkan</h3>
                    <p><?php echo $total_data['canceled_orders']; ?></p>
                </div>
                <div class="summary-card">
                    <h3>Total Pendapatan</h3>
                    <p>Rp <?php echo number_format($total_data['total_revenue'], 0, ',', '.'); ?></p>
                </div>
            </div>
                                    
            <!-- Sales Table -->
            <div class="sales-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($pesanan = mysqli_fetch_assoc($result)): 
                            $status_class = '';
                            $status_text = '';
                            
                            switch($pesanan['status']) {
                                case 'pesanan diterima':
                                    $status_class = 'completed';
                                    $status_text = 'Selesai';
                                    break;
                                case 'pesanan dikirim':
                                    $status_class = 'processing';
                                    $status_text = 'Diproses';
                                    break;
                                case 'menunggu pembayaran':
                                    $status_class = 'pending';
                                    $status_text = 'Pending';
                                    break;
                                case 'pesanan dibatalkan':
                                    $status_class = 'canceled';
                                    $status_text = 'Dibatalkan';
                                    break;
                                case 'pesanan diproses':
                                    $status_class = 'processing';
                                    $status_text = 'Diproses';
                                    break;
                            }
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>#ORD-<?php echo str_pad($pesanan['id_pesanan'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo date('d M Y', strtotime($pesanan['tanggal_pesanan'])); ?></td>
                            <td><?php echo $pesanan['nama_pelanggan']; ?></td>
                            <td>Rp <?php echo number_format($pesanan['total_belanja'], 0, ',', '.'); ?></td>
                            <td><span class="status <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                            <td>
                                <a href="#order-details-<?php echo $pesanan['id_pesanan']; ?>" class="btn-icon edit">
                                    <i class='bx bx-show'></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <!-- <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul> -->
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Order Details Modals -->
    <?php foreach ($all_orders as $id_pesanan => $pesanan): ?>
        <?php 
        $items = isset($all_items[$id_pesanan]) ? $all_items[$id_pesanan] : [];
        $subtotal = $pesanan['total_belanja'] - $pesanan['biaya_pengiriman'];
        ?>
        <div class="modal" id="order-details-<?php echo $id_pesanan; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Detail Pesanan ORD-<?php echo str_pad($id_pesanan, 4, '0', STR_PAD_LEFT); ?></h3>
                    <a href="#" class="close">&times;</a>
                </div>
                <div class="modal-body">
                    <div class="order-details">
                        <h4>Informasi Pesanan</h4>
                        <div class="summary-row">
                            <span>Tanggal Pesanan:</span>
                            <span><?php echo date('d M Y H:i', strtotime($pesanan['tanggal_pesanan'])); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Status:</span>
                            <span class="status <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Metode Pembayaran:</span>
                            <span><?php echo $pesanan['metode_pembayaran'] ? $pesanan['metode_pembayaran'] : '-'; ?></span>
                        </div>
                        <?php if ($pesanan['bukti']): ?>
                        <div class="summary-row">
                            <span>Bukti Pembayaran:</span>
                            <span>
                                <a href="../bukti_pembayaran/<?php echo $pesanan['bukti']; ?>" target="_blank">
                                    Lihat Bukti
                                </a>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="order-details">
                        <h4>Informasi Pelanggan</h4>
                        <div class="summary-row">
                            <span>Nama:</span>
                            <span><?php echo $pesanan['nama_pelanggan']; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Email:</span>
                            <span><?php echo $pesanan['email']; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Telepon:</span>
                            <span><?php echo $pesanan['no_telepon']; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Alamat:</span>
                            <span>
                                <?php echo $pesanan['alamat_lengkap']; ?>, 
                                <?php echo $pesanan['kabupaten']; ?>, 
                                <?php echo $pesanan['provinsi']; ?> 
                                <?php echo $pesanan['kode_pos']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="order-details">
                        <h4>Informasi Pengiriman</h4>
                        <div class="summary-row">
                            <span>Kurir:</span>
                            <span><?php echo $pesanan['metode_pengiriman'] ? $pesanan['metode_pengiriman'] : '-'; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Biaya:</span>
                            <span>Rp <?php echo number_format($pesanan['biaya_pengiriman'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Estimasi:</span>
                            <span><?php echo $pesanan['estimasi'] ? $pesanan['estimasi'] : '-'; ?></span>
                        </div>
                    </div>

                    <div class="order-details">
                        <h4>Item Pesanan</h4>
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
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?php echo $item['judul']; ?></td>
                                    <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo $item['jumlah']; ?></td>
                                    <td>Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Ongkos Kirim:</span>
                            <span>Rp <?php echo number_format($pesanan['biaya_pengiriman'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Diskon:</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>Rp <?php echo number_format($pesanan['total_belanja'], 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-outline">Tutup</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

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
        

        // Fungsi untuk mencetak laporan utama
        function printReport() {
            // Clone elemen yang ingin dicetak
            const printContent = document.querySelector('.sales-table').cloneNode(true);
            
            // Buat elemen header untuk cetakan
            const printHeader = document.createElement('div');
            printHeader.className = 'print-header';
            printHeader.innerHTML = `
                <h2>Laporan Penjualan Toko BukaBuku</h2>
                <p>Periode: ${getSelectedMonth()} ${getSelectedYear()}</p>
                <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                <hr>
            `;
            
            // Buat elemen footer untuk cetakan
            const printFooter = document.createElement('div');
            printFooter.className = 'print-footer';
            printFooter.innerHTML = `
                <hr>
                <p>Dicetak oleh: <?php echo $_SESSION['username'] ?? 'Admin'; ?></p>
            `;

            // Buka window baru untuk cetak
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Laporan Penjualan - ${getSelectedMonth()} ${getSelectedYear()}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .print-header { text-align: center; margin-bottom: 20px; }
                        .print-footer { text-align: right; margin-top: 20px; font-size: 12px; }
                        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        hr { border: 0.5px solid #eee; }
                        .status { 
                            display: inline-block; 
                            padding: 3px 8px; 
                            border-radius: 3px; 
                            font-size: 12px; 
                            font-weight: bold; 
                        }
                        .status.completed { background: #d4edda; color: #155724; }
                        .status.pending { background: #fff3cd; color: #856404; }
                        .status.canceled { background: #f8d7da; color: #721c24; }
                        .status.processing { background: #cce5ff; color: #004085; }
                    </style>
                </head>
                <body>
                    ${printHeader.outerHTML}
                    ${printContent.outerHTML}
                    ${printFooter.outerHTML}
                </body>
                </html>
            `);
            printWindow.document.close();
            
            // Fokus ke window cetak dan jalankan print
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }

        // Fungsi pembantu untuk mendapatkan bulan terpilih
        function getSelectedMonth() {
            const monthSelect = document.getElementById('month-filter');
            const selectedMonth = monthSelect.options[monthSelect.selectedIndex].text;
            return monthSelect.value === 'all' ? 'Semua Bulan' : selectedMonth;
        }

        // Fungsi pembantu untuk mendapatkan tahun terpilih
        function getSelectedYear() {
            const yearSelect = document.getElementById('year-filter');
            return yearSelect.value === 'all' ? '' : yearSelect.value;
        }


        // Fungsi untuk mencetak detail pesanan
        function printOrderDetail(orderId) {
            const modalContent = document.querySelector(`#order-details-${orderId} .modal-content`).cloneNode(true);
            
            // Hapus tombol aksi yang tidak perlu di cetak
            const modalFooter = modalContent.querySelector('.modal-footer');
            if (modalFooter) modalFooter.remove();
            
            // Buat window baru untuk cetak
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Detail Pesanan #ORD-${orderId.toString().padStart(4, '0')}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h3 { color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; }
                        .order-details { margin-bottom: 20px; }
                        .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
                        .summary-row.total { font-weight: bold; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px; }
                        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        .status { 
                            display: inline-block; 
                            padding: 3px 8px; 
                            border-radius: 3px; 
                            font-size: 12px; 
                            font-weight: bold; 
                        }
                        .print-footer { text-align: right; margin-top: 20px; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    ${modalContent.innerHTML}
                    <div class="print-footer">
                        Dicetak pada: ${new Date().toLocaleString('id-ID')}
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }
    </script>
</body>
</html>