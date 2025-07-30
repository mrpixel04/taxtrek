<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
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

    // Check if table exists first
    $table_check = "SHOW TABLES LIKE 'TBL_DATA'";
    $table_result = mysqli_query($connection, $table_check);
    
    if (mysqli_num_rows($table_result) == 0) {
        error_log("Table TBL_DATA not found");
        echo json_encode([
            'success' => false,
            'message' => 'Jadual TBL_DATA tidak dijumpai'
        ]);
        exit();
    }

    // Check table structure first
    $describe_sql = "DESCRIBE TBL_DATA";
    $describe_result = mysqli_query($connection, $describe_sql);
    
    if ($describe_result) {
        $columns = [];
        while ($column = mysqli_fetch_assoc($describe_result)) {
            $columns[] = $column['Field'];
        }
        error_log("Table columns: " . implode(", ", $columns));
        
        // Check if we have an ID column (look for iddata)
        $has_id = false;
        $id_column = '';
        foreach ($columns as $col) {
            if (strtolower($col) == 'iddata') {
                $has_id = true;
                $id_column = $col;
                break;
            }
        }
        
        error_log("ID column found: " . ($has_id ? $id_column : "No ID column"));
        
        // Get all records from TBL_DATA
        if ($has_id) {
            $sql = "SELECT * FROM TBL_DATA ORDER BY `$id_column` DESC";
        } else {
            $sql = "SELECT * FROM TBL_DATA";
        }
    } else {
        $sql = "SELECT * FROM TBL_DATA";
    }
    
    error_log("SQL Query: " . $sql);
    $result = mysqli_query($connection, $sql);
    
    if ($result) {
        $data = [];
        $rowCount = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $rowCount++;
            // Log first row column names for debugging
            if ($rowCount == 1) {
                error_log("Available columns: " . implode(", ", array_keys($row)));
            }
            
            $status_value = isset($row['STATUS_BUAT_DI_SITE']) ? $row['STATUS_BUAT_DI_SITE'] : '';
            if ($rowCount <= 3) { // Log first 3 records to see status values
                error_log("Row $rowCount STATUS_BUAT_DI_SITE value: '" . $status_value . "'");
            }
            
            $data[] = [
                'id' => isset($row['iddata']) ? $row['iddata'] : $rowCount,
                'name' => isset($row['NAMAPEMILIK']) ? $row['NAMAPEMILIK'] : '',
                'noakaun' => isset($row['NOAKAUNDREAMS']) ? $row['NOAKAUNDREAMS'] : '',
                'norumah_body' => isset($row['NORUMAH_BODY']) ? $row['NORUMAH_BODY'] : '',
                'addr1_body' => isset($row['ADDR1_BODY']) ? $row['ADDR1_BODY'] : '',
                'addr2_body' => isset($row['ADDR2_BODY']) ? $row['ADDR2_BODY'] : '',
                'postcode_body' => isset($row['POSTCODE_BODY']) ? $row['POSTCODE_BODY'] : '',
                'state_body' => isset($row['STATE_BODY']) ? $row['STATE_BODY'] : '',
                'bakitunggakan' => isset($row['BAKITUNGGAKAN']) ? $row['BAKITUNGGAKAN'] : '',
                'status_buat_di_site' => $status_value,
                'tarikh_buat_di_site' => isset($row['TARIKH_BUAT_DI_SITE']) ? $row['TARIKH_BUAT_DI_SITE'] : ''
            ];
        }
        
        error_log("Total rows found: " . $rowCount);
        
        echo json_encode([
            'success' => true,
            'message' => 'Data berjaya diambil',
            'data' => $data
        ]);
    } else {
        $mysql_error = mysqli_error($connection);
        error_log("MySQL Error: " . $mysql_error);
        echo json_encode([
            'success' => false,
            'message' => 'Ralat mengambil data: ' . $mysql_error,
            'sql_query' => $sql
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