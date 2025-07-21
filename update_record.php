<?php


include("classes/conn.php");



    $id = $_POST['id'];
    $newStatus = $_POST['status'];
    $newDate = $_POST['date'];
    $namaPemilik = $_POST['namaPemilik'];
    $noRumah = $_POST['noRumah'];
    $namaPremis = $_POST['namaPremis'];
    $alamat1 = $_POST['alamat1'];
    $alamat2 = $_POST['alamat2'];
    $alamat3 = $_POST['alamat3'];
    $poskod = $_POST['poskod'];
    $bakitTunggakan = $_POST['bakitTunggakan'];


    // Prepare and execute an SQL update statement
    //$sql = "UPDATE TBL_DATA SET STATUS_DATA = ?, TARIKH_BUAT = ? WHERE iddata = ?";
    $sql = "UPDATE TBL_DATA SET STATUS_DATA = ?, TARIKH_BUAT = ?, NAMAPEMILIK = ?, NORUMAH = ?, NAMAPREMIS = ? , ALAMAT1 = ?, ALAMAT2 = ?, ALAMAT3 = ? , POSKOD = ?, BAKITUNGGAKAN = ? WHERE iddata = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt === false) {
        die("Error: " . $connection->error);
    }

   // $stmt->bind_param("ssi", $newStatus, $newDate, $id);
    $stmt->bind_param("ssssssssssi", $newStatus, $newDate, $namaPemilik, $noRumah, $namaPremis ,$alamat1, $alamat2, $alamat3 ,$poskod, $bakitTunggakan, $id);

    if ($stmt->execute()) {
        // Update successful
        echo "Record updated successfully";
    } else {
        // Update failed
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();


// Close the database connection
$connection->close();

?>
