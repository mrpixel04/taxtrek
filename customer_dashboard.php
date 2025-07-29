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

<!-- Modern Clean Header -->
<div class="modern-welcome-header">
    <div class="welcome-content">
        <div class="welcome-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="welcome-text">
            <h1>Selamat Datang</h1>
            <h2><?php echo htmlspecialchars($_SESSION['user_fullname']); ?></h2>
            <p class="welcome-subtitle">Dashboard peribadi anda untuk memantau status dan data</p>
        </div>
    </div>
    <div class="user-badges">
        <div class="badge-item">
            <i class="fas fa-id-badge"></i>
            <span><?php echo htmlspecialchars($_SESSION['user_no_gaji']); ?></span>
        </div>
        <div class="badge-item status-<?php echo $_SESSION['user_ispaid'] === 'PAID' ? 'paid' : 'pending'; ?>">
            <i class="fas fa-<?php echo $_SESSION['user_ispaid'] === 'PAID' ? 'check-circle' : 'clock'; ?>"></i>
            <span><?php echo $_SESSION['user_ispaid']; ?></span>
        </div>
    </div>
</div>

<!-- Modern Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card total-data">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo $customerStats['total_data'] ?? 0; ?></div>
            <div class="stat-label">Jumlah Data Saya</div>
        </div>
    </div>
    
    <div class="stat-card completed-data">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo $customerStats['completed'] ?? 0; ?></div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    
    <div class="stat-card pending-data">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo $customerStats['pending'] ?? 0; ?></div>
            <div class="stat-label">Belum Selesai</div>
        </div>
    </div>
</div>

<!-- Modern Action Cards -->
<div class="main-content-grid">
    <div class="action-section">
        <h3 class="section-title">
            <i class="fas fa-rocket"></i>
            Tindakan Pantas
        </h3>
        <div class="action-buttons">
            <a href="?page=customer_data.php" class="modern-btn primary">
                <div class="btn-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Lihat Data Saya</span>
                    <span class="btn-subtitle">Semak status dan data anda</span>
                </div>
            </a>
            
            <button class="modern-btn secondary" onclick="showContactInfo()">
                <div class="btn-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Hubungi Sokongan</span>
                    <span class="btn-subtitle">Dapatkan bantuan teknikal</span>
                </div>
            </button>
            
            <button class="modern-btn tertiary" onclick="showAccountInfo()">
                <div class="btn-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Maklumat Akaun</span>
                    <span class="btn-subtitle">Lihat profil dan status</span>
                </div>
            </button>
        </div>
    </div>
    
    <div class="status-section">
        <h3 class="section-title">
            <i class="fas fa-chart-line"></i>
            Status Keseluruhan
        </h3>
        <div class="status-content">
            <?php if (($customerStats['total_data'] ?? 0) > 0): ?>
                <?php 
                $total = $customerStats['total_data'];
                $completed = $customerStats['completed'] ?? 0;
                $percentage = ($total > 0) ? round(($completed / $total) * 100) : 0;
                ?>
                <div class="progress-circle-container">
                    <div class="progress-percentage">
                        <span class="percentage-number"><?php echo $percentage; ?></span>
                        <span class="percentage-symbol">%</span>
                    </div>
                    <div class="progress-label">Kemajuan Keseluruhan</div>
                </div>
                
                <div class="progress-bar-modern">
                    <div class="progress-fill" style="width: <?php echo $percentage; ?>%"></div>
                </div>
                
                <div class="status-details">
                    <div class="status-item completed">
                        <i class="fas fa-check-circle"></i>
                        <span class="status-label">Selesai</span>
                        <span class="status-count"><?php echo $completed; ?></span>
                    </div>
                    <div class="status-item pending">
                        <i class="fas fa-clock"></i>
                        <span class="status-label">Belum Selesai</span>
                        <span class="status-count"><?php echo ($customerStats['pending'] ?? 0); ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>Tiada Data Dijumpai</h4>
                    <p>Anda belum mempunyai sebarang data dalam sistem.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Clean Notice Section -->
<div class="info-notice">
    <div class="notice-icon">
        <i class="fas fa-info-circle"></i>
    </div>
    <div class="notice-content">
        <h4>Maklumat Penting</h4>
        <p>Jika anda mempunyai sebarang pertanyaan atau memerlukan bantuan, sila hubungi pentadbir sistem atau gunakan butang "Hubungi Sokongan" di atas.</p>
    </div>
</div>

<style>
/* Modern Clean Customer Dashboard Styles */
:root {
    --hazel: #A67C52;
    --hazel-light: #D2B48C;
    --hazel-dark: #8B7355;
    --cream: #FBF9F6;
    --warm-white: #FEFEFE;
    --soft-gray: #F5F5F5;
    --text-primary: #2C3E50;
    --text-secondary: #7F8C8D;
    --accent-blue: #667eea;
    --accent-green: #27AE60;
    --accent-orange: #E67E22;
    --shadow-soft: 0 2px 20px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 5px 30px rgba(0, 0, 0, 0.12);
}

/* Modern Welcome Header */
.modern-welcome-header {
    background: linear-gradient(135deg, var(--hazel) 0%, var(--hazel-light) 100%);
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 3rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.modern-welcome-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.welcome-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.welcome-avatar {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.welcome-text h1 {
    font-size: 1.2rem;
    font-weight: 300;
    margin: 0;
    opacity: 0.9;
}

.welcome-text h2 {
    font-size: 2rem;
    font-weight: 600;
    margin: 0.2rem 0;
}

.welcome-subtitle {
    font-size: 1rem;
    opacity: 0.8;
    margin: 0;
}

.user-badges {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.badge-item {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.badge-item.status-paid {
    background: rgba(39, 174, 96, 0.2);
    border-color: rgba(39, 174, 96, 0.3);
}

.badge-item.status-pending {
    background: rgba(231, 126, 34, 0.2);
    border-color: rgba(231, 126, 34, 0.3);
}

/* Modern Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: var(--warm-white);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow-soft);
    transition: all 0.3s ease;
    border: 1px solid rgba(166, 124, 82, 0.1);
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.total-data .stat-icon {
    background: linear-gradient(135deg, var(--accent-blue), #5A67D8);
}

.completed-data .stat-icon {
    background: linear-gradient(135deg, var(--accent-green), #48BB78);
}

.pending-data .stat-icon {
    background: linear-gradient(135deg, var(--accent-orange), #ED8936);
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    line-height: 1;
}

.stat-label {
    font-size: 0.95rem;
    color: var(--text-secondary);
    margin-top: 0.5rem;
    font-weight: 500;
}

/* Main Content Grid */
.main-content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

.action-section,
.status-section {
    background: var(--warm-white);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow-soft);
    border: 1px solid rgba(166, 124, 82, 0.1);
}

.section-title {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: var(--hazel);
    font-size: 1.1rem;
}

/* Modern Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.modern-btn {
    background: var(--warm-white);
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 1.2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.3s ease;
    cursor: pointer;
}

.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
    border-color: var(--hazel);
    color: var(--text-primary);
    text-decoration: none;
}

.modern-btn.primary:hover {
    border-color: var(--accent-blue);
    background: rgba(102, 126, 234, 0.05);
}

.modern-btn.secondary:hover {
    border-color: var(--accent-green);
    background: rgba(39, 174, 96, 0.05);
}

.modern-btn.tertiary:hover {
    border-color: var(--accent-orange);
    background: rgba(231, 126, 34, 0.05);
}

.btn-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    background: var(--soft-gray);
    color: var(--text-secondary);
    transition: all 0.3s ease;
}

.modern-btn.primary .btn-icon {
    background: rgba(102, 126, 234, 0.1);
    color: var(--accent-blue);
}

.modern-btn.secondary .btn-icon {
    background: rgba(39, 174, 96, 0.1);
    color: var(--accent-green);
}

.modern-btn.tertiary .btn-icon {
    background: rgba(231, 126, 34, 0.1);
    color: var(--accent-orange);
}

.btn-content {
    flex: 1;
    text-align: left;
}

.btn-title {
    display: block;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.2rem;
}

.btn-subtitle {
    display: block;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

/* Status Section */
.status-content {
    text-align: center;
}

.progress-circle-container {
    margin-bottom: 2rem;
}

.progress-percentage {
    display: flex;
    align-items: baseline;
    justify-content: center;
    margin-bottom: 0.5rem;
}

.percentage-number {
    font-size: 3rem;
    font-weight: 700;
    color: var(--hazel);
}

.percentage-symbol {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-secondary);
    margin-left: 0.2rem;
}

.progress-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.progress-bar-modern {
    width: 100%;
    height: 8px;
    background: #E5E7EB;
    border-radius: 10px;
    margin: 1.5rem 0;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--hazel), var(--hazel-light));
    border-radius: 10px;
    transition: width 0.5s ease;
}

.status-details {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.status-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--soft-gray);
    border-radius: 10px;
    flex: 1;
}

.status-item i {
    font-size: 1.2rem;
}

.status-item.completed i {
    color: var(--accent-green);
}

.status-item.pending i {
    color: var(--accent-orange);
}

.status-label {
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.status-count {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    font-size: 4rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h4 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-secondary);
    margin: 0;
}

/* Info Notice */
.info-notice {
    background: var(--cream);
    border: 1px solid var(--hazel-light);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-top: 2rem;
}

.notice-icon {
    width: 40px;
    height: 40px;
    background: var(--hazel-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--hazel-dark);
    font-size: 1.1rem;
    flex-shrink: 0;
}

.notice-content h4 {
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.notice-content p {
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modern-welcome-header {
        padding: 2rem 1.5rem;
        text-align: center;
    }
    
    .welcome-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .welcome-text h2 {
        font-size: 1.5rem;
    }
    
    .main-content-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .status-details {
        flex-direction: column;
    }
    
    .user-badges {
        justify-content: center;
    }
    
    .action-section,
    .status-section {
        padding: 1.5rem;
    }
    
    .info-notice {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .modern-welcome-header {
        padding: 1.5rem 1rem;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .modern-btn {
        padding: 1rem;
    }
    
    .btn-title {
        font-size: 0.9rem;
    }
    
    .btn-subtitle {
        font-size: 0.8rem;
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

console.log('Modern customer dashboard initialized successfully');
</script> 