<?php
session_start();
include('../koneksi.php');

// Inisialisasi variabel pesan
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Ambil parameter pencarian/filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT buku.*, kategori.nama_kategori FROM buku JOIN kategori ON buku.id_kategori = kategori.id_kategori";

// Tambahkan kondisi WHERE berdasarkan filter
$where_conditions = [];
if (!empty($search)) {
    $where_conditions[] = "(buku.judul LIKE '%" . mysqli_real_escape_string($koneksi, $search) . "%' OR buku.penulis LIKE '%" . mysqli_real_escape_string($koneksi, $search) . "%')";
}
if (!empty($category_filter)) {
    $where_conditions[] = "buku.id_kategori = " . intval($category_filter);
}
if (!empty($status_filter)) {
    if ($status_filter == 'available') {
        $where_conditions[] = "buku.stok > 3";
    } elseif ($status_filter == 'low-stock') {
        $where_conditions[] = "buku.stok BETWEEN 1 AND 3";
    } elseif ($status_filter == 'out-of-stock') {
        $where_conditions[] = "buku.stok = 0";
    }
}

if (!empty($where_conditions)) {
    $query .= " WHERE " . implode(" AND ", $where_conditions);
}

// Eksekusi query
$result = mysqli_query($koneksi, $query);

// Ambil data kategori untuk dropdown
$kategori_options = '';
$kategori_query = mysqli_query($koneksi, "SELECT * FROM kategori");
while($kat = mysqli_fetch_assoc($kategori_query)) {
    $selected = ($category_filter == $kat['id_kategori']) ? 'selected' : '';
    $kategori_options .= "<option value='{$kat['id_kategori']}' $selected>{$kat['nama_kategori']}</option>";
}


$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_buku = isset($_GET['id_buku']) ? intval($_GET['id_buku']) : 0;


if ($action && $id_buku) {
    $buku_data = mysqli_fetch_assoc(mysqli_query($koneksi, 
        "SELECT buku.*, kategori.nama_kategori 
         FROM buku 
         JOIN kategori ON buku.id_kategori = kategori.id_kategori 
         WHERE buku.id_buku = $id_buku"));
}

// Tangani form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_book'])) {
        // Proses simpan buku (tambah/edit)
        $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
        $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
        $id_kategori = intval($_POST['kategori']);
        $isbn = mysqli_real_escape_string($koneksi, $_POST['isbn']);
        $harga = intval($_POST['harga']);
        $stok = intval($_POST['stok']);
        $tanggal_terbit = mysqli_real_escape_string($koneksi, $_POST['tanggal_terbit']);
        $jumlah_halaman = intval($_POST['jumlah_halaman']);
        $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
        $bahasa = mysqli_real_escape_string($koneksi, $_POST['bahasa']);
        $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
        
        // Handle file upload
        $gambar = isset($buku_data['gambar']) ? $buku_data['gambar'] : '';
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
            $target_dir = "../image/";
            $target_file = $target_dir . basename($_FILES["cover"]["name"]);
            
            // Generate unique filename
            $file_ext = pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
                $gambar = $new_filename;
                
                // Hapus gambar lama jika edit
                if (isset($buku_data['gambar']) && !empty($buku_data['gambar']) && file_exists("../image/" . $buku_data['gambar'])) {
                    unlink("../image/" . $buku_data['gambar']);
                }
            }
        }
        
        if (isset($_POST['id_buku']) && $_POST['id_buku']) {
            // Edit buku
            $id_buku = intval($_POST['id_buku']);
            $update_query = "UPDATE buku SET 
                judul = '$judul',
                penulis = '$penulis',
                id_kategori = $id_kategori,
                isbn = '$isbn',
                harga = $harga,
                stok = $stok,
                tanggal_terbit = '$tanggal_terbit',
                jumlah_halaman = $jumlah_halaman,
                penerbit = '$penerbit',
                bahasa = '$bahasa',
                deskripsi = '$deskripsi'";
            
            if ($gambar) {
                $update_query .= ", gambar = '$gambar'";
            }
            
            $update_query .= " WHERE id_buku = $id_buku";
            
            mysqli_query($koneksi, $update_query);
            $message = "Buku berhasil diperbarui!";
        } else {
            // Tambah buku baru
            $insert_query = "INSERT INTO buku (
                judul, penulis, id_kategori, isbn, harga, stok, tanggal_terbit, 
                jumlah_halaman, penerbit, bahasa, deskripsi, gambar
            ) VALUES (
                '$judul', '$penulis', $id_kategori, '$isbn', $harga, $stok, 
                '$tanggal_terbit', $jumlah_halaman, '$penerbit', '$bahasa', 
                '$deskripsi', '$gambar'
            )";
            
            mysqli_query($koneksi, $insert_query);
            $message = "Buku berhasil ditambahkan!";
        }
        
        header("Location:buku.php?message=" . urlencode($message));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manajemen Buku</title>
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

        /* MAIN CONTENT */
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
        }
        #content main .head-title .left h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 10px;
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
            pointer-events: none;
        }
        #content main .head-title .left .breadcrumb li a.active {
            color: var(--purple);
            pointer-events: unset;
        }

        /* BOOK MANAGEMENT STYLES */
        .book-actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--purple);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--light-purple);
            color: var(--purple);
        }
        
        .btn-danger {
            background: var(--red);
            color: white;
        }
        
        .btn-danger:hover {
            opacity: 0.9;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            opacity: 0.9;
        }
        
        .search-filter {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .search-filter input, .search-filter select {
            padding: 10px 16px;
            border-radius: 6px;
            border: 1px solid var(--grey);
            background: var(--light);
            color: var(--dark);
            font-family: var(--lato);
        }
        
        .book-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .book-table th, .book-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--grey);
        }
        
        .book-table th {
            background: var(--purple);
            color: white;
            font-weight: 600;
        }
        
        .book-table tr:last-child td {
            border-bottom: none;
        }
        
        .book-table tr:hover {
            background: var(--grey);
        }
        
        .book-cover {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-available {
            background: #d4edda;
            color: #155724;
        }
        
        .status-out-of-stock {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-limited {
            background: #fff3cd;
            color: #856404;
        }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .edit-btn {
            background: var(--light-yellow);
            color: var(--yellow);
        }
        
        .edit-btn:hover {
            background: var(--yellow);
            color: white;
        }
        
        .delete-btn {
            background: var(--light-orange);
            color: var(--orange);
        }
        
        .delete-btn:hover {
            background: var(--orange);
            color: white;
        }
        
        .view-btn {
            background: var(--light-purple);
            color: var(--purple);
        }
        
        .view-btn:hover {
            background: var(--purple);
            color: white;
        }

        /* PAGINATION */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 24px;
            gap: 8px;
        }
        
        .page-item {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: var(--light);
            color: var(--dark);
            transition: all 0.3s ease;
        }
        
        .page-item.active {
            background: var(--purple);
            color: white;
        }
        
        .page-item:hover:not(.active) {
            background: var(--grey);
        }
        
        .page-item.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* MODAL STYLES */
        .modal {
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
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--grey);
        }
        
        .modal-header h3 {
            font-size: 1.5rem;
            color: var(--dark);
        }
        
        .modal-header .close {
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--dark);
        }
        
        .modal-body {
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 16px;
            border-radius: 6px;
            border: 1px solid var(--grey);
            background: var(--light);
            color: var(--dark);
            font-family: var(--lato);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--purple);
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 16px;
            border-top: 1px solid var(--grey);
        }
        
        /* Message styles */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        /* Responsive styles */
        @media screen and (max-width: 768px) {
            #sidebar {
                width: 200px;
            }

            #content {
                width: calc(100% - 60px);
                left: 200px;
            }

            #content nav .nav-link {
                display: none;
            }
            
            .book-table {
                display: block;
                overflow-x: auto;
            }
        }

        @media screen and (max-width: 576px) {
            #content nav form .form-input input {
                display: none;
            }

            #content nav form .form-input button {
                width: auto;
                height: auto;
                background: transparent;
                border-radius: none;
                color: var(--dark);
            }

            #content nav form.show .form-input input {
                display: block;
                width: 100%;
            }
            
            #content nav form.show .form-input button {
                width: 36px;
                height: 100%;
                border-radius: 0 36px 36px 0;
                color: var(--light);
                background: var(--red);
            }

            #content nav form.show ~ .notification,
            #content nav form.show ~ .profile {
                display: none;
            }
            
            .book-actions {
                flex-direction: column;
            }
            
            .search-filter {
                flex-direction: column;
                width: 100%;
            }
            
            .search-filter input, .search-filter select {
                width: 100%;
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
            <li class="active">
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
                <a href="settings.html">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="../logout.php" class="logout">
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
                <i class='bx bx-menu'></i>
            </div>
            
            <div class="nav-right">
                <input type="checkbox" id="switch-mode" hidden>
                <label for="switch-mode" class="switch-mode"></label>
                <a href="#" class="notification">
                    <i class='bx bxs-bell'></i>
                    <span class="num">8</span>
                </a>
                <a href="#" class="profile">
                    <img src="image/profile-picture.jpg" alt="Profile Image">
                </a>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="head-title">
                <div class="left">
                    <h1>Manajemen Buku</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="dashboard.php">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Manajemen Buku</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="book-actions">
                <form method="GET" action="buku.php" class="search-filter">
                    <input type="text" name="search" placeholder="Cari judul buku..." 
                        value="<?php echo htmlspecialchars($search); ?>">
                    <select name="category">
                        <option value="">Semua Kategori</option>
                        <?php echo $kategori_options; ?>
                    </select>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="available" <?php echo ($status_filter == 'available') ? 'selected' : ''; ?>>Tersedia</option>
                        <option value="low-stock" <?php echo ($status_filter == 'low-stock') ? 'selected' : ''; ?>>Stok Sedikit</option>
                        <option value="out-of-stock" <?php echo ($status_filter == 'out-of-stock') ? 'selected' : ''; ?>>Habis</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <?php if (!empty($search) || !empty($category_filter) || !empty($status_filter)): ?>
                        <a href="buku.php" class="btn btn-danger">Reset</a>
                    <?php endif; ?>
                </form>
                <a href="buku.php?action=add" class="btn btn-primary">
                    <i class='bx bx-plus'></i> Tambah Buku
                </a>
            </div>

            <div class="table-container">
                <table class="book-table">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($buku = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><img src="../image/<?= $buku['gambar'] ?>" alt="Cover" class="book-cover"></td>
                                <td><?= htmlspecialchars($buku['judul']) ?></td>
                                <td><?= htmlspecialchars($buku['penulis']) ?></td>
                                <td><?= htmlspecialchars($buku['nama_kategori']) ?></td>
                                <td>Rp<?= number_format($buku['harga'], 0, ',', '.') ?></td>
                                <td><?= $buku['stok'] ?></td>
                                <td>
                                    <span class="status-badge 
                                        <?= ($buku['stok'] > 3) ? 'status-available' : (($buku['stok'] > 0) ? 'status-limited' : 'status-out-of-stock') ?>">
                                        <?= ($buku['stok'] > 3) ? 'Tersedia' : (($buku['stok'] > 0) ? 'Stok Sedikit' : 'Habis') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="buku.php?action=view&id_buku=<?= $buku['id_buku'] ?>" class="action-btn view-btn">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        <a href="buku.php?action=edit&id_buku=<?= $buku['id_buku'] ?>" class="action-btn edit-btn">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                        <a href="hapus_buku.php?id_buku=<?= $buku['id_buku'] ?>" class="action-btn delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                            <i class='bx bx-trash'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">Tidak ada data buku ditemukan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <a href="#" class="page-item disabled">
                    <i class='bx bx-chevron-left'></i>
                </a>
                <a href="#" class="page-item active">1</a>
                <a href="#" class="page-item">2</a>
                <a href="#" class="page-item">3</a>
                <a href="#" class="page-item">
                    <i class='bx bx-chevron-right'></i>
                </a>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- MODAL TAMBAH/EDIT BUKU -->
    <div class="modal" style="display: <?php echo ($action == 'add' || $action == 'edit') ? 'flex' : 'none'; ?>;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><?php echo ($action == 'add') ? 'Tambah Buku Baru' : 'Edit Buku'; ?></h3>
                <a href="buku.php" class="close">&times;</a>
            </div>
            <div class="modal-body">
                <form method="POST" action="buku.php" enctype="multipart/form-data">
                    <input type="hidden" name="id_buku" value="<?php echo isset($buku_data['id_buku']) ? $buku_data['id_buku'] : ''; ?>">
                    
                    <div class="form-group">
                        <label for="book-title">Judul Buku *</label>
                        <input type="text" id="book-title" name="judul" class="form-control" required
                            value="<?php echo isset($buku_data['judul']) ? htmlspecialchars($buku_data['judul']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-author">Penulis *</label>
                        <input type="text" id="book-author" name="penulis" class="form-control" required
                            value="<?php echo isset($buku_data['penulis']) ? htmlspecialchars($buku_data['penulis']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-category">Kategori *</label>
                        <select id="book-category" name="kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <?php 
                            $kategori_query = mysqli_query($koneksi, "SELECT * FROM kategori");
                            while($kat = mysqli_fetch_assoc($kategori_query)) {
                                $selected = (isset($buku_data['id_kategori']) && $buku_data['id_kategori'] == $kat['id_kategori']) ? 'selected' : '';
                                echo "<option value='{$kat['id_kategori']}' $selected>{$kat['nama_kategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="book-isbn">ISBN</label>
                        <input type="text" id="book-isbn" name="isbn" class="form-control"
                            value="<?php echo isset($buku_data['isbn']) ? htmlspecialchars($buku_data['isbn']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-price">Harga (Rp) *</label>
                        <input type="number" id="book-price" name="harga" class="form-control" min="0" required
                            value="<?php echo isset($buku_data['harga']) ? $buku_data['harga'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-stock">Stok *</label>
                        <input type="number" id="book-stock" name="stok" class="form-control" min="0" required
                            value="<?php echo isset($buku_data['stok']) ? $buku_data['stok'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-date">Tanggal Terbit</label>
                        <input type="date" id="book-date" name="tanggal_terbit" class="form-control"
                            value="<?php echo isset($buku_data['tanggal_terbit']) ? $buku_data['tanggal_terbit'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-pages">Jumlah Halaman</label>
                        <input type="number" id="book-pages" name="jumlah_halaman" class="form-control" min="0"
                            value="<?php echo isset($buku_data['jumlah_halaman']) ? $buku_data['jumlah_halaman'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-publisher">Penerbit</label>
                        <input type="text" id="book-publisher" name="penerbit" class="form-control"
                            value="<?php echo isset($buku_data['penerbit']) ? htmlspecialchars($buku_data['penerbit']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-language">Bahasa</label>
                        <input type="text" id="book-language" name="bahasa" class="form-control" value="<?php echo isset($buku_data['bahasa']) ? htmlspecialchars($buku_data['bahasa']) : 'Indonesia'; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="book-description">Deskripsi</label>
                        <textarea id="book-description" name="deskripsi" class="form-control" rows="4"><?php echo isset($buku_data['deskripsi']) ? htmlspecialchars($buku_data['deskripsi']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="book-cover">Cover Buku</label>
                        <input type="file" id="book-cover" name="cover" class="form-control" accept="image/*">
                        <small>Format: JPG/PNG, Maksimal 2MB</small>
                        <?php if (isset($buku_data['gambar']) && !empty($buku_data['gambar'])): ?>
                            <div style="margin-top: 10px;">
                                <p>Cover saat ini:</p>
                                <img src="../image/<?= $buku_data['gambar'] ?>" alt="Current Cover" style="max-width: 100px; max-height: 150px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="modal-footer">
                        <a href="buku.php" class="btn btn-cancel">Batal</a>
                        <button type="submit" name="save_book" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL LIHAT DETAIL BUKU -->
    <div class="modal" style="display: <?php echo ($action == 'view') ? 'flex' : 'none'; ?>;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Buku</h3>
                <a href="buku.php" class="close">&times;</a>
            </div>
            <div class="modal-body">
                <?php if (isset($buku_data)): ?>
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <img src="../image/<?= $buku_data['gambar'] ?>" alt="Book Cover" style="border-radius: 6px; width: 150px; height: 200px; object-fit: cover;">
                    <div style="flex: 1;">
                        <h2 style="margin-bottom: 8px;"><?= htmlspecialchars($buku_data['judul']) ?></h2>
                        <p style="color: var(--dark-grey); margin-bottom: 16px;"><?= htmlspecialchars($buku_data['penulis']) ?></p>
                        
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">Kategori</p>
                                <p style="font-weight: 500;"><?= htmlspecialchars($buku_data['nama_kategori']) ?></p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">ISBN</p>
                                <p style="font-weight: 500;"><?= !empty($buku_data['isbn']) ? htmlspecialchars($buku_data['isbn']) : '-' ?></p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">Harga</p>
                                <p style="font-weight: 500;">Rp<?= number_format($buku_data['harga'], 0, ',', '.') ?></p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">Stok</p>
                                <p style="font-weight: 500;"><?= $buku_data['stok'] ?></p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">Tanggal Terbit</p>
                                <p style="font-weight: 500;"><?= !empty($buku_data['tanggal_terbit']) ? $buku_data['tanggal_terbit'] : '-' ?></p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">Halaman</p>
                                <p style="font-weight: 500;"><?= !empty($buku_data['jumlah_halaman']) ? $buku_data['jumlah_halaman'] : '-' ?></p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">Penerbit</p>
                                <p style="font-weight: 500;"><?= !empty($buku_data['penerbit']) ? htmlspecialchars($buku_data['penerbit']) : '-' ?></p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">Bahasa</p>
                                <p style="font-weight: 500;"><?= !empty($buku_data['bahasa']) ? htmlspecialchars($buku_data['bahasa']) : 'Indonesia' ?></p>
                            </div>
                            <div>
                                <p style="font-size: 14px; color: var(--dark-grey);">Status</p>
                                <p>
                                    <span class="status-badge 
                                        <?= ($buku_data['stok'] > 3) ? 'status-available' : (($buku_data['stok'] > 0) ? 'status-limited' : 'status-out-of-stock') ?>">
                                        <?= ($buku_data['stok'] > 3) ? 'Tersedia' : (($buku_data['stok'] > 0) ? 'Stok Sedikit' : 'Habis') ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 style="margin-bottom: 8px;">Deskripsi</h4>
                    <p style="line-height: 1.6; white-space: pre-line;"><?= !empty($buku_data['deskripsi']) ? htmlspecialchars($buku_data['deskripsi']) : 'Tidak ada deskripsi' ?></p>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <a href="buku.php" class="btn btn-primary">Tutup</a>
            </div>
        </div>
    </div>
</body>
</html>