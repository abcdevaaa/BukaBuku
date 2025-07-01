<?php
session_start();
include('../koneksi.php');

// Fungsi untuk menampilkan pesan alert
function showAlert($message, $type) {
    echo '<div class="alert alert-'.$type.'">
            <i class="bx '.($type == 'success' ? 'bx-check-circle' : 'bx-error').'"></i>
            <span>'.$message.'</span>
          </div>';
}

// Proses tambah kategori
if (isset($_POST['add_category'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    
    // Upload foto
    $foto = '';
    if ($_FILES['foto']['name']) {
        $target_dir = "../image/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false) {
            // Generate unique filename
            $foto = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $foto;
            
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                // File uploaded successfully
            } else {
                showAlert("Maaf, terjadi error saat upload file.", "danger");
            }
        } else {
            showAlert("File yang diupload bukan gambar.", "danger");
        }
    }
    
    $sql = "INSERT INTO kategori (nama_kategori, deskripsi, foto) 
            VALUES ('$nama', '$deskripsi', '$foto')";
    
    if (mysqli_query($koneksi, $sql)) {
        showAlert("Kategori berhasil ditambahkan", "success");
    } else {
        showAlert("Error: " . mysqli_error($koneksi), "danger");
    }
}

// Proses edit kategori
if (isset($_POST['edit_category'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    
    // Jika ada file foto baru diupload
    if ($_FILES['foto']['name']) {
        $target_dir = "../image/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false) {
            // Generate unique filename
            $foto = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $foto;
            
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                // Hapus foto lama jika ada
                $sql_old = "SELECT foto FROM kategori WHERE id_kategori = $id";
                $result = mysqli_query($conn, $sql_old);
                $row = mysqli_fetch_assoc($result);
                if ($row['foto'] && file_exists($target_dir . $row['foto'])) {
                    unlink($target_dir . $row['foto']);
                }
            } else {
                showAlert("Maaf, terjadi error saat upload file.", "danger");
            }
        } else {
            showAlert("File yang diupload bukan gambar.", "danger");
        }
    } else {
        // Jika tidak ada file baru, tetap gunakan foto lama
        $foto = $_POST['foto_lama'];
    }
    
    $sql = "UPDATE kategori SET  
            nama_kategori = '$nama', 
            deskripsi = '$deskripsi', 
            foto = '$foto' 
            WHERE id_kategori = $id";
    
    if (mysqli_query($koneksi, $sql)) {
        showAlert("Kategori berhasil diperbarui", "success");
    } else {
        showAlert("Error: " . mysqli_error($koneksi), "danger");
    }
}

// Proses hapus kategori
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Hapus foto jika ada
    $sql_foto = "SELECT foto FROM kategori WHERE id_kategori = $id";
    $result = mysqli_query($koneksi, $sql_foto);
    $row = mysqli_fetch_assoc($result);
    if ($row['foto']) {
        $target_dir = "../image/";
        if (file_exists($target_dir . $row['foto'])) {
            unlink($target_dir . $row['foto']);
        }
    }
    
    $sql = "DELETE FROM kategori WHERE id_kategori = $id";
    if (mysqli_query($koneksi, $sql)) {
        showAlert("Kategori berhasil dihapus", "success");
    } else {
        showAlert("Error: " . mysqli_error($koneksi), "danger");
    }
}

// Ambil data kategori dari database
$sql = "SELECT k.*, COUNT(b.id_buku) as jumlah_buku 
        FROM kategori k 
        LEFT JOIN buku b ON k.id_kategori = b.id_kategori 
        GROUP BY k.id_kategori";
$result = mysqli_query($koneksi, $sql);
$kategories = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Cek apakah modal tambah/edit harus ditampilkan
$show_modal = isset($_GET['add']) || isset($_GET['edit']);
$modal_type = isset($_GET['add']) ? 'add' : (isset($_GET['edit']) ? 'edit' : '');
$edit_data = [];

if ($modal_type == 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM kategori WHERE id_kategori = $id";
    $result = mysqli_query($koneksi, $sql);
    $edit_data = mysqli_fetch_assoc($result);
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
                    <h1>Manajemen Kategori</h1>
                </div>
            </div>

            <!-- Alert Message -->
            <?php 
            if (isset($_SESSION['alert'])) {
                showAlert($_SESSION['alert']['message'], $_SESSION['alert']['type']);
                unset($_SESSION['alert']);
            }
            ?>

            <div class="action-buttons">
                <a href="?add=1" class="btn btn-primary">
                    <i class='bx bx-plus'></i> Tambah Kategori
                </a>
            </div>

            <div class="categories-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID Kategori</th>
                            <th>Foto</th>
                            <th>Nama Kategori</th>
                            <th>Jumlah Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kategories as $kategori): ?>
                        <tr>
                            <td><?= $kategori['id_kategori'] ?></td>
                            <td>
                                <?php if ($kategori['foto']): ?>
                                <img src="../image/<?= $kategori['foto'] ?>" alt="<?= $kategori['nama_kategori'] ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                <span>-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $kategori['nama_kategori'] ?></td>
                            <td><?= $kategori['jumlah_buku'] ?></td>
                            <td>
                                <div class="category-actions">
                                    <a href="?edit=1&id=<?= $kategori['id_kategori'] ?>" class="btn-icon edit">
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <a href="?delete=<?= $kategori['id_kategori'] ?>" class="btn-icon delete" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        <i class='bx bx-trash'></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                              
                    </tbody>
                </table>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal untuk Tambah/Edit Kategori -->
    <?php if ($show_modal): ?>
    <div class="modal show" id="category-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><?= ($modal_type == 'add' ? 'Tambah Kategori Baru' : 'Edit Kategori') ?></h3>
                <a href="kategori.php" class="close">&times;</a>
            </div>
            <form method="POST" action="kategori.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php if ($modal_type == 'edit'): ?>
                    <input type="hidden" name="id" value="<?= $edit_data['id_kategori'] ?>">
                    <input type="hidden" name="foto_lama" value="<?= $edit_data['foto'] ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="nama">Nama Kategori</label>
                        <input type="text" id="nama" name="nama" class="form-control" 
                               value="<?= $modal_type == 'edit' ? $edit_data['nama_kategori'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi (Opsional)</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= $modal_type == 'edit' ? $edit_data['deskripsi'] : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Kategori</label>
                        <input type="file" id="foto" name="foto" class="form-control">
                        <?php if ($modal_type == 'edit' && $edit_data['foto']): ?>
                        <div style="margin-top: 10px;">
                            <img src="../image/<?= $edit_data['foto'] ?>" alt="Foto Kategori" style="max-width: 100px; max-height: 100px;">
                            <p>Foto saat ini</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="kategori.php" class="btn btn-outline">Batal</a>
                    <button type="submit" name="<?= $modal_type == 'add' ? 'add_category' : 'edit_category' ?>" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

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