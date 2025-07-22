<?php
// index_simple.php - Simple test version

echo "<h2>🧪 Simple Index Test</h2>";
echo "<hr>";

// Test 1: Basic PHP
echo "<h3>Test 1: Basic PHP</h3>";
echo "✅ PHP is working<br>";
echo "PHP Version: " . phpversion() . "<br>";

// Test 2: Try to include connection
echo "<h3>Test 2: Including Database Connection</h3>";
try {
    if (file_exists("classes/conn.php")) {
        include("classes/conn.php");
        echo "✅ Connection file included<br>";
        
        if (isset($connection)) {
            echo "✅ Connection variable exists<br>";
            echo "Connection status: " . ($connection->connect_error ? "❌ Failed" : "✅ Success") . "<br>";
        } else {
            echo "❌ Connection variable not set<br>";
        }
    } else {
        echo "❌ Connection file not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Test 3: Session start
echo "<h3>Test 3: Session Test</h3>";
try {
    session_start();
    echo "✅ Session started successfully<br>";
    echo "Session ID: " . session_id() . "<br>";
} catch (Exception $e) {
    echo "❌ Session error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<p><a href='index.php'>🔙 Back to main index.php</a></p>";
echo "<p><a href='test_connection.php'>🔍 Run full connection test</a></p>";
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background-color: #f9f9f9; 
}
h2 { color: #333; }
h3 { color: #666; margin-top: 15px; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style> 