
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
        #content main .head-title .btn-download {
            height: 36px;
            padding: 0 16px;
            border-radius: 36px;
            background: var(--purple);
            color: var(--light);
            display: flex;
            justify-content: center;
            align-items: center;
            grid-gap: 10px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            outline: none;
            transition: all 0.3s ease;
        }
        #content main .head-title .btn-download:hover {
            background: var(--light-purple);
            color: var(--purple);
        }

        #content main .box-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            grid-gap: 24px;
            margin-top: 36px;
        }
        #content main .box-info li {
            padding: 24px;
            background: var(--light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            grid-gap: 24px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        #content main .box-info li:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        #content main .box-info li .bx {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            font-size: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #content main .box-info li:nth-child(1) .bx {
            background: var(--light-purple);
            color: var(--purple);
        }
        #content main .box-info li:nth-child(2) .bx {
            background: var(--light-yellow);
            color: var(--yellow);
        }
        #content main .box-info li:nth-child(3) .bx {
            background: var(--light-orange);
            color: var(--orange);
        }
        #content main .box-info li .text h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }
        #content main .box-info li .text p {
            color: var(--dark);    
        }

        /* Chart container */
        .chart-container {
            background: var(--light);
            border-radius: 20px;
            padding: 24px;
            margin-top: 24px;
        }
        .chart-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
        }
        .chart {
            width: 100%;
            height: 300px;
        }

        #content main .table-data {
            display: flex;
            flex-wrap: wrap;
            grid-gap: 24px;
            margin-top: 24px;
            width: 100%;
            color: var(--dark);
        }
        #content main .table-data > div {
            border-radius: 20px;
            background: var(--light);
            padding: 24px;
            overflow-x: auto;
        }
        #content main .table-data .head {
            display: flex;
            align-items: center;
            grid-gap: 16px;
            margin-bottom: 24px;
        }
        #content main .table-data .head h3 {
            margin-right: auto;
            font-size: 24px;
            font-weight: 600;
        }
        #content main .table-data .head .bx {
            cursor: pointer;
            font-size: 1.2rem;
        }

        #content main .table-data .order {
            flex-grow: 1;
            flex-basis: 500px;
        }
        #content main .table-data .order table {
            width: 100%;
            border-collapse: collapse;
        }
        #content main .table-data .order table th {
            padding-bottom: 12px;
            font-size: 13px;
            text-align: left;
            border-bottom: 1px solid var(--grey);
        }
        #content main .table-data .order table td {
            padding: 16px 0;
        }
        #content main .table-data .order table tr td:first-child {
            display: flex;
            align-items: center;
            grid-gap: 12px;
            padding-left: 6px;
        }
        #content main .table-data .order table td img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        #content main .table-data .order table tbody tr:hover {
            background: var(--grey);
        }
        #content main .table-data .order table tr td .status {
            font-size: 10px;
            padding: 6px 16px;
            color: var(--light);
            border-radius: 20px;
            font-weight: 700;
        }
        #content main .table-data .order table tr td .status.completed {
            background: var(--purple);
        }
        #content main .table-data .order table tr td .status.process {
            background: var(--yellow);
        }
        #content main .table-data .order table tr td .status.pending {
            background: var(--orange);
        }

        #content main .table-data .todo {
            flex-grow: 1;
            flex-basis: 300px;
        }
        #content main .table-data .todo .todo-list {
            width: 100%;
        }
        #content main .table-data .todo .todo-list li {
            width: 100%;
            margin-bottom: 16px;
            background: var(--grey);
            border-radius: 10px;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        #content main .table-data .todo .todo-list li:hover {
            transform: translateX(5px);
        }
        #content main .table-data .todo .todo-list li .bx {
            cursor: pointer;
        }
        #content main .table-data .todo .todo-list li.completed {
            border-left: 10px solid var(--purple);
        }
        #content main .table-data .todo .todo-list li.not-completed {
            border-left: 10px solid var(--orange);
        }
        #content main .table-data .todo .todo-list li:last-child {
            margin-bottom: 0;
        }

        /* Add todo form */
        .add-todo-form {
            display: none;
            margin-top: 20px;
        }
        .add-todo-form.show {
            display: block;
        }
        .add-todo-form input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--grey);
            border-radius: 8px;
            margin-bottom: 10px;
            font-family: var(--lato);
        }
        .add-todo-form button {
            background: var(--purple);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .add-todo-form button:hover {
            background: var(--light-purple);
            color: var(--purple);
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
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
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
            color: var(--dark);
        }
        .modal-body {
            margin-bottom: 20px;
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .modal-footer button {
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .modal-footer .btn-confirm {
            background: var(--purple);
            color: white;
            border: none;
        }
        .modal-footer .btn-cancel {
            background: var(--grey);
            color: var(--dark);
            border: none;
        }
        .modal-footer button:hover {
            opacity: 0.9;
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

            #content nav .nav-link {
                display: none;
            }
        }

        @media screen and (max-width: 576px) {
            #content nav .profile .name {
                display: none;
            }

            #content main .box-info {
                grid-template-columns: 1fr;
            }

            #content main .table-data .head {
                min-width: 420px;
            }
            #content main .table-data .order table {
                min-width: 420px;
            }
            #content main .table-data .todo .todo-list {
                min-width: 420px;
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
            <li class="active">
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
                <a href="settings.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="../logout.php" class="logout" id="logout-btn">
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
                <!-- <a href="#" class="nav-link">Dashboard</a> -->
            </div>
            
            <div class="nav-right">
                <input type="checkbox" id="switch-mode" hidden>
                <label for="switch-mode" class="switch-mode"></label>
                <a href="#" class="profile" id="profile-btn">
                    <img src="image/𓂂  ♱⠀◌  ★⠀◯  ⭑ ⸱ ៰  ͘ ࣭ 𓂂  ♱⠀◌ •.jpeg" alt="Profile Image">
                </a>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown" id="profile-dropdown">
                <a href="#"><i class='bx bxs-user'></i> Profil Saya</a>
                <a href="#"><i class='bx bxs-cog'></i> Pengaturan</a>
                <a href="../logout.php" id="logout-dropdown-btn"><i class='bx bxs-log-out-circle'></i> Logout</a>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <!-- <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul> -->
                </div>
                <a href="#" class="btn-download" id="download-report">
                    <i class='bx bxs-cloud-download'></i>
                    <span class="text">Download PDF</span>
                </a>
            </div>

            <ul class="box-info">
                <li onclick="window.location.href='pesanan.html'">
                    <i class='bx bxs-calendar-check'></i>
                    <span class="text">
                        <h3 id="order-count">1020</h3>
                        <p>Pesanan Baru</p>
                    </span>
                </li>
                
                <li onclick="window.location.href='pelanggan.php'">
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3 id="visitor-count">123</h3>
                        <p>Pengunjung</p>
                    </span>
                </li>
                
                <li onclick="window.location.href='laporan.html'">
                    <i class='bx bxs-dollar-circle'></i>
                    <span class="text">
                        <h3 id="sales-count">Rp12.543.000</h3>
                        <p>Total Penjualan</p>
                    </span>
                </li>
                
            </ul>


            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Pesanan Terbaru</h3>
                        <i class='bx bx-filter' onclick="alert('Fitur filter pesanan akan tersedia segera!')"></i>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Tanggal Pesanan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr onclick="showOrderDetail('ORD-2023-001')">
                                <td>
                                    <img src="https://via.placeholder.com/36x36" alt="Customer Image">
                                    <p>John Doe</p>
                                </td>
                                <td>15-06-2023</td>
                                <td><span class="status completed">Selesai</span></td>
                            </tr>
                            <tr onclick="showOrderDetail('ORD-2023-002')">
                                <td>
                                    <img src="https://via.placeholder.com/36x36" alt="Customer Image">
                                    <p>Jane Smith</p>
                                </td>
                                <td>14-06-2023</td>
                                <td><span class="status pending">Menunggu</span></td>
                            </tr>
                            <tr onclick="showOrderDetail('ORD-2023-003')">
                                <td>
                                    <img src="https://via.placeholder.com/36x36" alt="Customer Image">
                                    <p>Robert Johnson</p>
                                </td>
                                <td>14-06-2023</td>
                                <td><span class="status process">Diproses</span></td>
                            </tr>
                            <tr onclick="showOrderDetail('ORD-2023-004')">
                                <td>
                                    <img src="https://via.placeholder.com/36x36" alt="Customer Image">
                                    <p>Sarah Williams</p>
                                </td>
                                <td>13-06-2023</td>
                                <td><span class="status pending">Menunggu</span></td>
                            </tr>
                            <tr onclick="showOrderDetail('ORD-2023-005')">
                                <td>
                                    <img src="https://via.placeholder.com/36x36" alt="Customer Image">
                                    <p>Michael Brown</p>
                                </td>
                                <td>12-06-2023</td>
                                <td><span class="status completed">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="todo">
                    <div class="head">
                        <h3>Daftar Tugas</h3>
                        <i class='bx bx-plus' id="add-todo-btn"></i>
                        <i class='bx bx-filter' onclick="alert('Fitur filter tugas akan tersedia segera!')"></i>
                    </div>
                    <ul class="todo-list" id="todo-list">
                        <li class="completed" onclick="toggleTodoStatus(this)">
                            <p>Periksa stok buku terlaris</p>
                            <i class='bx bx-dots-vertical-rounded' onclick="event.stopPropagation(); showTodoOptions(event, this.parentElement)"></i>
                        </li>
                        <li class="completed" onclick="toggleTodoStatus(this)">
                            <p>Kirim laporan mingguan</p>
                            <i class='bx bx-dots-vertical-rounded' onclick="event.stopPropagation(); showTodoOptions(event, this.parentElement)"></i>
                        </li>
                        <li class="not-completed" onclick="toggleTodoStatus(this)">
                            <p>Hubungi supplier buku baru</p>
                            <i class='bx bx-dots-vertical-rounded' onclick="event.stopPropagation(); showTodoOptions(event, this.parentElement)"></i>
                        </li>
                        <li class="completed" onclick="toggleTodoStatus(this)">
                            <p>Perbarui harga buku diskon</p>
                            <i class='bx bx-dots-vertical-rounded' onclick="event.stopPropagation(); showTodoOptions(event, this.parentElement)"></i>
                        </li>
                        <li class="not-completed" onclick="toggleTodoStatus(this)">
                            <p>Siapkan paket promo akhir bulan</p>
                            <i class='bx bx-dots-vertical-rounded' onclick="event.stopPropagation(); showTodoOptions(event, this.parentElement)"></i>
                        </li>
                    </ul>
                    
                    Add Todo Form -->
                    <!-- <div class="add-todo-form" id="add-todo-form">
                        <input type="text" placeholder="Masukkan tugas baru..." id="new-todo-input">
                        <button id="save-todo-btn">Simpan</button>
                    </div>
                </div>-->
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Order Detail Modal -->
    <div class="modal" id="order-detail-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Pesanan</h3>
                <span class="close" onclick="closeModal('order-detail-modal')">&times;</span>
            </div>
            <div class="modal-body" id="order-detail-content">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal('order-detail-modal')">Tutup</button>
                <button class="btn-confirm" onclick="printOrder()">Cetak</button>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <!-- <div class="modal" id="logout-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Konfirmasi Logout</h3>
                <span class="close" onclick="closeModal('logout-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin keluar dari sistem?</p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal('logout-modal')">Batal</button>
                <button class="btn-confirm" onclick="performLogout()">Logout</button>
            </div>
        </div>
    </div> -->

    <!-- Todo Options Modal -->
    <div class="modal" id="todo-options-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Opsi Tugas</h3>
                <span class="close" onclick="closeModal('todo-options-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <p>Apa yang ingin Anda lakukan dengan tugas ini?</p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="deleteTodo()">Hapus</button>
                <button class="btn-confirm" onclick="editTodo()">Edit</button>
            </div>
        </div>
    </div>

    <!-- Gunakan versi Chart.js yang lebih spesifik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.js"></script>
    
    <script>
        // DOM Elements
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const switchMode = document.getElementById('switch-mode');
        const notificationBtn = document.getElementById('notification-btn');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');
        const markAllRead = document.getElementById('mark-all-read');
        const logoutBtn = document.getElementById('logout-btn');
        const logoutDropdownBtn = document.getElementById('logout-dropdown-btn');
        const downloadReport = document.getElementById('download-report');
        const addTodoBtn = document.getElementById('add-todo-btn');
        const addTodoForm = document.getElementById('add-todo-form');
        const saveTodoBtn = document.getElementById('save-todo-btn');
        const newTodoInput = document.getElementById('new-todo-input');
        const todoList = document.getElementById('todo-list');
        
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
        markAllRead.addEventListener('click', () => {
            const notifications = document.querySelectorAll('.notification-item.unread');
            notifications.forEach(notification => {
                notification.classList.remove('unread');
            });
            document.querySelector('.notification .num').textContent = '0';
            notificationDropdown.classList.remove('show');
        });
        
        // // Show logout confirmation
        // logoutBtn.addEventListener('click', (e) => {
        //     e.preventDefault();
        //     document.getElementById('logout-modal').classList.add('show');
        // });
        
        // logoutDropdownBtn.addEventListener('click', (e) => {
        //     e.preventDefault();
        //     profileDropdown.classList.remove('show');
        //     document.getElementById('logout-modal').classList.add('show');
        // });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if(!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.remove('show');
            }
            if(!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });
        
        // Download report
        downloadReport.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Laporan PDF sedang diproses dan akan segera diunduh...');
        });
        
        // Add todo
        addTodoBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            addTodoForm.classList.toggle('show');
        });
        
        saveTodoBtn.addEventListener('click', () => {
            const todoText = newTodoInput.value.trim();
            if(todoText) {
                const newTodo = document.createElement('li');
                newTodo.className = 'not-completed';
                newTodo.innerHTML = `
                    <p>${todoText}</p>
                    <i class='bx bx-dots-vertical-rounded' onclick="event.stopPropagation(); showTodoOptions(event, this.parentElement)"></i>
                `;
                newTodo.onclick = function() { toggleTodoStatus(this); };
                todoList.appendChild(newTodo);
                newTodoInput.value = '';
                addTodoForm.classList.remove('show');
            }
        });
        
        // Modal functions
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }
        
        function showOrderDetail(orderId) {
            const modalContent = document.getElementById('order-detail-content');
            modalContent.innerHTML = `
                <h4>ID Pesanan: ${orderId}</h4>
                <p><strong>Pelanggan:</strong> John Doe</p>
                <p><strong>Tanggal:</strong> 15 Juni 2023</p>
                <p><strong>Status:</strong> Selesai</p>
                <p><strong>Total:</strong> Rp250.000</p>
                <h5 style="margin-top: 15px;">Item Pesanan:</h5>
                <ul style="margin-left: 20px;">
                    <li>Belajar JavaScript - 1x Rp100.000</li>
                    <li>Panduan CSS - 1x Rp80.000</li>
                    <li>HTML untuk Pemula - 1x Rp70.000</li>
                </ul>
                <p style="margin-top: 15px;"><strong>Alamat Pengiriman:</strong> Jl. Contoh No. 123, Jakarta</p>
            `;
            document.getElementById('order-detail-modal').classList.add('show');
        }
        
        function printOrder() {
            alert('Fitur cetak pesanan akan membuka jendela pencetakan...');
            closeModal('order-detail-modal');
        }
        
        function performLogout() {
            alert('Anda akan keluar dari sistem...');
            closeModal('logout-modal');
            window.location.href = 'index.html';
        }
        
        // Todo functions
        function toggleTodoStatus(todoItem) {
            if(todoItem.classList.contains('completed')) {
                todoItem.classList.remove('completed');
                todoItem.classList.add('not-completed');
            } else {
                todoItem.classList.remove('not-completed');
                todoItem.classList.add('completed');
            }
        }
        
        let currentTodo = null;
        
        function showTodoOptions(event, todoItem) {
            event.stopPropagation();
            currentTodo = todoItem;
            document.getElementById('todo-options-modal').classList.add('show');
        }
        
        function deleteTodo() {
            if(currentTodo) {
                currentTodo.remove();
                currentTodo = null;
            }
            closeModal('todo-options-modal');
        }
        
        function editTodo() {
            if(currentTodo && currentTodo.querySelector('p')) {
                const todoText = currentTodo.querySelector('p').textContent;
                const newText = prompt('Edit tugas:', todoText);
                if(newText !== null && newText.trim() !== '') {
                    currentTodo.querySelector('p').textContent = newText.trim();
                }
            } else {
                console.error('Elemen todo tidak ditemukan');
            }
            closeModal('todo-options-modal');
        }
        
        // Initialize chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('chartCanvas').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Penjualan (Rp)',
                        data: [1200000, 1900000, 1500000, 2000000, 1800000, 2500000, 2200000],
                        backgroundColor: '#8E3482',
                        borderColor: '#8E3482',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp' + context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        }
                    }
                }
            });
            
            // Simulate real-time data updates
            setInterval(() => {
                // Update counters with random values
                document.getElementById('order-count').textContent = Math.floor(1020 + Math.random() * 20);
                document.getElementById('visitor-count').textContent = Math.floor(2834 + Math.random() * 50);
                
                // Format sales with thousand separators
                const sales = 12543000 + Math.floor(Math.random() * 500000);
                document.getElementById('sales-count').textContent = 'Rp' + sales.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                
                // Update chart data
                salesChart.data.datasets[0].data = salesChart.data.datasets[0].data.map(() => 
                    Math.floor(1000000 + Math.random() * 2000000)
                );
                salesChart.update();
            }, 5000);
        });
    </script>
</body>
</html>