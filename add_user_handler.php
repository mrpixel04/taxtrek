<?php
// add_user_handler.php - Separate AJAX handler for adding users
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Tidak dibenarkan. Sila log masuk semula.']);
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

    // Get and sanitize form data
    $no_gaji = mysqli_real_escape_string($connection, $_POST['no_gaji'] ?? '');
    $fullname = mysqli_real_escape_string($connection, $_POST['fullname'] ?? '');
    $email = mysqli_real_escape_string($connection, $_POST['email'] ?? '');
    $hpno = mysqli_real_escape_string($connection, $_POST['hpno'] ?? '');
    $userlevel = mysqli_real_escape_string($connection, $_POST['userlevel'] ?? 'CUSTOMER');
    $isactive = mysqli_real_escape_string($connection, $_POST['isactive'] ?? 'ACTIVE');
    $ispaid = mysqli_real_escape_string($connection, $_POST['ispaid'] ?? 'NOT PAID');
    $katalaluan = $_POST['katalaluan'] ?? '';

    // Validate required fields
    if (empty($no_gaji)) {
        throw new Exception("No Gaji diperlukan!");
    }
    if (empty($fullname)) {
        throw new Exception("Nama Penuh diperlukan!");
    }
    if (empty($katalaluan)) {
        throw new Exception("Katalaluan diperlukan!");
    }

    // Check if no_gaji already exists
    $check_sql = "SELECT id FROM TBL_USERS WHERE no_gaji = '$no_gaji'";
    $check_result = mysqli_query($connection, $check_sql);
    
    if (!$check_result) {
        throw new Exception("Ralat semakan data: " . mysqli_error($connection));
    }
    
    if (mysqli_num_rows($check_result) > 0) {
        throw new Exception("No Gaji '$no_gaji' sudah wujud dalam sistem!");
    }

    // Validate email format if provided
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Format email tidak sah!");
    }

    // Hash password securely
    $hashed_password = password_hash($katalaluan, PASSWORD_DEFAULT);

    // Insert new user
    $sql = "INSERT INTO TBL_USERS (no_gaji, katalaluan, fullname, userlevel, isactive, ispaid, hpno, email) 
            VALUES ('$no_gaji', '$hashed_password', '$fullname', '$userlevel', '$isactive', '$ispaid', '$hpno', '$email')";

    if (mysqli_query($connection, $sql)) {
        $new_user_id = mysqli_insert_id($connection);
        
        // Log the action (optional)
        error_log("New user added: ID=$new_user_id, No_Gaji=$no_gaji, Name=$fullname by user " . $_SESSION['user_no_gaji']);
        
        echo json_encode([
            'success' => true, 
            'message' => "Pengguna baru '$fullname' telah ditambah berjaya!",
            'user_id' => $new_user_id
        ]);
    } else {
        throw new Exception("Ralat menambah pengguna: " . mysqli_error($connection));
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    error_log("Add user error: " . $e->getMessage());
} finally {
    // Always close connection
    if (isset($connection) && $connection !== null) {
        mysqli_close($connection);
    }
}
?> 