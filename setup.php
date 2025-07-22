<?php
session_start();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_table'])) {
    try {
        include("db_connect.php");
        
        // Check if connection is successful
        if ($connection === null) {
            $message = 'Ralat sambungan pangkalan data: ' . (isset($connection_error) ? $connection_error : 'Sambungan gagal');
            $message_type = 'error';
        } else {
            // SQL to create table
            $create_table_sql = "
            CREATE TABLE IF NOT EXISTS TBL_USERS (
                id INT AUTO_INCREMENT PRIMARY KEY,
                no_gaji VARCHAR(50) UNIQUE NOT NULL,
                katalaluan VARCHAR(255) NOT NULL,
                fullname VARCHAR(100) NOT NULL,
                userlevel ENUM('CUSTOMER', 'ADMIN') DEFAULT 'CUSTOMER',
                last_login_datetime DATETIME NULL,
                isactive ENUM('ACTIVE', 'NOT ACTIVE') DEFAULT 'ACTIVE',
                ispaid ENUM('PAID', 'NOT PAID') DEFAULT 'NOT PAID',
                hpno VARCHAR(20),
                email VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            
            if (mysqli_query($connection, $create_table_sql)) {
                // Insert sample users
                $insert_users_sql = "
                INSERT IGNORE INTO TBL_USERS (no_gaji, katalaluan, fullname, userlevel, isactive, ispaid, hpno, email) VALUES
                ('ADMIN001', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Sistem', 'ADMIN', 'ACTIVE', 'PAID', '0123456789', 'admin@taxtrek.com'),
                ('EMP001', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pekerja Satu', 'CUSTOMER', 'ACTIVE', 'PAID', '0123456788', 'pekerja1@taxtrek.com')";
                
                if (mysqli_query($connection, $insert_users_sql)) {
                    $message = 'Jadual TBL_USERS berjaya dicipta dan data sampel telah dimasukkan!';
                    $message_type = 'success';
                } else {
                    $message = 'Jadual dicipta tetapi gagal memasukkan data sampel: ' . mysqli_error($connection);
                    $message_type = 'warning';
                }
            } else {
                $message = 'Gagal mencipta jadual: ' . mysqli_error($connection);
                $message_type = 'error';
            }
            
            mysqli_close($connection);
        }
    } catch (Exception $e) {
        $message = 'Ralat: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Check if table already exists
$table_exists = false;
try {
    include("db_connect.php");
    if ($connection !== null) {
        $table_check = "SHOW TABLES LIKE 'TBL_USERS'";
        $table_result = mysqli_query($connection, $table_check);
        $table_exists = (mysqli_num_rows($table_result) > 0);
        mysqli_close($connection);
    }
} catch (Exception $e) {
    // Connection failed
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaxTrek - Setup Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(90deg, #ff00cc, #8000ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .setup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 600px;
            width: 100%;
        }
        
        .btn-primary {
            background: linear-gradient(90deg, #8000ff, #ff00cc);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #7000ef, #ef00bc);
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .status-exists {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-missing {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="setup-card">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">🔧 Setup Sistem TaxTrek</h2>
        <p class="text-muted">Sediakan pangkalan data untuk sistem log masuk</p>
    </div>
    
    <div class="mb-4">
        <h5>Status Jadual Pangkalan Data:</h5>
        <div class="d-flex align-items-center mt-2">
            <span class="me-3"><strong>TBL_USERS:</strong></span>
            <?php if ($table_exists): ?>
                <span class="status-badge status-exists">✅ Sudah Wujud</span>
            <?php else: ?>
                <span class="status-badge status-missing">❌ Belum Dicipta</span>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (!$table_exists): ?>
    <div class="alert alert-info">
        <strong>📋 Maklumat:</strong><br>
        Jadual <code>TBL_USERS</code> belum dicipta. Klik butang di bawah untuk mencipta jadual dan memasukkan data pengguna sampel.
    </div>
    
    <form method="POST" action="">
        <div class="text-center">
            <button type="submit" name="create_table" class="btn btn-primary btn-lg">
                🚀 Cipta Jadual TBL_USERS
            </button>
        </div>
    </form>
    <?php else: ?>
    <div class="alert alert-success">
        <strong>✅ Sistem Sudah Sedia!</strong><br>
        Jadual pangkalan data sudah dicipta. Anda boleh menggunakan sistem log masuk sekarang.
    </div>
    
    <div class="text-center">
        <a href="index.php" class="btn btn-primary btn-lg">
            🔑 Pergi Ke Halaman Log Masuk
        </a>
    </div>
    <?php endif; ?>
    
    <hr class="my-4">
    
    <div class="row">
        <div class="col-md-6">
            <h6 class="fw-bold">👤 Akaun Admin:</h6>
            <small class="text-muted">
                No Gaji: <code>ADMIN001</code><br>
                Katalaluan: <code>password123</code>
            </small>
        </div>
        <div class="col-md-6">
            <h6 class="fw-bold">👥 Akaun Pekerja:</h6>
            <small class="text-muted">
                No Gaji: <code>EMP001</code><br>
                Katalaluan: <code>password123</code>
            </small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>

<script>
<?php if (!empty($message)): ?>
    <?php if ($message_type === 'success'): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berjaya!',
        text: '<?php echo $message; ?>',
        confirmButtonColor: '#8000ff',
        confirmButtonText: 'OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.reload();
        }
    });
    <?php elseif ($message_type === 'error'): ?>
    Swal.fire({
        icon: 'error',
        title: 'Ralat!',
        text: '<?php echo $message; ?>',
        confirmButtonColor: '#8000ff',
        confirmButtonText: 'OK'
    });
    <?php elseif ($message_type === 'warning'): ?>
    Swal.fire({
        icon: 'warning',
        title: 'Amaran!',
        text: '<?php echo $message; ?>',
        confirmButtonColor: '#8000ff',
        confirmButtonText: 'OK'
    });
    <?php endif; ?>
<?php endif; ?>
</script>

</body>
</html> 