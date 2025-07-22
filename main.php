<?php
session_start();

//$_SESSION = array();

// Destroy the session
//session_destroy();

// Check if user is logged in

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
    

// Check if user account is still active
include("db_connect.php");
if ($connection !== null) {
    $check_user_sql = "SELECT isactive FROM TBL_USERS WHERE id = " . $_SESSION['user_id'];
    $check_result = mysqli_query($connection, $check_user_sql);
    if ($check_result && mysqli_num_rows($check_result) == 1) {
        $user_status = mysqli_fetch_assoc($check_result);
        if ($user_status['isactive'] !== 'ACTIVE') {
            session_destroy();
            header("Location: index.php");
            exit();
        }
    }
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaxTrek - Sistem Pengurusan Profesional</title>
   
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts - Professional Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #8000ff 0%, #ff00cc 100%);
            --primary-color: #8000ff;
            --secondary-color: #ff00cc;
            --accent-color: #6c5ce7;
            --text-dark: #2d3436;
            --text-light: #636e72;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --shadow-light: rgba(0, 0, 0, 0.05);
            --shadow-medium: rgba(0, 0, 0, 0.1);
            --shadow-heavy: rgba(128, 0, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Modern Navbar */
        .navbar {
            background: var(--white);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(128, 0, 255, 0.1);
            box-shadow: 0 4px 30px var(--shadow-light);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 28px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            margin-right: 2rem;
        }

        /* Custom Hamburger Menu */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            background: var(--primary-gradient);
            transition: all 0.3s ease;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 255, 0.25);
        }
       
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            width: 1.5em;
            height: 1.5em;
        }
    
        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--text-dark);
            padding: 0.75rem 1.5rem;
            margin: 0 0.25rem;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color);
            background: linear-gradient(135deg, rgba(128, 0, 255, 0.1), rgba(255, 0, 204, 0.1));
            transform: translateY(-2px);
        }
      
        .navbar-nav .nav-item.active .nav-link {
            background: var(--primary-gradient);
            color: var(--white);
            box-shadow: 0 8px 25px var(--shadow-heavy);
            transform: translateY(-2px);
        }

        /* User Dropdown */
        .user-dropdown .dropdown-toggle {
            background: var(--primary-gradient);
            color: var(--white);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            box-shadow: 0 8px 25px var(--shadow-heavy);
            transition: all 0.3s ease;
        }

        .user-dropdown .dropdown-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px var(--shadow-heavy);
        }

        .user-dropdown .dropdown-toggle::after {
            margin-left: 8px;
        }

        .user-dropdown .dropdown-toggle:focus {
            box-shadow: 0 0 0 0.2rem rgba(128, 0, 255, 0.25);
        }

        .user-dropdown .dropdown-menu {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 1rem 0;
            min-width: 280px;
            margin-top: 0.5rem;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
        }

        .user-info {
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(128, 0, 255, 0.05), rgba(255, 0, 204, 0.05));
            border-radius: 16px;
            margin: 0 1rem 1rem 1rem;
            border: 1px solid rgba(128, 0, 255, 0.1);
        }

        .user-info .fw-bold {
            font-size: 16px;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .badge {
            font-size: 11px;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .logout-btn {
            color: #e74c3c !important;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            margin: 0 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(231, 76, 60, 0.1);
            transform: translateX(4px);
        }

        /* Main Content */
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 100px);
        }

        /* Dashboard Cards */
        .stats-card {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            border: none;
            box-shadow: 0 8px 30px var(--shadow-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px var(--shadow-medium);
        }

        .stats-card .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--white);
            margin-bottom: 1rem;
        }

        .stats-card h3 {
            font-size: 36px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0.5rem 0;
        }

        .stats-card p {
            color: var(--text-light);
            font-weight: 500;
            margin: 0;
        }

        /* Page Cards */
        .page-card {
            background: var(--white);
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 30px var(--shadow-light);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .page-card .card-header {
            background: var(--primary-gradient);
            color: var(--white);
            padding: 1.5rem;
            border: none;
        }

        .page-card .card-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 20px;
        }

        .page-card .card-body {
            padding: 2rem;
        }

        /* Welcome Section */
        .welcome-section {
            background: var(--white);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 8px 30px var(--shadow-light);
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--primary-gradient);
            opacity: 0.05;
            transform: rotate(-45deg);
        }

        .welcome-section h2 {
            font-size: 32px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .navbar-brand {
                font-size: 24px;
            }
            
            .navbar-collapse {
                margin-top: 1rem;
                padding: 1rem;
                background: var(--white);
                border-radius: 15px;
                box-shadow: 0 8px 30px var(--shadow-light);
            }
            
            .navbar-nav {
                margin-bottom: 1rem;
            }
    
        .navbar-nav .nav-link {
                margin: 0.25rem 0;
                text-align: center;
            }
            
            .user-dropdown {
                width: 100%;
            }
            
            .user-dropdown .dropdown-toggle {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem 0;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .stats-card {
                margin-bottom: 1rem;
                padding: 1.5rem;
            }
            
            .stats-card h3 {
                font-size: 28px;
            }
            
            .welcome-section {
                padding: 2rem;
            }
            
            .welcome-section h2 {
                font-size: 24px;
            }
            
            .page-card .card-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .stats-card {
                padding: 1rem;
            }
            
            .stats-card .card-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .stats-card h3 {
                font-size: 24px;
            }
            
            .welcome-section {
                padding: 1.5rem;
            }
            
            .welcome-section h2 {
                font-size: 20px;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 4px;
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

                /* Customer Dashboard Specific Styles */
                .customer-welcome {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 2rem;
                    border-radius: 15px;
                    margin-bottom: 2rem;
                    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
                }

                .customer-info-badge {
                    margin-top: 1rem;
                }

                .customer-stat {
                    border-left: 4px solid var(--primary-color);
                }

                .customer-actions .btn {
                    border-radius: 10px;
                    padding: 0.75rem;
                    transition: all 0.3s ease;
                }

                .customer-actions .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                }

                .customer-status .card-header.bg-gradient,
                .customer-actions .card-header.bg-gradient {
                    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
                    color: white;
                }

                .progress-container {
                    padding: 1rem 0;
                }

                .progress {
                    border-radius: 10px;
                    height: 10px;
                }

                .progress-bar {
                    border-radius: 10px;
                }

                .status-breakdown {
                    background-color: #f8f9fa;
                    padding: 1rem;
                    border-radius: 8px;
                }
    </style>
</head>
<body>
    <!-- Modern Navigation -->
    <nav class="navbar navbar-expand-lg">
    <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="#">
                <i class="fas fa-chart-line me-2"></i>TaxTrek
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

            <!-- Navigation Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                                    <!-- Main Navigation - Role Based -->
                    <ul class="navbar-nav mx-auto">
                        <?php if ($_SESSION['user_level'] === 'ADMIN'): ?>
                            <!-- ADMIN Menu Items -->
                            <li class="nav-item <?php if (($_GET['page'] ?? '') == 'dashboard.php' || empty($_GET['page'])) echo 'active'; ?>">
                                <a class="nav-link" href="?page=dashboard.php">
                                    <i class="fas fa-chart-line me-2"></i>Dashboard Admin
                                </a>
                            </li>
                            <li class="nav-item <?php if (($_GET['page'] ?? '') == 'pengguna.php') echo 'active'; ?>">
                                <a class="nav-link" href="?page=pengguna.php">
                                    <i class="fas fa-users-cog me-2"></i>Pengurusan Pengguna
                                </a>
                            </li>
                            <li class="nav-item <?php if (($_GET['page'] ?? '') == 'bayaran.php') echo 'active'; ?>">
                                <a class="nav-link" href="?page=bayaran.php">
                                    <i class="fas fa-credit-card me-2"></i>Pengurusan Bayaran
                                </a>
                            </li>
                            <li class="nav-item <?php if (($_GET['page'] ?? '') == 'page_data.php') echo 'active'; ?>">
                                <a class="nav-link" href="?page=page_data.php">
                                    <i class="fas fa-database me-2"></i>Semua Data Taskforce
                                </a>
                            </li>
                        <?php else: ?>
                            <!-- CUSTOMER Menu Items -->
                            <li class="nav-item <?php if (($_GET['page'] ?? '') == 'customer_dashboard.php' || empty($_GET['page'])) echo 'active'; ?>">
                                <a class="nav-link" href="?page=customer_dashboard.php">
                                    <i class="fas fa-home me-2"></i>Dashboard Saya
                                </a>
                            </li>
                            <li class="nav-item <?php if (($_GET['page'] ?? '') == 'customer_data.php') echo 'active'; ?>">
                                <a class="nav-link" href="?page=customer_data.php">
                                    <i class="fas fa-file-alt me-2"></i>Data Saya
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>

                <!-- User Dropdown -->
                <div class="dropdown user-dropdown">
                    <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i>
                        <span class="d-none d-sm-inline"><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></span>
                        <span class="d-sm-none">Menu</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li class="user-info">
                            <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></div>
                            <small class="text-muted d-block mb-2"><?php echo htmlspecialchars($_SESSION['user_no_gaji']); ?></small>
                            <span class="badge bg-<?php echo $_SESSION['user_level'] === 'ADMIN' ? 'danger' : 'primary'; ?> me-1">
                                <?php echo $_SESSION['user_level']; ?>
                            </span>
                            <span class="badge bg-<?php echo $_SESSION['user_ispaid'] === 'PAID' ? 'success' : 'warning'; ?>">
                                <?php echo $_SESSION['user_ispaid']; ?>
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item logout-btn" href="#" onclick="confirmLogout(); return false;">
                                <i class="fas fa-sign-out-alt me-2"></i>Log Keluar
                            </a>
                </li>
            </ul>
                </div>
        </div>
    </div>
</nav>

    <!-- Main Content -->
    <div class="container main-content">
        <div class="fade-in">
<?php 
            $page = isset($_GET['page']) ? $_GET['page'] : ''; 
            $userLevel = $_SESSION['user_level'];
            
            // Set default page based on user role
            if (empty($page)) {
                $page = ($userLevel === 'ADMIN') ? 'dashboard.php' : 'customer_dashboard.php';
            }
            
            // Role-based content loading
            if ($userLevel === 'ADMIN') {
                // ADMIN CONTENT
                if ($page == 'dashboard.php') {
                    // Admin Dashboard content
                    echo '<div class="welcome-section">';
                    echo '<h2><i class="fas fa-chart-line me-3"></i>Dashboard Admin - TaxTrek</h2>';
                    echo '<p class="lead">Sistem pengurusan pentadbiran yang komprehensif untuk keperluan perniagaan anda, ' . htmlspecialchars($_SESSION['user_fullname']) . '</p>';
                    echo '</div>';
                    
                    echo '<div class="row g-4 mb-4">';
                    echo '<div class="col-lg-3 col-md-6 col-sm-6">';
                    echo '<div class="stats-card">';
                    echo '<div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">';
                    echo '<i class="fas fa-users"></i>';
                    echo '</div>';
                    echo '<h3>2</h3>';
                    echo '<p>Total Pengguna Aktif</p>';
                    echo '</div>';
                    echo '</div>';
                    
                    echo '<div class="col-lg-3 col-md-6 col-sm-6">';
                    echo '<div class="stats-card">';
                    echo '<div class="card-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">';
                    echo '<i class="fas fa-credit-card"></i>';
                    echo '</div>';
                    echo '<h3>2</h3>';
                    echo '<p>Bayaran Selesai</p>';
                    echo '</div>';
                    echo '</div>';
                    
                    echo '<div class="col-lg-3 col-md-6 col-sm-6">';
                    echo '<div class="stats-card">';
                    echo '<div class="card-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">';
                    echo '<i class="fas fa-database"></i>';
                    echo '</div>';
                    echo '<h3>0</h3>';
                    echo '<p>Data Taskforce</p>';
                    echo '</div>';
                    echo '</div>';
                    
                    echo '<div class="col-lg-3 col-md-6 col-sm-6">';
                    echo '<div class="stats-card">';
                    echo '<div class="card-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">';
                    echo '<i class="fas fa-chart-bar"></i>';
                    echo '</div>';
                    echo '<h3>0</h3>';
                    echo '<p>Laporan Dijana</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Admin Quick Actions
                    echo '<div class="row g-4">';
                    echo '<div class="col-lg-6 col-md-6">';
                    echo '<div class="page-card">';
                    echo '<div class="card-header">';
                    echo '<h4><i class="fas fa-rocket me-2"></i>Tindakan Pentadbir</h4>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<div class="d-grid gap-2">';
                    echo '<a href="?page=pengguna.php" class="btn btn-outline-primary"><i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru</a>';
                    echo '<a href="?page=bayaran.php" class="btn btn-outline-success"><i class="fas fa-plus me-2"></i>Pengurusan Bayaran</a>';
                    echo '<a href="?page=page_data.php" class="btn btn-outline-info"><i class="fas fa-database me-2"></i>Lihat Semua Data</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    
                    echo '<div class="col-lg-6 col-md-6">';
                    echo '<div class="page-card">';
                    echo '<div class="card-header">';
                    echo '<h4><i class="fas fa-bell me-2"></i>Aktiviti Sistem</h4>';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<div class="text-muted text-center py-4">';
                    echo '<i class="fas fa-cogs fa-3x mb-3"></i>';
                    echo '<p>Panel aktiviti sistem untuk pentadbir</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                
                } elseif ($page == 'pengguna.php') {
                    include('page_users.php');
                    
                } elseif ($page == 'bayaran.php') {
                    echo '<div class="page-card">';
                    echo '<div class="card-header">';
                    echo '<h4><i class="fas fa-credit-card me-2"></i>Pengurusan Bayaran</h4>';
                    echo '</div>';
                    echo '<div class="card-body text-center py-5">';
                    echo '<i class="fas fa-credit-card fa-4x text-muted mb-4"></i>';
                    echo '<h5>Modul Pengurusan Bayaran</h5>';
                    echo '<p class="text-muted">Halaman ini akan mengandungi sistem pengurusan bayaran yang komprehensif.</p>';
                    echo '<button class="btn btn-success"><i class="fas fa-plus me-2"></i>Rekod Bayaran Baru</button>';
                    echo '</div>';
                    echo '</div>';
                    
                } elseif ($page == 'page_data.php') {
                    if (file_exists('page_data.php')) {
                        include('page_data.php');
                    } else {
                        echo '<div class="page-card">';
                        echo '<div class="card-header">';
                        echo '<h4><i class="fas fa-database me-2"></i>Semua Data Taskforce</h4>';
                        echo '</div>';
                        echo '<div class="card-body text-center py-5">';
                        echo '<i class="fas fa-database fa-4x text-muted mb-4"></i>';
                        echo '<h5>Data Taskforce</h5>';
                        echo '<p class="text-muted">Halaman ini akan memaparkan semua data taskforce.</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    // Access denied for unknown admin pages
                    echo '<div class="page-card">';
                    echo '<div class="card-body text-center py-5">';
                    echo '<i class="fas fa-exclamation-triangle fa-4x text-warning mb-4"></i>';
                    echo '<h5>Halaman Tidak Dijumpai</h5>';
                    echo '<p class="text-muted">Halaman yang diminta tidak wujud.</p>';
                    echo '<a href="?page=dashboard.php" class="btn btn-primary">Kembali ke Dashboard Admin</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                // CUSTOMER CONTENT
                if ($page == 'customer_dashboard.php' || empty($page)) {
                    if (file_exists('customer_dashboard.php')) {
                        ob_start();
                        include('customer_dashboard.php');
                        $dashboard_content = ob_get_contents();
                        ob_end_clean();
                        
                        if (!empty(trim($dashboard_content))) {
                            echo $dashboard_content;
                        } else {
                            // Fallback content if include fails
                            echo '<div class="welcome-section customer-welcome">';
                            echo '<h2><i class="fas fa-user-circle me-3"></i>Selamat Datang, ' . htmlspecialchars($_SESSION['user_fullname']) . '</h2>';
                            echo '<p class="lead">Dashboard peribadi anda untuk memantau status dan data yang berkaitan dengan anda</p>';
                            echo '<div class="customer-info-badge">';
                            echo '<span class="badge bg-primary me-2">';
                            echo '<i class="fas fa-id-badge me-1"></i>' . htmlspecialchars($_SESSION['user_no_gaji']);
                            echo '</span>';
                            echo '<span class="badge bg-' . ($_SESSION['user_ispaid'] === 'PAID' ? 'success' : 'warning') . '">';
                            echo '<i class="fas fa-' . ($_SESSION['user_ispaid'] === 'PAID' ? 'check-circle' : 'clock') . ' me-1"></i>';
                            echo $_SESSION['user_ispaid'];
                            echo '</span>';
                            echo '</div>';
                            echo '</div>';
                            
                            echo '<div class="row g-4 mb-4">';
                            echo '<div class="col-lg-4 col-md-6 col-sm-6">';
                            echo '<div class="stats-card customer-stat">';
                            echo '<div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">';
                            echo '<i class="fas fa-file-alt"></i>';
                            echo '</div>';
                            echo '<h3>0</h3>';
                            echo '<p>Jumlah Data Saya</p>';
                            echo '</div>';
                            echo '</div>';
                            
                            echo '<div class="col-lg-4 col-md-6 col-sm-6">';
                            echo '<div class="stats-card customer-stat">';
                            echo '<div class="card-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">';
                            echo '<i class="fas fa-check-circle"></i>';
                            echo '</div>';
                            echo '<h3>0</h3>';
                            echo '<p>Selesai</p>';
                            echo '</div>';
                            echo '</div>';
                            
                            echo '<div class="col-lg-4 col-md-6 col-sm-6">';
                            echo '<div class="stats-card customer-stat">';
                            echo '<div class="card-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">';
                            echo '<i class="fas fa-clock"></i>';
                            echo '</div>';
                            echo '<h3>0</h3>';
                            echo '<p>Belum Selesai</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                            echo '<div class="row g-4">';
                            echo '<div class="col-lg-6 col-md-6">';
                            echo '<div class="page-card customer-actions">';
                            echo '<div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">';
                            echo '<h4><i class="fas fa-rocket me-2"></i>Tindakan Pantas</h4>';
                            echo '</div>';
                            echo '<div class="card-body">';
                            echo '<div class="d-grid gap-3">';
                            echo '<a href="?page=customer_data.php" class="btn btn-outline-primary btn-lg">';
                            echo '<i class="fas fa-eye me-2"></i>Lihat Data Saya';
                            echo '</a>';
                            echo '<button class="btn btn-outline-success btn-lg" onclick="alert(\'Hubungi: support@taxtrek.com\')">';
                            echo '<i class="fas fa-phone me-2"></i>Hubungi Sokongan';
                            echo '</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                            echo '<div class="col-lg-6 col-md-6">';
                            echo '<div class="page-card">';
                            echo '<div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">';
                            echo '<h4><i class="fas fa-info-circle me-2"></i>Maklumat Akaun</h4>';
                            echo '</div>';
                            echo '<div class="card-body text-center py-4">';
                            echo '<p><strong>Nama:</strong> ' . htmlspecialchars($_SESSION['user_fullname']) . '</p>';
                            echo '<p><strong>No Gaji:</strong> ' . htmlspecialchars($_SESSION['user_no_gaji']) . '</p>';
                            echo '<p><strong>Status:</strong> <span class="badge bg-' . ($_SESSION['user_ispaid'] === 'PAID' ? 'success' : 'warning') . '">' . $_SESSION['user_ispaid'] . '</span></p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="page-card">';
                        echo '<div class="card-header">';
                        echo '<h4><i class="fas fa-home me-2"></i>Dashboard Saya</h4>';
                        echo '</div>';
                        echo '<div class="card-body text-center py-5">';
                        echo '<i class="fas fa-exclamation-triangle fa-4x text-warning mb-4"></i>';
                        echo '<h5>File Dashboard Tidak Dijumpai</h5>';
                        echo '<p class="text-muted">customer_dashboard.php tidak wujud.</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } elseif ($page == 'customer_data.php') {
                    if (file_exists('customer_data.php')) {
                        include('customer_data.php');
                    } else {
                        echo '<div class="page-card">';
                        echo '<div class="card-header">';
                        echo '<h4><i class="fas fa-file-alt me-2"></i>Data Saya</h4>';
                        echo '</div>';
                        echo '<div class="card-body text-center py-5">';
                        echo '<i class="fas fa-file-alt fa-4x text-muted mb-4"></i>';
                        echo '<h5>Data Peribadi</h5>';
                        echo '<p class="text-muted">Halaman ini akan memaparkan data peribadi anda.</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    // Access denied for unauthorized pages
                    echo '<div class="page-card">';
                    echo '<div class="card-body text-center py-5">';
                    echo '<i class="fas fa-ban fa-4x text-danger mb-4"></i>';
                    echo '<h5>Akses Ditolak</h5>';
                    echo '<p class="text-muted">Anda tidak mempunyai kebenaran untuk mengakses halaman ini.</p>';
                    echo '<a href="?page=customer_dashboard.php" class="btn btn-primary">Kembali ke Dashboard Saya</a>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>

    <script>
        // Enhanced logout function
        function confirmLogout() {
            Swal.fire({
                title: 'Log Keluar?',
                text: "Anda pasti mahu log keluar dari sistem?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-out-alt me-2"></i>Ya, Log Keluar',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                customClass: {
                    popup: 'animated fadeIn faster',
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Sedang Log Keluar...',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 1000,
                        customClass: {
                            popup: 'animated fadeIn faster'
                        }
                    }).then(() => {
                        window.location.href = '?logout=1';
                    });
                }
            });
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('TaxTrek system initialized');
            console.log('User level: <?php echo $_SESSION['user_level']; ?>');
            
            // Simple dropdown initialization with jQuery
            setTimeout(function() {
                // Use jQuery for dropdown handling
                if (typeof $ !== 'undefined') {
                    console.log('Using jQuery for dropdown initialization');
                    
                    // Initialize Bootstrap dropdowns using jQuery
                    $('.dropdown-toggle').dropdown();
                    
                    // Handle user dropdown specifically
                    $('#userDropdown').off('click').on('click', function(e) {
                        e.preventDefault();
                        console.log('User dropdown clicked via jQuery');
                        $(this).dropdown('toggle');
                    });
                    
                    // Close dropdowns when clicking outside
                    $(document).off('click.dropdown').on('click.dropdown', function(e) {
                        if (!$(e.target).closest('.dropdown').length) {
                            $('.dropdown-menu.show').removeClass('show');
                        }
                    });
                    
                } else {
                    console.log('jQuery not available, using vanilla JS');
                    // Fallback to simple vanilla JS
                    const userDropdown = document.getElementById('userDropdown');
                    if (userDropdown) {
                        userDropdown.addEventListener('click', function(e) {
                            e.preventDefault();
                            const menu = this.nextElementSibling;
                            if (menu) {
                                menu.classList.toggle('show');
                            }
                        });
                    }
                }
                
                // Initialize navbar collapse for mobile
                const navbarToggler = document.querySelector('.navbar-toggler');
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarToggler && navbarCollapse) {
                    console.log('Navbar collapse initialized');
                    navbarToggler.addEventListener('click', function() {
                        navbarCollapse.classList.toggle('show');
                    });
                }
                
            }, 500);
            
            // Check if customer dashboard loaded
            const customerWelcome = document.querySelector('.customer-welcome');
            if (customerWelcome) {
                console.log('Customer dashboard content loaded successfully');
            } else {
                const adminWelcome = document.querySelector('.welcome-section');
                if (adminWelcome) {
                    console.log('Admin content loaded successfully');
                } else {
                    console.error('No dashboard content found!');
                }
            }
        });

        // Welcome message for first login
        <?php if (!isset($_SESSION['welcome_shown'])): ?>
        Swal.fire({
            title: 'Selamat Datang!',
            text: 'Anda telah berjaya log masuk ke sistem TaxTrek.',
            icon: 'success',
            confirmButtonColor: '#8000ff',
            confirmButtonText: '<i class="fas fa-rocket me-2"></i>Mulakan',
            customClass: {
                popup: 'animated fadeIn faster',
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
        <?php 
        $_SESSION['welcome_shown'] = true;
        endif; 
        ?>
    </script>
</body>
</html>


