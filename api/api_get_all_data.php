<?php 

error_reporting(E_ALL); // Report all errors and warnings
ini_set('display_errors', 1);


$host = "localhost";
$username = "root";
$password = "";
$database = "dbtaxtrek";

// Create a database connection
$connection = new mysqli($host, $username, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}





$sql = "SELECT * FROM TBL_DATA WHERE STATUS_DATA='BELUM BUAT'";

$result = $connection->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$connection->close();



?>