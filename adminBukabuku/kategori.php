<?php
session_start();
include('../koneksi.php');



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Toko Buku</title>
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

        /* Categories Table */
        .categories-table {
            width: 100%;
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .categories-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .categories-table th {
            background: var(--light-purple);
            color: var(--purple);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        .categories-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--grey);
            color: var(--dark);
        }
        .categories-table tr:last-child td {
            border-bottom: none;
        }
        .categories-table tr:hover td {
            background: rgba(142, 52, 130, 0.05);
        }
        .category-actions {
            display: flex;
            gap: 8px;
        }
        .category-actions .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .category-actions .btn-icon.edit {
            background: rgba(255, 206, 38, 0.2);
            color: var(--yellow);
        }
        .category-actions .btn-icon.edit:hover {
            background: rgba(255, 206, 38, 0.3);
        }
        .category-actions .btn-icon.delete {
            background: rgba(219, 80, 74, 0.2);
            color: var(--red);
        }
        .category-actions .btn-icon.delete:hover {
            background: rgba(219, 80, 74, 0.3);
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
            max-width: 500px;
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
            .categories-table {
                overflow-x: auto;
                display: block;
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
            <li class="active">
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
                    <img src="image/profile-picture.jpg" alt="Profile Image">
                </a>
            </div>
            
            <!-- Notification Dropdown -->
            <div class="notification-dropdown" id="notification-dropdown">
                <div class="notification-item unread">
                    <p>Pesanan baru dari John Doe</p>
                    <p class="notification-time">2 menit yang lalu</p>
                </div>
                <div class="notification-item unread">
                    <p>Pembayaran diterima untuk pesanan #1234</p>
                    <p class="notification-time">1 jam yang lalu</p>
                </div>
                <div class="notification-item">
                    <p>Pesanan #1235 telah dikirim</p>
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
                <a href="#"><i class='bx bxs-user'></i> Profil Saya</a>
                <a href="#"><i class='bx bxs-cog'></i> Pengaturan</a>
                <a href="#" id="logout-dropdown-btn"><i class='bx bxs-log-out-circle'></i> Logout</a>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Manajemen Kategori</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="dashboard.html">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Kategori</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Alert Message -->
            <div class="alert alert-success" id="success-alert" style="display: none;">
                <i class='bx bx-check-circle'></i>
                <span id="alert-message"></span>
            </div>

            <div class="action-buttons">
                <button class="btn btn-primary" id="add-category-btn">
                    <i class='bx bx-plus'></i> Tambah Kategori
                </button>
                <button class="btn btn-outline" id="export-categories-btn">
                    <i class='bx bx-export'></i> Export
                </button>
            </div>

            <div class="categories-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kategori</th>
                            <th>Nama Kategori</th>
                            <th>Jumlah Buku</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>FIK-001</td>
                            <td>Fiksi</td>
                            <td>128</td>
                            <td>12 Jan 2023</td>
                            <td>
                                <div class="category-actions">
                                    <div class="btn-icon edit" onclick="editCategory(1, 'FIK-001', 'Fiksi', 'Buku-buku fiksi')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                    <div class="btn-icon delete" onclick="showDeleteModal(1, 'Fiksi')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>NFK-002</td>
                            <td>Non-Fiksi</td>
                            <td>95</td>
                            <td>15 Jan 2023</td>
                            <td>
                                <div class="category-actions">
                                    <div class="btn-icon edit" onclick="editCategory(2, 'NFK-002', 'Non-Fiksi', 'Buku-buku non fiksi')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                    <div class="btn-icon delete" onclick="showDeleteModal(2, 'Non-Fiksi')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>ANK-003</td>
                            <td>Anak-anak</td>
                            <td>76</td>
                            <td>20 Feb 2023</td>
                            <td>
                                <div class="category-actions">
                                    <div class="btn-icon edit" onclick="editCategory(3, 'ANK-003', 'Anak-anak', 'Buku untuk anak-anak')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                    <div class="btn-icon delete" onclick="showDeleteModal(3, 'Anak-anak')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>PDK-004</td>
                            <td>Pendidikan</td>
                            <td>112</td>
                            <td>5 Mar 2023</td>
                            <td>
                                <div class="category-actions">
                                    <div class="btn-icon edit" onclick="editCategory(4, 'PDK-004', 'Pendidikan', 'Buku pendidikan')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                    <div class="btn-icon delete" onclick="showDeleteModal(4, 'Pendidikan')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>KMK-005</td>
                            <td>Komik</td>
                            <td>64</td>
                            <td>10 Apr 2023</td>
                            <td>
                                <div class="category-actions">
                                    <div class="btn-icon edit" onclick="editCategory(5, 'KMK-005', 'Komik', 'Buku komik dan manga')">
                                        <i class='bx bx-edit'></i>
                                    </div>
                                    <div class="btn-icon delete" onclick="showDeleteModal(5, 'Komik')">
                                        <i class='bx bx-trash'></i>
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

    <!-- Add/Edit Category Modal -->
    <div class="modal" id="category-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Tambah Kategori Baru</h3>
                <span class="close" onclick="closeModal('category-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="category-code">Kode Kategori</label>
                    <input type="text" id="category-code" class="form-control" placeholder="Masukkan kode kategori">
                </div>
                <div class="form-group">
                    <label for="category-name">Nama Kategori</label>
                    <input type="text" id="category-name" class="form-control" placeholder="Masukkan nama kategori">
                </div>
                <div class="form-group">
                    <label for="category-description">Deskripsi (Opsional)</label>
                    <textarea id="category-description" class="form-control" rows="3" placeholder="Masukkan deskripsi kategori"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('category-modal')">Batal</button>
                <button class="btn btn-primary" id="save-category-btn">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="delete-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Konfirmasi Hapus</h3>
                <span class="close" onclick="closeModal('delete-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <p id="delete-message">Apakah Anda yakin ingin menghapus kategori ini? Semua buku dalam kategori ini akan dipindahkan ke kategori "Belum Terkategori".</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('delete-modal')">Batal</button>
                <button class="btn btn-danger" id="confirm-delete-btn">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const switchMode = document.getElementById('switch-mode');
        const addCategoryBtn = document.getElementById('add-category-btn');
        const exportCategoriesBtn = document.getElementById('export-categories-btn');
        const categoryModal = document.getElementById('category-modal');
        const deleteModal = document.getElementById('delete-modal');
        const saveCategoryBtn = document.getElementById('save-category-btn');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        const modalTitle = document.getElementById('modal-title');
        const categoryCodeInput = document.getElementById('category-code');
        const categoryNameInput = document.getElementById('category-name');
        const categoryDescInput = document.getElementById('category-description');
        const successAlert = document.getElementById('success-alert');
        const alertMessage = document.getElementById('alert-message');
        const deleteMessage = document.getElementById('delete-message');
        const notificationBtn = document.getElementById('notification-btn');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        const markAllReadBtn = document.getElementById('mark-all-read');
        const logoutBtn = document.getElementById('logout-btn');
        const logoutDropdownBtn = document.getElementById('logout-dropdown-btn');

        // Current category id for edit/delete
        let currentCategoryId = null;
        let isEditMode = false;

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

        // Add category button click
        addCategoryBtn.addEventListener('click', () => {
            isEditMode = false;
            modalTitle.textContent = 'Tambah Kategori Baru';
            categoryCodeInput.value = '';
            categoryNameInput.value = '';
            categoryDescInput.value = '';
            currentCategoryId = null;
            categoryModal.classList.add('show');
        });

        // Export categories button click
        exportCategoriesBtn.addEventListener('click', () => {
            showAlert('Data kategori berhasil diexport dalam format CSV', 'success');
        });

        // Save category button click
        saveCategoryBtn.addEventListener('click', () => {
            const categoryCode = categoryCodeInput.value.trim();
            const categoryName = categoryNameInput.value.trim();
            const categoryDesc = categoryDescInput.value.trim();
            
            if(!categoryCode) {
                showAlert('Kode kategori tidak boleh kosong', 'error');
                return;
            }
            
            if(!categoryName) {
                showAlert('Nama kategori tidak boleh kosong', 'error');
                return;
            }
            
            if(isEditMode) {
                showAlert(`Kategori "${categoryName}" (${categoryCode}) berhasil diperbarui`, 'success');
            } else {
                showAlert(`Kategori "${categoryName}" (${categoryCode}) berhasil ditambahkan`, 'success');
            }
            
            closeModal('category-modal');
        });

        // Edit category function
        function editCategory(id, code, name, description) {
            isEditMode = true;
            currentCategoryId = id;
            modalTitle.textContent = 'Edit Kategori';
            categoryCodeInput.value = code;
            categoryNameInput.value = name;
            categoryDescInput.value = description || '';
            categoryModal.classList.add('show');
        }

        // Show delete confirmation modal
        function showDeleteModal(id, name) {
            currentCategoryId = id;
            deleteMessage.textContent = `Apakah Anda yakin ingin menghapus kategori "${name}"? Semua buku dalam kategori ini akan dipindahkan ke kategori "Belum Terkategori".`;
            deleteModal.classList.add('show');
        }

        // Confirm delete button click
        confirmDeleteBtn.addEventListener('click', () => {
            showAlert(`Kategori berhasil dihapus`, 'success');
            closeModal('delete-modal');
            currentCategoryId = null;
        });

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

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if(event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        });
    </script>
</body>
</html>