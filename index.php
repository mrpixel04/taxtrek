<?php
session_start();

// If user is already logged in, redirect to main page
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: main.php");
    exit();
}

$error_message = '';
$success_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    try {
        // Include database connection
        if (file_exists("db_connect.php")) {
            include("db_connect.php");
        } else {
            throw new Exception("File sambungan pangkalan data tidak dijumpai!");
        }
        
        $no_gaji = mysqli_real_escape_string($connection, $_POST['no_gaji']);
        $katalaluan = $_POST['katalaluan'];
        
        if (!empty($no_gaji) && !empty($katalaluan)) {
            // Check if connection is successful
            if ($connection === null) {
                $error_message = 'Ralat sambungan pangkalan data: ' . (isset($connection_error) ? $connection_error : 'Sambungan gagal');
            } else {
                // Check if table exists first
                $table_check = "SHOW TABLES LIKE 'TBL_USERS'";
                $table_result = mysqli_query($connection, $table_check);
                
                if (mysqli_num_rows($table_result) == 0) {
                    $error_message = 'Sistem belum disiapkan sepenuhnya. Sila klik <a href="setup.php" class="text-decoration-none"><strong>Setup Sistem</strong></a> untuk menyiapkan pangkalan data.';
                } else {
                    $sql = "SELECT * FROM TBL_USERS WHERE no_gaji = '$no_gaji' AND isactive = 'ACTIVE'";
                    $result = mysqli_query($connection, $sql);
                    
                    if ($result && mysqli_num_rows($result) == 1) {
                        $user = mysqli_fetch_assoc($result);
                        
                        // Verify password
                        if (password_verify($katalaluan, $user['katalaluan'])) {
                            // Update last login datetime
                            $update_sql = "UPDATE TBL_USERS SET last_login_datetime = NOW() WHERE id = " . $user['id'];
                            mysqli_query($connection, $update_sql);
                            
                            // Set session variables
                            $_SESSION['user_logged_in'] = true;
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_no_gaji'] = $user['no_gaji'];
                            $_SESSION['user_fullname'] = $user['fullname'];
                            $_SESSION['user_level'] = $user['userlevel'];
                            $_SESSION['user_ispaid'] = $user['ispaid'];
                            
                            $success_message = 'Log masuk berjaya!';
                        } else {
                            $error_message = 'No Gaji atau Katalaluan tidak betul!';
                        }
                    } else {
                        $error_message = 'No Gaji atau Katalaluan tidak betul!';
                    }
                }
            }
        } else {
            $error_message = 'Sila masukkan No Gaji dan Katalaluan!';
        }
        
        if (isset($connection) && $connection !== null) {
            mysqli_close($connection);
        }
    } catch (Exception $e) {
        $error_message = 'Ralat sistem: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaxTrek - Log Masuk</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">
  <!-- Poppins Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #A67C52 0%, #D2B48C 100%); /* Hazel Theme */
      animation: gradientMove 10s linear infinite;
    }
    
    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      100% { background-position: 100% 50%; }
    }

    .login-container {
      width: 100%;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      background-color: #edf3f3;
    }

    @media (min-width: 576px) {
      .card {
        width: 400px;
      }
    }

    .logo-container {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .logo {
      width: 60px;
      height: auto;
      margin-right: 10px;
    }

    .title {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    /* Button Styles */
    .btn-primary {
      background: linear-gradient(135deg, #A67C52, #D2B48C);
      border: none;
      transition: all 0.3s ease;
      border-radius: 10px;
      padding: 10px 20px;
      cursor: pointer;
      font-weight: 500;
    }

    .btn-primary:hover {
      transform: scale(1.05);
      background: linear-gradient(135deg, #8B7355, #A67C52);
    }

    .form-control {
      border-radius: 8px;
      padding: 12px;
      border: 1px solid #ddd;
    }

    .form-control:focus {
      border-color: #A67C52;
      box-shadow: 0 0 0 0.2rem rgba(166, 124, 82, 0.25);
    }

    .form-label {
      font-weight: 500;
      color: #555;
    }

    .setup-note {
      background-color: #fff3cd;
      border: 1px solid #ffeaa7;
      color: #856404;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .btn-secondary {
      background: linear-gradient(90deg, #6c757d, #495057);
      border: none;
      border-radius: 8px;
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      font-size: 12px;
      transition: all 0.3s ease;
    }

    .btn-secondary:hover {
      transform: scale(1.05);
      color: white;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="card">
      <div class="card-body p-5">
        <div class="logo-container">
          <img src="images/logo.png" alt="TaxTrek Logo" class="logo">
          <h2 class="title">TaxTrek</h2>
        </div>
        
        <div class="setup-note">
          <strong>📋 Nota Setup:</strong><br>
          Jika ini kali pertama, klik butang Setup di bawah untuk menyiapkan pangkalan data.
          <div class="mt-2">
            <a href="setup.php" class="btn btn-secondary">🔧 Setup Sistem</a>
          </div>
        </div>
        
        <form method="POST" action="">
          <div class="mb-3">
            <label for="no_gaji" class="form-label">No Gaji</label>
            <input type="text" class="form-control" id="no_gaji" name="no_gaji" placeholder="Masukkan No Gaji" required>
          </div>
          <div class="mb-3">
            <label for="katalaluan" class="form-label">Katalaluan</label>
            <input type="password" class="form-control" id="katalaluan" name="katalaluan" placeholder="Masukkan Katalaluan" required>
          </div>
          <div class="text-center">
            <button type="submit" name="login" class="btn btn-primary w-100">Log Masuk</button>
          </div>
        </form>
        
        <div class="mt-3 text-center">
          <small class="text-muted">
            Cubaan login: <code>ADMIN001</code> / <code>password123</code>
          </small>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>

  <script>
    <?php if (!empty($error_message)): ?>
    Swal.fire({
      icon: 'error',
      title: 'Ralat!',
      html: '<?php echo $error_message; ?>',
      confirmButtonColor: '#8000ff',
      confirmButtonText: 'OK'
    });
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
    Swal.fire({
      icon: 'success',
      title: 'Berjaya!',
      text: '<?php echo $success_message; ?>',
      confirmButtonColor: '#8000ff',
      confirmButtonText: 'OK'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'main.php';
      }
    });
    <?php endif; ?>
  </script>

</body>
</html>


