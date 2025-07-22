<?php
// test_new_connection.php - Test with new connection file

echo "<h2>🔍 Test New Connection File</h2>";
echo "<hr>";

// Step 1: Check if new file exists
echo "<h3>Step 1: Checking if db_connect.php file exists...</h3>";
if (file_exists("db_connect.php")) {
    echo "✅ File db_connect.php EXISTS<br>";
} else {
    echo "❌ File db_connect.php NOT FOUND<br>";
    die("Test stopped.");
}

// Step 2: Show file contents
echo "<h3>Step 2: Show file contents</h3>";
echo "<pre>" . htmlspecialchars(file_get_contents("db_connect.php")) . "</pre>";

// Step 3: Include the file
echo "<h3>Step 3: Including db_connect.php file...</h3>";
try {
    include("db_connect.php");
    echo "✅ File included successfully<br>";
} catch (Exception $e) {
    echo "❌ Error including file: " . $e->getMessage() . "<br>";
    die("Test stopped.");
}

// Step 4: Check variables
echo "<h3>Step 4: Checking variables...</h3>";
echo "db_host: " . (isset($db_host) ? "✅ SET ($db_host)" : "❌ NOT SET") . "<br>";
echo "db_user: " . (isset($db_user) ? "✅ SET ($db_user)" : "❌ NOT SET") . "<br>";
echo "db_name: " . (isset($db_name) ? "✅ SET ($db_name)" : "❌ NOT SET") . "<br>";
echo "connection: " . (isset($connection) ? "✅ SET" : "❌ NOT SET") . "<br>";
echo "connection_error: " . (isset($connection_error) ? "SET ($connection_error)" : "NOT SET") . "<br>";

// Step 5: Test connection
echo "<h3>Step 5: Testing connection...</h3>";
if (isset($connection) && $connection !== null) {
    echo "✅ Connection object exists and is not null<br>";
    echo "Connection type: " . get_class($connection) . "<br>";
    
    // Try a simple query
    $result = mysqli_query($connection, "SELECT 1 as test");
    if ($result) {
        echo "✅ Test query successful!<br>";
        $row = mysqli_fetch_assoc($result);
        echo "Test result: " . $row['test'] . "<br>";
    } else {
        echo "❌ Test query failed: " . mysqli_error($connection) . "<br>";
    }
} else {
    echo "❌ Connection is null or not set<br>";
    if (isset($connection_error)) {
        echo "Error: $connection_error<br>";
    }
}

echo "<hr>";
echo "<h3>✅ Test completed!</h3>";
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background-color: #f5f5f5; 
}
h2 { color: #333; }
h3 { color: #666; margin-top: 20px; }
pre { 
    background: #fff; 
    padding: 10px; 
    border: 1px solid #ddd; 
    overflow-x: auto; 
    font-size: 12px;
}
</style> 