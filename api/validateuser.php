<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
include('../db_connect.php');

// Function to send JSON response
function sendResponse($status, $data = null, $message = '') {
    $response = [
        'status' => $status,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit();
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', null, 'Only POST method allowed');
}

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Get employee_id from POST data (support both JSON and form data)
$employee_id = '';
if (isset($data['employee_id'])) {
    $employee_id = trim($data['employee_id']);
} elseif (isset($_POST['employee_id'])) {
    $employee_id = trim($_POST['employee_id']);
}

// Validate input
if (empty($employee_id)) {
    sendResponse('error', null, 'Employee ID is required');
}

// Validate employee_id format (numbers only)
if (!preg_match('/^\d+$/', $employee_id)) {
    sendResponse('error', null, 'Employee ID must contain numbers only');
}

try {
    // Check database connection
    if ($connection === null) {
        sendResponse('error', null, 'Database connection failed');
    }

    // Prepare SQL query to prevent SQL injection
    $sql = "SELECT 
                id,
                no_gaji,
                fullname,
                userlevel,
                isactive,
                ispaid,
                hpno,
                email,
                created_at,
                last_login_datetime
            FROM TBL_USERS 
            WHERE no_gaji = ? 
            AND isactive = 'ACTIVE'";
    
    $stmt = mysqli_prepare($connection, $sql);
    
    if (!$stmt) {
        sendResponse('error', null, 'Database query preparation failed');
    }
    
    // Bind parameter
    mysqli_stmt_bind_param($stmt, "s", $employee_id);
    
    // Execute query
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) == 1) {
        // User found
        $user = mysqli_fetch_assoc($result);
        
        // Prepare response data
        $userData = [
            'employee_exists' => true,
            'employee_id' => $user['no_gaji'],
            'employee_name' => $user['fullname'],
            'department' => 'General', // Default since not in table
            'position' => ucfirst(strtolower($user['userlevel'])), // ADMIN/CUSTOMER
            'employment_status' => $user['isactive'],
            'payment_status' => $user['ispaid'],
            'phone_number' => $user['hpno'],
            'email' => $user['email'],
            'last_login' => $user['last_login_datetime'],
            'member_since' => $user['created_at']
        ];
        
        sendResponse('success', $userData, 'Pengguna berdaftar');
        
    } elseif ($result && mysqli_num_rows($result) == 0) {
        // User not found
        $userData = [
            'employee_exists' => false,
            'employee_id' => $employee_id
        ];
        
        sendResponse('error', $userData, 'Pengguna tidak berdaftar');
        
    } else {
        // Query error
        sendResponse('error', null, 'Database query failed');
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
    
} catch (Exception $e) {
    // Handle any exceptions
    sendResponse('error', null, 'Server error: ' . $e->getMessage());
    
} finally {
    // Close database connection
    if (isset($connection)) {
        mysqli_close($connection);
    }
}
?>