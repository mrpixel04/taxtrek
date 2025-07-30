<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection (same as login.php)
include('../db_connect.php');

try {
    // Check if connection is successful (same logic as login.php)
    if ($connection === null) {
        echo json_encode([
            'success' => false,
            'message' => 'Ralat sambungan pangkalan data'
        ]);
        exit();
    }

    // Check if table exists first
    $table_check = "SHOW TABLES LIKE 'TBL_DATA'";
    $table_result = mysqli_query($connection, $table_check);
    
    if (mysqli_num_rows($table_result) == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Jadual TBL_DATA tidak dijumpai'
        ]);
        exit();
    }

    // Get all records from TBL_DATA
    $sql = "SELECT * FROM TBL_DATA ORDER BY id DESC";
    $result = mysqli_query($connection, $sql);
    
    if ($result) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'id' => $row['id'],
                'name' => $row['name'] ?? '',
                'noakaun' => $row['noakaun'] ?? '',
                'norumah_body' => $row['NORUMAH_BODY'] ?? '',
                'addr1_body' => $row['ADDR1_BODY'] ?? '',
                'addr2_body' => $row['ADDR2_BODY'] ?? '',
                'postcode_body' => $row['POSTCODE_BODY'] ?? '',
                'state_body' => $row['STATE_BODY'] ?? '',
                'bakitunggakan' => $row['BAKITUNGGAKAN'] ?? '',
                'status_buat_di_site' => $row['STATUS_BUAT_DI_SITE'] ?? '',
                'tarikh_buat_di_site' => $row['TARIKH_BUAT_DI_SITE'] ?? ''
            ];
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Data berjaya diperoleh',
            'data' => $data,
            'total' => count($data)
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Ralat mendapatkan data: ' . mysqli_error($connection)
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