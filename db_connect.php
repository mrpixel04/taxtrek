<?php
// db_connect.php - Fresh database connection file

$db_host = "localhost";

$db_user = "taskforce_user";
$db_pass = "Fbi220319";
$db_name = "dbtaxtrek";
/*
$db_user = "root";
$db_pass = "";
$db_name = "dbtaxtrek";
*/

$connection = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($connection->connect_error) {
    $connection_error = "Connection failed: " . $connection->connect_error;
    $connection = null;
} else {
    $connection_error = null;
}
?> 