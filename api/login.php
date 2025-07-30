<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection (same as index.php)
include('../db_connect.php');



$no_gaji = "zack";
$katalaluan = "password123";

try {
    // Check if connection is successful (same logic as index.php)
    if ($connection === null) {
        echo json_encode([
            'success' => false,
            'message' => 'Ralat sambungan pangkalan data'
        ]);
        exit();
    }

    // Same SQL query as index.php
    $sql = "SELECT * FROM TBL_USERS WHERE no_gaji = '$no_gaji' AND isactive = 'ACTIVE'";
    $result = mysqli_query($connection, $sql);
    
    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password (same as index.php)
        if (password_verify($katalaluan, $user['katalaluan'])) {
            // Check if user is CUSTOMER
            if ($user['userlevel'] !== 'CUSTOMER') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Akses ditolak. Hanya pelanggan dibenarkan.'
                ]);
                exit();
            }

            // Update last login datetime (same as index.php)
            $update_sql = "UPDATE TBL_USERS SET last_login_datetime = NOW() WHERE id = " . $user['id'];
            mysqli_query($connection, $update_sql);
            
            // Return success with user data
            echo json_encode([
                'success' => true,
                'message' => 'Log masuk berjaya!',
                'user' => [
                    'id' => $user['id'],
                    'no_gaji' => $user['no_gaji'],
                    'fullname' => $user['fullname'],
                    'userlevel' => $user['userlevel'],
                    'ispaid' => $user['ispaid'],
                    'email' => isset($user['email']) ? $user['email'] : '',
                    'hpno' => isset($user['hpno']) ? $user['hpno'] : ''
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No Gaji atau Katalaluan tidak betul!'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No Gaji atau Katalaluan tidak betul!'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ralat sistem: ' . $e->getMessage()
    ]);
}

if (isset($connection) && $connection !== null) {
    mysqli_close($connection);
}
?> 