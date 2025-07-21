<?php

    include('../classes/conn.php');

/*
    $iddata = $_POST['iddata'];
    $bil = $_POST['bil'];
*/
    

    $iddata = "1"; // Assuming it's an integer
    $bil = "114378"; // Assuming it's a string
    
    
    // Include parameters directly in the SQL query
    $sql = "SELECT imgs_list_fn FROM TBL_DATA_IMAGES WHERE iddata = ".(int)$iddata." AND bil = '".$bil."'";

    // Execute the query
    $result = $connection->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            $imageFilenames = explode("||", $row['imgs_list_fn']);
            $imageUrls = [];

            foreach ($imageFilenames as $filename) {
                // Modify the path as needed to serve the images from your server
                $imageUrls[] = 'https://eastbizz.com/taskforce/imagestaskforce/' . $bil . '/' . $filename;
            }

            echo json_encode($imageUrls);
        } else {
            echo '[]'; // No images found
        }
    } else {
        echo '[]'; // Failed to execute the query
    }

    $connection->close();


?>