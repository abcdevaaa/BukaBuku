<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pelanggan - Toko Buku</title>
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
            --green: #28a745;
        }

        body {
            background: var(--grey);
            font-family: var(--lato);
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

        /* Customers Table */
        .customers-table {
            width: 100%;
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .customers-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .customers-table th {
            background: var(--light-purple);
            color: var(--purple);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        .customers-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--grey);
            color: var(--dark);
        }
        .customers-table tr:last-child td {
            border-bottom: none;
        }
        .customers-table tr:hover td {
            background: rgba(142, 52, 130, 0.05);
        }
        .customer-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .customer-actions {
            display: flex;
            gap: 8px;
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
            background: rgba(142, 52, 130, 0.1);
            color: var(--purple);
        }
        .btn-icon.edit:hover {
            background: rgba(142, 52, 130, 0.2);
        }
        .btn-icon.delete {
            background: rgba(219, 80, 74, 0.1);
            color: var(--red);
        }
        .btn-icon.delete:hover {
            background: rgba(219, 80, 74, 0.2);
        }
        .btn-icon.view {
            background: rgba(40, 167, 69, 0.1);
            color: var(--green);
        }
        .btn-icon.view:hover {
            background: rgba(40, 167, 69, 0.2);
        }

        /* Customer Avatar */
        .customer-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .customer-name-avatar {
            display: flex;
            align-items: center;
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
            max-width: 600px;
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

        /* Customer Detail Modal */
        .customer-detail {
            margin-bottom: 20px;
        }
        .customer-detail h4 {
            margin-bottom: 10px;
            color: var(--dark);
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            width: 150px;
            color: var(--dark-grey);
        }
        .detail-value {
            flex: 1;
            color: var(--dark);
        }
        .detail-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
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
            .customers-table {
                overflow-x: auto;
                display: block;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                width: 100%;
                margin-bottom: 5px;
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
            <!-- <li>
                <a href="laporan.html">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Laporan</span>
                </a>
            </li> -->
            <li class="active">
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
                <a href="#" class="logout">
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
                <a href="#" class="profile" id="profile-btn">
                    <img src="image/profile-picture.jpg" alt="Profile Image">
                </a>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown" id="profile-dropdown">
                <a href="profile.html"><i class='bx bxs-user'></i> Profil Saya</a>
                <a href="settings.html"><i class='bx bxs-cog'></i> Pengaturan</a>
                <a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Manajemen Pelanggan</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="dashboard.html">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Pelanggan</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="customers-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Total Pesanan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data Pelanggan 1 -->
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="customer-name-avatar">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John Doe" class="customer-avatar">
                                    John Doe
                                </div>
                            </td>
                            <td>john.doe@example.com</td>
                            <td>081234567890</td>
                            <td>5</td>
                            <td><span class="customer-status status-active">Aktif</span></td>
                            <td>
                                <div class="customer-actions">
                                    <div class="btn-icon view" onclick="viewCustomerDetail(1)">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    <div class="btn-icon delete" onclick="showDeleteModal(1, 'John Doe')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Data Pelanggan 2 -->
                        <tr>
                            <td>2</td>
                            <td>
                                <div class="customer-name-avatar">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Jane Smith" class="customer-avatar">
                                    Jane Smith
                                </div>
                            </td>
                            <td>jane.smith@example.com</td>
                            <td>082345678901</td>
                            <td>3</td>
                            <td><span class="customer-status status-active">Aktif</span></td>
                            <td>
                                <div class="customer-actions">
                                    <div class="btn-icon view" onclick="viewCustomerDetail(2)">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    <div class="btn-icon delete" onclick="showDeleteModal(2, 'Jane Smith')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Data Pelanggan 3 -->
                        <tr>
                            <td>3</td>
                            <td>
                                <div class="customer-name-avatar">
                                    <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Robert Johnson" class="customer-avatar">
                                    Robert Johnson
                                </div>
                            </td>
                            <td>robert.j@example.com</td>
                            <td>083456789012</td>
                            <td>7</td>
                            <td><span class="customer-status status-active">Aktif</span></td>
                            <td>
                                <div class="customer-actions">
                                    <div class="btn-icon view" onclick="viewCustomerDetail(3)">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    
                                    <div class="btn-icon delete" onclick="showDeleteModal(3, 'Robert Johnson')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Data Pelanggan 4 -->
                        <tr>
                            <td>4</td>
                            <td>
                                <div class="customer-name-avatar">
                                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Sarah Williams" class="customer-avatar">
                                    Sarah Williams
                                </div>
                            </td>
                            <td>sarah.w@example.com</td>
                            <td>084567890123</td>
                            <td>2</td>
                            <td><span class="customer-status status-inactive">Nonaktif</span></td>
                            <td>
                                <div class="customer-actions">
                                    <div class="btn-icon view" onclick="viewCustomerDetail(4)">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    
                                    <div class="btn-icon delete" onclick="showDeleteModal(4, 'Sarah Williams')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Data Pelanggan 5 -->
                        <tr>
                            <td>5</td>
                            <td>
                                <div class="customer-name-avatar">
                                    <img src="https://randomuser.me/api/portraits/men/91.jpg" alt="Michael Brown" class="customer-avatar">
                                    Michael Brown
                                </div>
                            </td>
                            <td>michael.b@example.com</td>
                            <td>085678901234</td>
                            <td>4</td>
                            <td><span class="customer-status status-active">Aktif</span></td>
                            <td>
                                <div class="customer-actions">
                                    <div class="btn-icon view" onclick="viewCustomerDetail(5)">
                                        <i class='bx bx-show'></i>
                                    </div>
                                    
                                    <div class="btn-icon delete" onclick="showDeleteModal(5, 'Michael Brown')">
                                        <i class='bx bx-trash'></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Customer Detail Modal -->
    <div class="modal" id="detail-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Pelanggan</h3>
                <span class="close" onclick="closeModal('detail-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <!-- Detail untuk Pelanggan 1 -->
                <div id="detail-customer-1" class="customer-detail-content">
                    <div class="customer-detail">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer Avatar" class="detail-avatar">
                        <h4>Informasi Pribadi</h4>
                        <div class="detail-row">
                            <div class="detail-label">Nama Lengkap:</div>
                            <div class="detail-value">John Doe</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value">john.doe@example.com</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Telepon:</div>
                            <div class="detail-value">081234567890</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value"><span class="customer-status status-active">Aktif</span></div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Alamat</h4>
                        <div class="detail-row">
                            <div class="detail-value">
                                Jl. Contoh No. 123, Jakarta Selatan, DKI Jakarta 12345
                            </div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Statistik Pesanan</h4>
                        <div class="detail-row">
                            <div class="detail-label">Total Pesanan:</div>
                            <div class="detail-value">5</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Total Belanja:</div>
                            <div class="detail-value">Rp 1.250.000</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Pesanan Terakhir:</div>
                            <div class="detail-value">15 Mei 2023</div>
                        </div>
                    </div>
                </div>
                
                <!-- Detail untuk Pelanggan 2 -->
                <div id="detail-customer-2" class="customer-detail-content" style="display:none;">
                    <div class="customer-detail">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Customer Avatar" class="detail-avatar">
                        <h4>Informasi Pribadi</h4>
                        <div class="detail-row">
                            <div class="detail-label">Nama Lengkap:</div>
                            <div class="detail-value">Jane Smith</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value">jane.smith@example.com</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Telepon:</div>
                            <div class="detail-value">082345678901</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value"><span class="customer-status status-active">Aktif</span></div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Alamat</h4>
                        <div class="detail-row">
                            <div class="detail-value">
                                Jl. Sample No. 456, Bandung, Jawa Barat 45678
                            </div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Statistik Pesanan</h4>
                        <div class="detail-row">
                            <div class="detail-label">Total Pesanan:</div>
                            <div class="detail-value">3</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Total Belanja:</div>
                            <div class="detail-value">Rp 750.000</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Pesanan Terakhir:</div>
                            <div class="detail-value">10 Mei 2023</div>
                        </div>
                    </div>
                </div>
                
                <!-- Detail untuk Pelanggan 3 -->
                <div id="detail-customer-3" class="customer-detail-content" style="display:none;">
                    <div class="customer-detail">
                        <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Customer Avatar" class="detail-avatar">
                        <h4>Informasi Pribadi</h4>
                        <div class="detail-row">
                            <div class="detail-label">Nama Lengkap:</div>
                            <div class="detail-value">Robert Johnson</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value">robert.j@example.com</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Telepon:</div>
                            <div class="detail-value">083456789012</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value"><span class="customer-status status-active">Aktif</span></div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Alamat</h4>
                        <div class="detail-row">
                            <div class="detail-value">
                                Jl. Test No. 789, Surabaya, Jawa Timur 67890
                            </div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Statistik Pesanan</h4>
                        <div class="detail-row">
                            <div class="detail-label">Total Pesanan:</div>
                            <div class="detail-value">7</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Total Belanja:</div>
                            <div class="detail-value">Rp 1.850.000</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Pesanan Terakhir:</div>
                            <div class="detail-value">12 Mei 2023</div>
                        </div>
                    </div>
                </div>
                
                <!-- Detail untuk Pelanggan 4 -->
                <div id="detail-customer-4" class="customer-detail-content" style="display:none;">
                    <div class="customer-detail">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Customer Avatar" class="detail-avatar">
                        <h4>Informasi Pribadi</h4>
                        <div class="detail-row">
                            <div class="detail-label">Nama Lengkap:</div>
                            <div class="detail-value">Sarah Williams</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value">sarah.w@example.com</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Telepon:</div>
                            <div class="detail-value">084567890123</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value"><span class="customer-status status-inactive">Nonaktif</span></div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Alamat</h4>
                        <div class="detail-row">
                            <div class="detail-value">
                                Jl. Demo No. 321, Yogyakarta, DIY 54321
                            </div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Statistik Pesanan</h4>
                        <div class="detail-row">
                            <div class="detail-label">Total Pesanan:</div>
                            <div class="detail-value">2</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Total Belanja:</div>
                            <div class="detail-value">Rp 350.000</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Pesanan Terakhir:</div>
                            <div class="detail-value">20 April 2023</div>
                        </div>
                    </div>
                </div>
                
                <!-- Detail untuk Pelanggan 5 -->
                <div id="detail-customer-5" class="customer-detail-content" style="display:none;">
                    <div class="customer-detail">
                        <img src="https://randomuser.me/api/portraits/men/91.jpg" alt="Customer Avatar" class="detail-avatar">
                        <h4>Informasi Pribadi</h4>
                        <div class="detail-row">
                            <div class="detail-label">Nama Lengkap:</div>
                            <div class="detail-value">Michael Brown</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value">michael.b@example.com</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Telepon:</div>
                            <div class="detail-value">085678901234</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status:</div>
                            <div class="detail-value"><span class="customer-status status-active">Aktif</span></div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Alamat</h4>
                        <div class="detail-row">
                            <div class="detail-value">
                                Jl. Contoh Baru No. 654, Medan, Sumatera Utara 76543
                            </div>
                        </div>
                    </div>
                    <div class="customer-detail">
                        <h4>Statistik Pesanan</h4>
                        <div class="detail-row">
                            <div class="detail-label">Total Pesanan:</div>
                            <div class="detail-value">4</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Total Belanja:</div>
                            <div class="detail-value">Rp 950.000</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Pesanan Terakhir:</div>
                            <div class="detail-value">8 Mei 2023</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeModal('detail-modal')">Tutup</button>
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
                <p id="delete-message">Apakah Anda yakin ingin menghapus pelanggan ini? Semua data terkait akan dihapus permanen.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('delete-modal')">Batal</button>
                <button class="btn btn-danger" id="confirm-delete-btn">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('sidebar-toggle').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('hide');
        });

        // Toggle profile dropdown
        document.getElementById('profile-btn').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('profile-dropdown').classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if(!document.getElementById('profile-btn').contains(e.target) && 
               !document.getElementById('profile-dropdown').contains(e.target)) {
                document.getElementById('profile-dropdown').classList.remove('show');
            }
        });

        // View customer details
        function viewCustomerDetail(id) {
            // Hide all detail content first
            document.querySelectorAll('.customer-detail-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Show selected detail
            const detailContent = document.getElementById(`detail-customer-${id}`);
            if (detailContent) {
                detailContent.style.display = 'block';
            }
            
            document.getElementById('detail-modal').classList.add('show');
        }

        // Show delete confirmation modal
        function showDeleteModal(id, name) {
            document.getElementById('delete-message').textContent = 
                `Apakah Anda yakin ingin menghapus pelanggan "${name}"? Semua data terkait akan dihapus permanen.`;
            document.getElementById('delete-modal').classList.add('show');
        }

        // Close modal function
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // Confirm delete button click
        document.getElementById('confirm-delete-btn').addEventListener('click', () => {
            alert('Pelanggan berhasil dihapus');
            closeModal('delete-modal');
        });

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if(event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        });
    </script>
</body>
</html>