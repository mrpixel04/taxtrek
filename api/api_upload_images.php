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

    // Log the request
    error_log("Image upload request received");
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));

    // Get required parameters
    if (!isset($_POST['data_id']) || !isset($_POST['noakaun_dreams'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Parameter data_id dan noakaun_dreams diperlukan'
        ]);
        exit();
    }

    $data_id = mysqli_real_escape_string($connection, $_POST['data_id']);
    $noakaun_dreams = mysqli_real_escape_string($connection, $_POST['noakaun_dreams']);
    
    error_log("Processing upload for data_id: $data_id, noakaun_dreams: $noakaun_dreams");

    // Check if files were uploaded
    if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
        echo json_encode([
            'success' => false,
            'message' => 'Tiada fail gambar dihantar'
        ]);
        exit();
    }

    // Create upload directory based on NOAKAUNDREAMS
    $base_upload_dir = '../uploads/images/';
    $upload_dir = $base_upload_dir . $noakaun_dreams . '/';
    
    if (!file_exists($base_upload_dir)) {
        mkdir($base_upload_dir, 0755, true);
        error_log("Created base upload directory: $base_upload_dir");
    }
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
        error_log("Created upload directory: $upload_dir");
    }

    $uploaded_files = [];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $max_file_size = 5 * 1024 * 1024; // 5MB

    // Process each uploaded file
    $file_count = count($_FILES['images']['name']);
    error_log("Processing $file_count files");
    
    for ($i = 0; $i < $file_count; $i++) {
        $file_name = $_FILES['images']['name'][$i];
        $file_tmp = $_FILES['images']['tmp_name'][$i];
        $file_type = $_FILES['images']['type'][$i];
        $file_size = $_FILES['images']['size'][$i];
        $file_error = $_FILES['images']['error'][$i];

        error_log("Processing file $i: $file_name, type: $file_type, size: $file_size");

        // Skip if no file uploaded
        if ($file_error === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        // Check for upload errors
        if ($file_error !== UPLOAD_ERR_OK) {
            error_log("Upload error for file $i: $file_error");
            continue;
        }

        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            error_log("Invalid file type for file $i: $file_type");
            continue;
        }

        // Validate file size
        if ($file_size > $max_file_size) {
            error_log("File too large for file $i: $file_size bytes");
            continue;
        }

        // Generate unique filename
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_filename = $data_id . '_' . time() . '_' . ($i + 1) . '.' . $file_extension;
        $file_path = $upload_dir . $unique_filename;
        $relative_path = 'uploads/images/' . $noakaun_dreams . '/' . $unique_filename;

        // Move uploaded file
        if (move_uploaded_file($file_tmp, $file_path)) {
            $uploaded_files[] = [
                'original_name' => $file_name,
                'filename' => $unique_filename,
                'path' => $relative_path,
                'size' => $file_size
            ];
            error_log("✅ File uploaded successfully: $file_path");
        } else {
            error_log("❌ Failed to move file: $file_tmp to $file_path");
        }
    }

    if (empty($uploaded_files)) {
        echo json_encode([
            'success' => false,
            'message' => 'Tiada fail gambar berjaya dimuatnaik'
        ]);
        exit();
    }

    // Save image paths to database
    $image_paths = array_column($uploaded_files, 'path');
    $image_paths_json = json_encode($image_paths);
    
    // Update the TBL_DATA record with image paths
    $update_sql = "UPDATE TBL_DATA SET 
                   image_paths = '$image_paths_json',
                   updated_at = NOW()
                   WHERE iddata = '$data_id'";
    
    error_log("Updating database with SQL: $update_sql");
    $update_result = mysqli_query($connection, $update_sql);
    
    if ($update_result) {
        error_log("✅ Database updated successfully");
        echo json_encode([
            'success' => true,
            'message' => 'Gambar berjaya dimuatnaik',
            'uploaded_files' => $uploaded_files,
            'total_files' => count($uploaded_files)
        ]);
    } else {
        error_log("❌ Database update failed: " . mysqli_error($connection));
        echo json_encode([
            'success' => false,
            'message' => 'Ralat menyimpan maklumat gambar ke pangkalan data: ' . mysqli_error($connection)
        ]);
    }

} catch (Exception $e) {
    error_log("Exception in image upload: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Ralat sistem: ' . $e->getMessage()
    ]);
}

if (isset($connection) && $connection !== null) {
    mysqli_close($connection);
}
?> 