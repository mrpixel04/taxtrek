<?php


include('../classes/conn.php');



$iddata = $_POST['iddata']; 
$nofile = $_POST['nofile']; 
$bil = $_POST['bil']; 


$targetDirectory = "../imagestaskforce/${bil}/"; // Create a dynamic folder based on 'iddata'

if (!file_exists($targetDirectory)) {
    mkdir($targetDirectory, 0777, true); // Create the folder if it doesn't exist
}

$uploadedFilenames = [];


foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
    $filename = basename($_FILES['images']['name'][$key]);
    $targetFile = $targetDirectory . $filename;

    if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $targetFile)) {
        $uploadedFilenames[] = $filename;
    }
}
    


    // Insert the data into the 'TBL_DATA_IMAGES' table
    $imgs_list_fn = implode("||", $uploadedFilenames);
    $sql = "INSERT INTO TBL_DATA_IMAGES (iddata, nofile, bil,imgs_list_fn) VALUES (?,?,?,?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("isss", $iddata,$nofile,$bil,$imgs_list_fn);

    if ($stmt->execute()) {
        echo 'success'; // Return success to Flutter
    } else {
        echo 'fail'; // Return failure to Flutter
    }

    $stmt->close();


    $connection->close();
?>
