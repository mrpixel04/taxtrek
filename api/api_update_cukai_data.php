<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection (same as login.php)
include('../db_connect.php');

try {
    // Check if connection is successful
    if ($connection === null) {
        echo json_encode([
            'success' => false,
            'message' => 'Ralat sambungan pangkalan data'
        ]);
        exit();
    }

    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id']) || !isset($input['status_buat_di_site']) || !isset($input['tarikh_buat_di_site'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Parameter yang diperlukan tidak lengkap'
        ]);
        exit();
    }

    $id = mysqli_real_escape_string($connection, $input['id']);
    $status_buat_di_site = mysqli_real_escape_string($connection, $input['status_buat_di_site']);
    $tarikh_buat_di_site = mysqli_real_escape_string($connection, $input['tarikh_buat_di_site']);

    // Log input data for debugging
    error_log("Update data - ID: $id, Status: $status_buat_di_site, Date: $tarikh_buat_di_site");

    // Check table structure to find the correct ID column
    $describe_sql = "DESCRIBE TBL_DATA";
    $describe_result = mysqli_query($connection, $describe_sql);
    $id_column = 'iddata'; // default based on table structure
    
    if ($describe_result) {
        while ($column = mysqli_fetch_assoc($describe_result)) {
            if (strtolower($column['Field']) == 'iddata') {
                $id_column = $column['Field'];
                break;
            }
        }
        error_log("Using ID column: " . $id_column);
    }

    // Update the record using the correct ID column
    $sql = "UPDATE TBL_DATA SET 
            STATUS_BUAT_DI_SITE = '$status_buat_di_site', 
            TARIKH_BUAT_DI_SITE = '$tarikh_buat_di_site'
            WHERE `$id_column` = '$id'";
    
    error_log("Update SQL: " . $sql);
    $result = mysqli_query($connection, $sql);
    
    if ($result) {
        $affected_rows = mysqli_affected_rows($connection);
        error_log("Affected rows: " . $affected_rows);
        
        if ($affected_rows > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Data berjaya dikemaskini'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Tiada perubahan dibuat atau rekod tidak dijumpai (ID: ' . $id . ')'
            ]);
        }
    } else {
        $mysql_error = mysqli_error($connection);
        error_log("MySQL Update Error: " . $mysql_error);
        echo json_encode([
            'success' => false,
            'message' => 'Ralat mengkemaskini data: ' . $mysql_error
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