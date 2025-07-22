<?php
// customer_dashboard.php - Customer-specific dashboard
// Session is already checked in main.php, no need to redirect here

// Include database connection with error handling
$connection = null;
$connection_error = '';

try {
    if (file_exists("db_connect.php")) {
        include("db_connect.php");
    } else {
        $connection_error = 'db_connect.php file not found';
    }
} catch (Exception $e) {
    $connection_error = $e->getMessage();
}

// Get customer's data summary
$customerStats = [
    'total_data' => 0,
    'completed' => 0,
    'pending' => 0
];

if ($connection !== null) {
    $user_id = $_SESSION['user_id'];
    $user_no_gaji = $_SESSION['user_no_gaji'];
    $user_fullname = $_SESSION['user_fullname'];
    
    // Check if TBL_DATA table exists
    $table_check = "SHOW TABLES LIKE 'TBL_DATA'";
    $table_result = mysqli_query($connection, $table_check);
    
    if ($table_result && mysqli_num_rows($table_result) > 0) {
        // Get customer-specific data counts using correct field names
        // Search by INSBY (user ID), NAMAPEMILIK (owner name), or NAMAPEMILIK_BODY
        $sql = "SELECT COUNT(*) as total_data FROM TBL_DATA WHERE 
                INSBY = '$user_id' OR 
                NAMAPEMILIK LIKE '%$user_fullname%' OR 
                NAMAPEMILIK_BODY LIKE '%$user_fullname%'";
        $result = mysqli_query($connection, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $customerStats['total_data'] = $row['total_data'] ?? 0;
        }
        
        // Get completed tasks (STATUS_DATA = 'SUDAH BUAT')
        $sql = "SELECT COUNT(*) as completed FROM TBL_DATA WHERE 
                (INSBY = '$user_id' OR 
                 NAMAPEMILIK LIKE '%$user_fullname%' OR 
                 NAMAPEMILIK_BODY LIKE '%$user_fullname%') 
                AND STATUS_DATA = 'SUDAH BUAT'";
        $result = mysqli_query($connection, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $customerStats['completed'] = $row['completed'] ?? 0;
        }
        
        // Get pending tasks (STATUS_DATA = 'BELUM BUAT')
        $sql = "SELECT COUNT(*) as pending FROM TBL_DATA WHERE 
                (INSBY = '$user_id' OR 
                 NAMAPEMILIK LIKE '%$user_fullname%' OR 
                 NAMAPEMILIK_BODY LIKE '%$user_fullname%') 
                AND STATUS_DATA = 'BELUM BUAT'";
        $result = mysqli_query($connection, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $customerStats['pending'] = $row['pending'] ?? 0;
        }
    }
}
?>

<div class="welcome-section customer-welcome">
    <h2><i class="fas fa-user-circle me-3"></i>Selamat Datang, <?php echo htmlspecialchars($_SESSION['user_fullname']); ?></h2>
    <p class="lead">Dashboard peribadi anda untuk memantau status dan data yang berkaitan dengan anda</p>
    <div class="customer-info-badge">
        <span class="badge bg-primary me-2">
            <i class="fas fa-id-badge me-1"></i><?php echo htmlspecialchars($_SESSION['user_no_gaji']); ?>
        </span>
        <span class="badge bg-<?php echo $_SESSION['user_ispaid'] === 'PAID' ? 'success' : 'warning'; ?>">
            <i class="fas fa-<?php echo $_SESSION['user_ispaid'] === 'PAID' ? 'check-circle' : 'clock'; ?> me-1"></i>
            <?php echo $_SESSION['user_ispaid']; ?>
        </span>
    </div>
</div>

<!-- Customer Statistics -->
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="stats-card customer-stat">
            <div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3><?php echo $customerStats['total_data'] ?? 0; ?></h3>
            <p>Jumlah Data Saya</p>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="stats-card customer-stat">
            <div class="card-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3><?php echo $customerStats['completed'] ?? 0; ?></h3>
            <p>Selesai</p>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="stats-card customer-stat">
            <div class="card-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-clock"></i>
            </div>
            <h3><?php echo $customerStats['pending'] ?? 0; ?></h3>
            <p>Belum Selesai</p>
        </div>
    </div>
</div>

<!-- Customer Actions and Information -->
<div class="row g-4">
    <div class="col-lg-6 col-md-6">
        <div class="page-card customer-actions">
            <div class="card-header bg-gradient">
                <h4><i class="fas fa-rocket me-2"></i>Tindakan Pantas</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="?page=customer_data.php" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-eye me-2"></i>Lihat Data Saya
                    </a>
                    <button class="btn btn-outline-success btn-lg" onclick="showContactInfo()">
                        <i class="fas fa-phone me-2"></i>Hubungi Sokongan
                    </button>
                    <button class="btn btn-outline-info btn-lg" onclick="showAccountInfo()">
                        <i class="fas fa-info-circle me-2"></i>Maklumat Akaun
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-6">
        <div class="page-card customer-status">
            <div class="card-header bg-gradient">
                <h4><i class="fas fa-chart-pie me-2"></i>Status Keseluruhan</h4>
            </div>
            <div class="card-body">
                <?php if (($customerStats['total_data'] ?? 0) > 0): ?>
                    <div class="progress-container">
                        <?php 
                        $total = $customerStats['total_data'];
                        $completed = $customerStats['completed'] ?? 0;
                        $percentage = ($total > 0) ? round(($completed / $total) * 100) : 0;
                        ?>
                        <div class="progress-info mb-2">
                            <span>Kemajuan Keseluruhan</span>
                            <span class="fw-bold"><?php echo $percentage; ?>%</span>
                        </div>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="status-breakdown">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-check-circle text-success me-2"></i>Selesai:</span>
                                <span class="fw-bold"><?php echo $completed; ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="fas fa-clock text-warning me-2"></i>Belum Selesai:</span>
                                <span class="fw-bold"><?php echo ($customerStats['pending'] ?? 0); ?></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5>Tiada Data Dijumpai</h5>
                        <p class="text-muted">Anda belum mempunyai sebarang data dalam sistem.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Important Notice for Customers -->
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading">Maklumat Penting</h5>
                <p class="mb-0">Jika anda mempunyai sebarang pertanyaan atau memerlukan bantuan, sila hubungi pentadbir sistem atau gunakan butang "Hubungi Sokongan" di atas.</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Customer Dashboard Specific Styles */
.customer-welcome {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
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
}

.progress-bar {
    border-radius: 10px;
}

.status-breakdown {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

/* Responsive adjustments for customer dashboard */
@media (max-width: 768px) {
    .customer-welcome {
        padding: 1.5rem;
        text-align: center;
    }
    
    .customer-welcome h2 {
        font-size: 1.5rem;
    }
}
</style>

<script>
// Customer dashboard functionality
function showContactInfo() {
    Swal.fire({
        title: 'Hubungi Sokongan',
        html: `
            <div class="text-start">
                <p><i class="fas fa-phone me-2 text-primary"></i><strong>Telefon:</strong> +603-1234-5678</p>
                <p><i class="fas fa-envelope me-2 text-primary"></i><strong>Email:</strong> support@taxtrek.com</p>
                <p><i class="fas fa-clock me-2 text-primary"></i><strong>Waktu Operasi:</strong> 9:00 AM - 6:00 PM</p>
                <hr>
                <p class="text-muted small">Sila nyatakan No Gaji anda (${<?php echo json_encode($_SESSION['user_no_gaji']); ?>}) semasa menghubungi sokongan.</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#007bff'
    });
}

function showAccountInfo() {
    Swal.fire({
        title: 'Maklumat Akaun Anda',
        html: `
            <div class="text-start">
                <p><i class="fas fa-user me-2 text-primary"></i><strong>Nama:</strong> <?php echo htmlspecialchars($_SESSION['user_fullname']); ?></p>
                <p><i class="fas fa-id-badge me-2 text-primary"></i><strong>No Gaji:</strong> <?php echo htmlspecialchars($_SESSION['user_no_gaji']); ?></p>
                <p><i class="fas fa-shield-alt me-2 text-primary"></i><strong>Level:</strong> <?php echo htmlspecialchars($_SESSION['user_level']); ?></p>
                <p><i class="fas fa-credit-card me-2 text-primary"></i><strong>Status Bayaran:</strong> <?php echo htmlspecialchars($_SESSION['user_ispaid']); ?></p>
                <hr>
                <p class="text-muted small">Untuk mengemas kini maklumat akaun, sila hubungi pentadbir sistem.</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#007bff'
    });
}

console.log('Customer dashboard initialized successfully');
</script> 