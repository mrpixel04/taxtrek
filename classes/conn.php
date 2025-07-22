<?php 
$host = "localhost";
$username = "root";
$password = "";
$database = "dbtaxtrek";

$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    $db_connection_error = "Connection failed: " . $connection->connect_error;
    $connection = null;
} else {
    $db_connection_error = null;
}
?>