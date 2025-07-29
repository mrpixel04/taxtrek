<?php
// update_bulk_date.php - Handle bulk date updates for TARIKH_BUAT field
session_start();

// Security: Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_level'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Tidak dibenarkan - Sila log masuk']);
    exit;
}

// Include database connection
include("db_connect.php");

// Set response header to JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak dibenarkan']);
    exit;
}

// Validate input
if (!isset($_POST['date']) || !isset($_POST['record_ids'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$selected_date = trim($_POST['date']);
$record_ids = $_POST['record_ids'];

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selected_date)) {
    echo json_encode(['success' => false, 'message' => 'Format tarikh tidak sah']);
    exit;
}

// Validate record IDs
if (!is_array($record_ids) || empty($record_ids)) {
    echo json_encode(['success' => false, 'message' => 'Tiada rekod dipilih']);
    exit;
}

// Check database connection
if ($connection === null) {
    echo json_encode(['success' => false, 'message' => 'Gagal menyambung ke pangkalan data']);
    exit;
}

try {
    // Get user information for security check
    $user_id = $_SESSION['user_id'];
    $user_fullname = $_SESSION['user_fullname'];
    $user_level = $_SESSION['user_level'];
    
    // Start transaction
    mysqli_begin_transaction($connection);
    
    $updated_count = 0;
    $errors = [];
    
    foreach ($record_ids as $record_id) {
        // Sanitize record ID
        $record_id = mysqli_real_escape_string($connection, $record_id);
        
        // Security: Only allow users to update their own records (unless admin)
        if ($user_level === 'CUSTOMER') {
            // For customers, only allow updating records that belong to them
            $check_sql = "SELECT iddata FROM TBL_DATA WHERE 
                         iddata = '$record_id' AND 
                         (INSBY = '$user_id' OR 
                          NAMAPEMILIK LIKE '%$user_fullname%' OR 
                          NAMAPEMILIK_BODY LIKE '%$user_fullname%')";
            
            $check_result = mysqli_query($connection, $check_sql);
            if (!$check_result || mysqli_num_rows($check_result) === 0) {
                $errors[] = "Rekod ID $record_id tidak ditemui atau tidak dibenarkan";
                continue;
            }
        }
        
        // Update the TARIKH_BUAT field
        $update_sql = "UPDATE TBL_DATA 
                      SET TARIKH_BUAT = '$selected_date'
                      WHERE iddata = '$record_id'";
        
        if (mysqli_query($connection, $update_sql)) {
            $affected_rows = mysqli_affected_rows($connection);
            if ($affected_rows > 0) {
                $updated_count++;
            } else {
                $errors[] = "Rekod ID $record_id tidak dikemas kini (mungkin tiada perubahan)";
            }
        } else {
            $errors[] = "Gagal mengemas kini rekod ID $record_id: " . mysqli_error($connection);
        }
    }
    
    // Commit transaction if we have successful updates
    if ($updated_count > 0) {
        mysqli_commit($connection);
        
        // Log the action for audit trail
        $log_message = "Bulk date update: $updated_count records updated to $selected_date by user $user_id ($user_fullname)";
        error_log($log_message);
        
        $response = [
            'success' => true,
            'updated_count' => $updated_count,
            'message' => "Berjaya mengemas kini $updated_count rekod"
        ];
        
        // Include errors if any
        if (!empty($errors)) {
            $response['warnings'] = $errors;
        }
        
        echo json_encode($response);
    } else {
        // Rollback if no records were updated
        mysqli_rollback($connection);
        
        echo json_encode([
            'success' => false,
            'message' => 'Tiada rekod dikemas kini',
            'errors' => $errors
        ]);
    }
    
} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($connection);
    
    error_log("Bulk date update error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Ralat sistem: ' . $e->getMessage()
    ]);
}

// Close database connection
if (isset($connection)) {
    mysqli_close($connection);
}
?> 