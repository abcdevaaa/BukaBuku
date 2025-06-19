<?php
session_start();
include ('../koneksi.php');

if (!isset($_SESSION['username'])) {
    header("Location: LoginRegister.php");
    exit();
}

$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$admin = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Toko Buku</title>
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
            --blue: #17a2b8;
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
            border: none;
            box-shadow: none;
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
            margin-bottom: 8px;
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
            font-weight: 500;
        }
        #content main .head-title .left .breadcrumb li i {
            font-size: 1.2rem;
        }

        /* Profile Section */
        .profile-section {
            background: var(--light);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 30px;
        }
        .profile-avatar-container {
            position: relative;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--light-purple);
        }
        .profile-info {
            flex: 1;
            min-width: 200px;
        }
        .profile-info h2 {
            font-size: 24px;
            margin-bottom: 5px;
            color: var(--dark);
        }
        .profile-info p {
            color: var(--dark-grey);
            margin-bottom: 15px;
        }

        /* Buttons */
        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        .btn-primary {
            background: var(--purple);
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background: #7c2d72;
            transform: translateY(-2px);
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--purple);
            color: var(--purple);
        }
        .btn-outline:hover {
            background: var(--light-purple);
            transform: translateY(-2px);
        }
        .btn i {
            font-size: 1.1rem;
        }

        /* Profile Details */
        .profile-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .detail-card {
            background: var(--light);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .detail-card h3 {
            font-size: 18px;
            margin-bottom: 20px;
            color: var(--purple);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .detail-card h3 i {
            font-size: 20px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--grey);
        }
        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .detail-label {
            width: 150px;
            color: var(--dark-grey);
            font-weight: 500;
        }
        .detail-value {
            flex: 1;
            color: var(--dark);
            word-break: break-word;
        }
        .detail-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Alert Message */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
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

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background: var(--light);
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.3s ease;
        }
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--grey);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h3 {
            font-size: 1.5rem;
            color: var(--dark);
        }
        .modal-header .close {
            font-size: 1.8rem;
            cursor: pointer;
            color: var(--dark-grey);
            transition: color 0.3s ease;
        }
        .modal-header .close:hover {
            color: var(--red);
        }
        .modal-body {
            padding: 20px;
        }
        .modal-footer {
            padding: 20px;
            border-top: 1px solid var(--grey);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--grey);
            border-radius: 6px;
            font-family: var(--lato);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--purple);
            box-shadow: 0 0 0 3px rgba(142, 52, 130, 0.2);
        }
        .avatar-preview-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .avatar-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid var(--light-purple);
        }
        .avatar-options {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .avatar-option {
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .avatar-option:hover {
            transform: scale(1.1);
        }
        .avatar-option img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid transparent;
        }
        .avatar-option.selected img {
            border-color: var(--purple);
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
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            .profile-avatar {
                margin-right: 0;
                margin-bottom: 20px;
            }
            .profile-details {
                grid-template-columns: 1fr;
            }
            .modal-content {
                max-width: 90%;
            }
        }

        @media screen and (max-width: 576px) {
            #content nav .profile .name {
                display: none;
            }
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
            .detail-label {
                width: 100%;
            }
            .detail-actions {
                flex-direction: column;
            }
            .modal-footer {
                flex-direction: column;
            }
            .modal-footer .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand" aria-label="Toko Buku Logo">
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
            <li>
                <a href="pelanggan.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Pelanggan</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
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
                    <img src="image/profile-picture.jpg" alt="Profile Image">
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
                    <h1>Profil Saya</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="dashboard.php">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Profil Saya</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Alert Message -->
            <!-- <div class="alert alert-success" role="alert">
                <i class="bx bx-check-circle"></i>
                <span>Profil berhasil diperbarui</span>
            </div> -->

            <!-- Profile Section -->
            <div class="profile-section">
                <div class="profile-header">
                    <?php if (!empty($admin['profil'])) : ?>
                    <img src="image/<?= $admin['profil'] ?>" alt="Foto Profil">
                <?php else: ?>
                    <div class="profile-pic-placeholder">
                        <i class="ri-user-fill" style="font-size: 3rem; color: #666;"></i>
                    </div>
                <?php endif; ?>
                    <div class="profile-info">
                        <h2><?= htmlspecialchars($admin['username']) ?></h2>
                        <p><?= htmlspecialchars($admin['email']) ?></p>
                        <button class="btn btn-outline" onclick="document.getElementById('change-avatar-modal').style.display='flex'">
                            <i class='bx bx-camera'></i> Ubah Foto Profil
                        </button>
                    </div>
                </div>

                <div class="profile-details">
                    <!-- Informasi Pribadi -->
                    <div class="detail-card">
                        <h3><i class='bx bxs-user'></i> Informasi Pribadi</h3>
                        <div class="detail-row">
                            <div class="detail-label">Nama Lengkap</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['username']) ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['email']) ?></div>
                        </div>
                        <div class="detail-actions">
                            <button class="btn btn-primary" onclick="document.getElementById('edit-profile-modal').style.display='flex'">
                                <i class='bx bx-edit'></i> Edit Profil
                            </button>
                            <button class="btn btn-primary" onclick="document.getElementById('change-password-modal').style.display='flex'">
                                <i class='bx bx-key'></i> Ubah Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Edit Profile Modal -->
    <div class="modal" id="edit-profile-modal" role="dialog" aria-labelledby="edit-profile-title" style="display: none;">
        <div class="modal-content">
            <form method="POST" action="profile.php">
                <div class="modal-header">
                    <h3 id="edit-profile-title">Edit Profil</h3>
                    <span class="close" onclick="document.getElementById('edit-profile-modal').style.display='none'" aria-label="Close modal">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="profile-name">Nama Lengkap</label>
                        <input type="text" name="name" id="profile-name-input" class="form-control" value="Admin Toko Buku" required>
                    </div>
                    <div class="form-group">
                        <label for="profile-email">Email</label>
                        <input type="email" name="email" id="profile-email-input" class="form-control" value="admin@tokobuku.com" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('edit-profile-modal').style.display='none'">Batal</button>
                    <button type="submit" name="update_profile" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal" id="change-password-modal" role="dialog" aria-labelledby="change-password-title" style="display: none;">
        <div class="modal-content">
            <form method="POST" action="profile.php">
                <div class="modal-header">
                    <h3 id="change-password-title">Ubah Password</h3>
                    <span class="close" onclick="document.getElementById('change-password-modal').style.display='none'" aria-label="Close modal">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current-password">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current-password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password">Password Baru</label>
                        <input type="password" name="new_password" id="new-password" class="form-control" required>
                        <small class="text-muted">Password minimal 6 karakter</small>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" id="confirm-password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('change-password-modal').style.display='none'">Batal</button>
                    <button type="submit" name="change_password" class="btn btn-primary">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Avatar Modal -->
    <div class="modal" id="change-avatar-modal" role="dialog" aria-labelledby="change-avatar-title" style="display: none;">
        <div class="modal-content">
            <form method="POST" action="profile.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h3 id="change-avatar-title">Ubah Foto Profil</h3>
                    <span class="close" onclick="document.getElementById('change-avatar-modal').style.display='none'" aria-label="Close modal">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="avatar-preview-container">
                        <img src="image/profile-picture.jpg" alt="Current Avatar Preview" class="avatar-preview">
                    </div>
                    <div class="form-group">
                        <label for="avatar-upload">Unggah Foto Baru</label>
                        <input type="file" name="avatar_upload" id="avatar-upload" class="form-control" accept="image/*">
                    </div>
                    <div class="avatar-options">
                        <div class="avatar-option">
                            <input type="radio" name="avatar_url" id="avatar1" value="https://randomuser.me/api/portraits/men/1.jpg" style="display: none;">
                            <label for="avatar1" style="cursor: pointer;">
                                <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Avatar Option 1">
                            </label>
                        </div>
                        <div class="avatar-option">
                            <input type="radio" name="avatar_url" id="avatar2" value="https://randomuser.me/api/portraits/women/1.jpg" style="display: none;">
                            <label for="avatar2" style="cursor: pointer;">
                                <img src="https://randomuser.me/api/portraits/women/1.jpg" alt="Avatar Option 2">
                            </label>
                        </div>
                        <div class="avatar-option">
                            <input type="radio" name="avatar_url" id="avatar3" value="https://randomuser.me/api/portraits/men/2.jpg" style="display: none;">
                            <label for="avatar3" style="cursor: pointer;">
                                <img src="https://randomuser.me/api/portraits/men/2.jpg" alt="Avatar Option 3">
                            </label>
                        </div>
                        <div class="avatar-option">
                            <input type="radio" name="avatar_url" id="avatar4" value="https://randomuser.me/api/portraits/women/2.jpg" style="display: none;">
                            <label for="avatar4" style="cursor: pointer;">
                                <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Avatar Option 4">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('change-avatar-modal').style.display='none'">Batal</button>
                    <button type="submit" name="change_avatar" class="btn btn-primary">Simpan Foto Profil</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Minimal JavaScript for UI interactions
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            document.getElementById('sidebar-toggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('hide');
            });
            
            // Toggle profile dropdown
            profileBtn.addEventListener('click', (e) => {
                e.preventDefault();
                profileDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if(!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.remove('show');
                }
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
            
            // Preview uploaded avatar image
            document.getElementById('avatar-upload').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.querySelector('#change-avatar-modal .avatar-preview').src = event.target.result;
                        
                        // Uncheck any selected radio buttons
                        document.querySelectorAll('input[name="avatar_url"]').forEach(radio => {
                            radio.checked = false;
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Update preview when avatar option is selected
            document.querySelectorAll('input[name="avatar_url"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        document.querySelector('#change-avatar-modal .avatar-preview').src = this.value;
                        document.getElementById('avatar-upload').value = '';
                    }
                });
            });
        });
    </script>
</body>
</html>