<?php
// reset_password_handler.php - Password reset handler for admin
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Tidak dibenarkan. Sila log masuk semula.']);
    exit();
}

// Check if user has admin privileges (optional security check)
if (!isset($_SESSION['user_level']) || $_SESSION['user_level'] !== 'ADMIN') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Hanya pentadbir boleh reset katalaluan.']);
    exit();
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Kaedah tidak dibenarkan.']);
    exit();
}

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Include database connection
    if (file_exists("db_connect.php")) {
        include("db_connect.php");
    } else {
        throw new Exception("File sambungan pangkalan data tidak dijumpai!");
    }

    if ($connection === null) {
        throw new Exception("Ralat sambungan pangkalan data");
    }

    // Get and validate form data
    $user_id = mysqli_real_escape_string($connection, $_POST['user_id'] ?? '');
    $new_password = $_POST['new_password'] ?? '';

    // Validate required fields
    if (empty($user_id)) {
        throw new Exception("ID pengguna diperlukan!");
    }
    if (empty($new_password)) {
        throw new Exception("Katalaluan baru diperlukan!");
    }
    if (strlen($new_password) < 6) {
        throw new Exception("Katalaluan mestilah sekurang-kurangnya 6 aksara!");
    }

    // Check if user exists
    $check_sql = "SELECT id, no_gaji, fullname FROM TBL_USERS WHERE id = '$user_id'";
    $check_result = mysqli_query($connection, $check_sql);
    
    if (!$check_result) {
        throw new Exception("Ralat semakan pengguna: " . mysqli_error($connection));
    }
    
    if (mysqli_num_rows($check_result) === 0) {
        throw new Exception("Pengguna tidak dijumpai!");
    }

    $user_data = mysqli_fetch_assoc($check_result);

    // Hash the new password securely
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in database
    $update_sql = "UPDATE TBL_USERS SET katalaluan = '$hashed_password', updated_at = NOW() WHERE id = '$user_id'";

    if (mysqli_query($connection, $update_sql)) {
        // Log the password reset action
        $admin_no_gaji = $_SESSION['user_no_gaji'];
        $target_no_gaji = $user_data['no_gaji'];
        $target_name = $user_data['fullname'];
        
        error_log("Password reset: Admin $admin_no_gaji reset password for user $target_no_gaji ($target_name)");
        
        echo json_encode([
            'success' => true, 
            'message' => "Katalaluan untuk {$target_name} telah dikemaskini berjaya!",
            'user_info' => [
                'no_gaji' => $target_no_gaji,
                'fullname' => $target_name
            ]
        ]);
    } else {
        throw new Exception("Ralat mengemas kini katalaluan: " . mysqli_error($connection));
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    error_log("Password reset error: " . $e->getMessage());
} finally {
    // Always close connection
    if (isset($connection) && $connection !== null) {
        mysqli_close($connection);
    }
}
?> 