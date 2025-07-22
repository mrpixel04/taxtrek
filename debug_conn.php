<?php
// debug_conn.php - Debug the connection file

echo "<h2>🐛 Debug Connection File</h2>";
echo "<hr>";

echo "<h3>Step 1: Show file contents</h3>";
if (file_exists("classes/conn.php")) {
    echo "✅ File exists<br>";
    echo "<strong>File contents:</strong><br>";
    echo "<pre>" . htmlspecialchars(file_get_contents("classes/conn.php")) . "</pre>";
} else {
    echo "❌ File not found<br>";
    die();
}

echo "<h3>Step 2: Test variables before include</h3>";
echo "Before include - connection variable: " . (isset($connection) ? "SET" : "NOT SET") . "<br>";
echo "Before include - host variable: " . (isset($host) ? "SET" : "NOT SET") . "<br>";

echo "<h3>Step 3: Include file and show all variables</h3>";
include("classes/conn.php");

echo "After include - connection variable: " . (isset($connection) ? "SET" : "NOT SET") . "<br>";
echo "After include - host variable: " . (isset($host) ? "SET (" . $host . ")" : "NOT SET") . "<br>";
echo "After include - username variable: " . (isset($username) ? "SET (" . $username . ")" : "NOT SET") . "<br>";
echo "After include - database variable: " . (isset($database) ? "SET (" . $database . ")" : "NOT SET") . "<br>";
echo "After include - db_connection_error variable: " . (isset($db_connection_error) ? "SET (" . $db_connection_error . ")" : "NOT SET") . "<br>";

echo "<h3>Step 4: Show all defined variables</h3>";
$all_vars = get_defined_vars();
echo "<strong>All variables after include:</strong><br>";
foreach ($all_vars as $var_name => $var_value) {
    if (!in_array($var_name, ['_GET', '_POST', '_COOKIE', '_FILES', '_SERVER', '_ENV', 'GLOBALS', '_SESSION'])) {
        echo "$var_name = " . (is_object($var_value) ? get_class($var_value) : var_export($var_value, true)) . "<br>";
    }
}

echo "<h3>Step 5: Try manual connection</h3>";
echo "Attempting manual MySQL connection...<br>";
try {
    $manual_conn = new mysqli("localhost", "root", "", "dbtaxtrek");
    if ($manual_conn->connect_error) {
        echo "❌ Manual connection failed: " . $manual_conn->connect_error . "<br>";
    } else {
        echo "✅ Manual connection successful!<br>";
        mysqli_close($manual_conn);
    }
} catch (Exception $e) {
    echo "❌ Exception during manual connection: " . $e->getMessage() . "<br>";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
h2 { color: #333; }
h3 { color: #666; margin-top: 20px; }
pre { background: #fff; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
</style> 