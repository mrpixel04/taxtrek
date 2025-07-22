<?php
session_start();

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
    <title>TaxTrek - Sistem Pengurusan</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     
    <style>
        .navbar {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(90deg, #8000ff, #ff00cc); /* Purple to Pink */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* Highlight active menu item */
        .nav-item.active {
            background-color: #fff;
            border-radius: 20px;
            padding: 6px;
        }

        .navbar-toggler-icon {
            background-color: #fff;
        }

        .navbar-nav .nav-link {
            color: #fff;
            font-weight: 500;
        }

        .navbar-nav .nav-item.active .nav-link {
            color: #000;
        }

        /* Style navbar brand */
        .navbar-brand {
            color: #fff;
            font-size: 24px;
            font-weight: 600;
        }

        /* User dropdown styling */
        .dropdown-toggle::after {
            margin-left: 8px;
        }

        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 250px;
        }

        .user-info {
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 10px 10px 0 0;
        }

        .user-info small {
            color: #6c757d;
        }

        .logout-btn {
            color: #dc3545 !important;
        }

        .logout-btn:hover {
            background-color: #f8d7da;
        }

        .container-main {
            margin-top: 20px;
        }

        /* Fix dropdown toggle cursor */
        .dropdown-toggle {
            cursor: pointer;
        }

        /* Center the main menu items */
        .main-nav {
            margin: 0 auto;
        }

        /* Space between menu and user dropdown */
        .user-nav {
            margin-left: auto;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <!-- Brand/Title on the left -->
        <a class="navbar-brand" href="#">TaxTrek</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main menu items in the center/right -->
            <ul class="navbar-nav main-nav">
                <li class="nav-item <?php if (($_GET['page'] ?? '') == 'dashboard.php' || empty($_GET['page'])) echo 'active'; ?>">
                    <a class="nav-link" href="?page=dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item <?php if (($_GET['page'] ?? '') == 'pengguna.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=pengguna.php">Pengguna</a>
                </li>
                <li class="nav-item <?php if (($_GET['page'] ?? '') == 'bayaran.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=bayaran.php">Bayaran</a>
                </li>
                <li class="nav-item <?php if (($_GET['page'] ?? '') == 'page_data.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=page_data.php">Data Taskforce</a>
                </li>
            </ul>
            
            <!-- User dropdown on the far right -->
            <ul class="navbar-nav user-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li class="user-info">
                            <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></div>
                            <small><?php echo htmlspecialchars($_SESSION['user_no_gaji']); ?></small><br>
                            <small class="badge bg-<?php echo $_SESSION['user_level'] === 'ADMIN' ? 'danger' : 'primary'; ?>">
                                <?php echo $_SESSION['user_level']; ?>
                            </small>
                            <small class="badge bg-<?php echo $_SESSION['user_ispaid'] === 'PAID' ? 'success' : 'warning'; ?> ms-1">
                                <?php echo $_SESSION['user_ispaid']; ?>
                            </small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item logout-btn" href="javascript:void(0);" onclick="confirmLogout(); return false;">
                                <i class="fas fa-sign-out-alt me-2"></i>Log Keluar
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container container-main">
    <?php 
    $page = isset($_GET['page']) ? $_GET['page'] : ''; 

    if ($page == 'dashboard.php' || empty($page)) {
        // Dashboard content
        echo '<div class="row">';
        echo '<div class="col-12">';
        echo '<div class="card">';
        echo '<div class="card-header"><h4><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h4></div>';
        echo '<div class="card-body">';
        echo '<div class="row">';
        echo '<div class="col-md-3"><div class="card bg-primary text-white"><div class="card-body"><h5>Total Pengguna</h5><h2>2</h2></div></div></div>';
        echo '<div class="col-md-3"><div class="card bg-success text-white"><div class="card-body"><h5>Bayaran Aktif</h5><h2>2</h2></div></div></div>';
        echo '<div class="col-md-3"><div class="card bg-info text-white"><div class="card-body"><h5>Data Taskforce</h5><h2>0</h2></div></div></div>';
        echo '<div class="col-md-3"><div class="card bg-warning text-white"><div class="card-body"><h5>Laporan</h5><h2>0</h2></div></div></div>';
        echo '</div>';
        echo '<p class="mt-4">Selamat datang ke sistem TaxTrek, ' . htmlspecialchars($_SESSION['user_fullname']) . '!</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } elseif ($page == 'pengguna.php') {
        echo '<div class="card">';
        echo '<div class="card-header"><h4><i class="fas fa-users me-2"></i>Pengurusan Pengguna</h4></div>';
        echo '<div class="card-body">';
        echo '<p>Halaman pengurusan pengguna akan dibangunkan di sini.</p>';
        echo '</div>';
        echo '</div>';
    } elseif ($page == 'bayaran.php') {
        echo '<div class="card">';
        echo '<div class="card-header"><h4><i class="fas fa-credit-card me-2"></i>Pengurusan Bayaran</h4></div>';
        echo '<div class="card-body">';
        echo '<p>Halaman pengurusan bayaran akan dibangunkan di sini.</p>';
        echo '</div>';
        echo '</div>';
    } elseif ($page == 'page_data.php') {
        if (file_exists('page_data.php')) {
            include('page_data.php');
        } else {
            echo '<div class="card">';
            echo '<div class="card-header"><h4><i class="fas fa-database me-2"></i>Data Taskforce</h4></div>';
            echo '<div class="card-body">';
            echo '<p>Halaman data taskforce akan dibangunkan di sini.</p>';
            echo '</div>';
            echo '</div>';
        }
    } elseif ($page == 'upload_data.php') {
        if (file_exists('page_upload_data.php')) {
            include('page_upload_data.php');
        } else {
            echo '<div class="card">';
            echo '<div class="card-header"><h4><i class="fas fa-upload me-2"></i>Muat Naik Data</h4></div>';
            echo '<div class="card-body">';
            echo '<p>Halaman muat naik data akan dibangunkan di sini.</p>';
            echo '</div>';
            echo '</div>';
        }
    } elseif ($page == 'editalamat.php') {
        if (file_exists('editalamat.php')) {
            include('editalamat.php');
        }
    } else {
        // Default to dashboard
        header("Location: ?page=dashboard.php");
        exit();
    }
    ?>
</div>

<!-- Bootstrap Bundle with Popper (includes dropdown functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>

<script>
// Test if Bootstrap is loaded
console.log('Bootstrap loaded:', typeof bootstrap !== 'undefined');

function confirmLogout() {
    console.log('Logout function called'); // Debug log
    
    Swal.fire({
        title: 'Log Keluar?',
        text: "Anda pasti mahu log keluar dari sistem?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Log Keluar',
        cancelButtonText: 'Batal',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        console.log('Logout result:', result); // Debug log
        if (result.isConfirmed) {
            console.log('Redirecting to logout...'); // Debug log
            window.location.href = '?logout=1';
        }
    }).catch((error) => {
        console.error('SweetAlert error:', error);
    });
}

// Initialize dropdowns manually if needed
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing dropdowns...');
    
    // Enable all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    console.log('Dropdowns initialized:', dropdownList.length);
    
    // Test logout button
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        console.log('Logout button found');
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Logout button clicked via event listener');
            confirmLogout();
            return false;
        });
    } else {
        console.log('Logout button NOT found');
    }
});

// Welcome message for first login
<?php if (!isset($_SESSION['welcome_shown'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Selamat Datang!',
    text: 'Anda telah berjaya log masuk ke sistem TaxTrek.',
    confirmButtonColor: '#8000ff',
    confirmButtonText: 'OK'
});
<?php 
$_SESSION['welcome_shown'] = true;
endif; 
?>
</script>

</body>
</html>

