<?php
// test_connection.php - Simple database connection test

echo "<h2>🔍 TaxTrek Database Connection Test</h2>";
echo "<hr>";

// Step 1: Check if conn.php file exists
echo "<h3>Step 1: Checking if conn.php file exists...</h3>";
if (file_exists("classes/conn.php")) {
    echo "✅ File classes/conn.php EXISTS<br>";
} else {
    echo "❌ File classes/conn.php NOT FOUND<br>";
    die("Connection test stopped.");
}

// Step 2: Try to include the file
echo "<h3>Step 2: Including conn.php file...</h3>";
try {
    include("classes/conn.php");
    echo "✅ File included successfully<br>";
} catch (Exception $e) {
    echo "❌ Error including file: " . $e->getMessage() . "<br>";
    die("Connection test stopped.");
}

// Step 3: Check if connection variable exists
echo "<h3>Step 3: Checking connection variable...</h3>";
if (isset($connection)) {
    echo "✅ Connection variable exists<br>";
} else {
    echo "❌ Connection variable NOT SET<br>";
    if (isset($db_connection_error)) {
        echo "❌ Connection Error: " . $db_connection_error . "<br>";
    }
    die("Connection test stopped.");
}

// Step 4: Test database connection
echo "<h3>Step 4: Testing database connection...</h3>";
if ($connection === null) {
    echo "❌ Connection is null<br>";
    if (isset($db_connection_error)) {
        echo "Error details: " . $db_connection_error . "<br>";
    }
    die("Connection test stopped.");
} else {
    echo "✅ Database connected successfully!<br>";
    echo "Host: " . $connection->host_info . "<br>";
}

// Step 5: Test connection to MySQL (without specific database)
echo "<h3>Step 5: Testing MySQL connection (without database)...</h3>";
$mysql_conn = new mysqli("localhost", "root", "");
if ($mysql_conn->connect_error) {
    echo "❌ MySQL connection failed: " . $mysql_conn->connect_error . "<br>";
} else {
    echo "✅ MySQL server connected successfully!<br>";
    
    // Step 6: Check if target database exists
    echo "<h3>Step 6: Checking if database 'dbtaxtrek' exists...</h3>";
    $db_check = "SHOW DATABASES LIKE 'dbtaxtrek'";
    $db_result = mysqli_query($mysql_conn, $db_check);
    
    if ($db_result && mysqli_num_rows($db_result) > 0) {
        echo "✅ Database 'dbtaxtrek' EXISTS<br>";
    } else {
        echo "❌ Database 'dbtaxtrek' NOT FOUND<br>";
        echo "<strong>🔧 Creating database...</strong><br>";
        
        $create_db = "CREATE DATABASE dbtaxtrek";
        if (mysqli_query($mysql_conn, $create_db)) {
            echo "✅ Database 'dbtaxtrek' created successfully!<br>";
        } else {
            echo "❌ Error creating database: " . mysqli_error($mysql_conn) . "<br>";
        }
    }
    
    mysqli_close($mysql_conn);
}

// Step 7: Test connection to our target database again
echo "<h3>Step 7: Testing connection to dbtaxtrek database...</h3>";
$final_conn = new mysqli("localhost", "root", "", "dbtaxtrek");
if ($final_conn->connect_error) {
    echo "❌ Connection to dbtaxtrek failed: " . $final_conn->connect_error . "<br>";
} else {
    echo "✅ Successfully connected to dbtaxtrek database!<br>";
    
    // Check tables
    $table_result = mysqli_query($final_conn, "SHOW TABLES");
    if (mysqli_num_rows($table_result) > 0) {
        echo "Available tables:<br>";
        while ($table = mysqli_fetch_array($table_result)) {
            echo "- " . $table[0] . "<br>";
        }
    } else {
        echo "⚠️ No tables found in database<br>";
    }
    
    mysqli_close($final_conn);
}

echo "<hr>";
echo "<h3>✅ Connection test completed!</h3>";
echo "<p><a href='index_simple.php'>🧪 Test simple index</a></p>";
echo "<p><a href='setup.php'>🔧 Run setup page</a></p>";
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background-color: #f5f5f5; 
}
h2 { color: #333; }
h3 { color: #666; margin-top: 20px; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style> 