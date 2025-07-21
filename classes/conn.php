<?php 



$host = "localhost"; // Database host (usually "localhost" or your server's IP address)
$username = "root"; // Database username
$password = ""; // Database password
$database = "dbtaxtrek"; // Database name


// Create a database connection


$connection = new mysqli($host, $username, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}




?>