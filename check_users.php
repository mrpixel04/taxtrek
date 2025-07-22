<?php
// check_users.php - Debug what's in the users table

echo "<h2>🔍 Check Users Table</h2>";
echo "<hr>";

try {
    include("db_connect.php");
    
    if ($connection === null) {
        echo "❌ Database connection failed<br>";
        if (isset($connection_error)) {
            echo "Error: $connection_error<br>";
        }
        die();
    }
    
    echo "✅ Database connected successfully<br><br>";
    
    // Check if table exists
    echo "<h3>Step 1: Check if TBL_USERS table exists</h3>";
    $table_check = "SHOW TABLES LIKE 'TBL_USERS'";
    $table_result = mysqli_query($connection, $table_check);
    
    if (mysqli_num_rows($table_result) == 0) {
        echo "❌ TBL_USERS table does NOT exist!<br>";
        echo "<strong>👉 You need to run setup first!</strong><br>";
        echo "<a href='setup.php' style='background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>🔧 Go to Setup</a><br><br>";
    } else {
        echo "✅ TBL_USERS table exists<br><br>";
        
        // Show table structure
        echo "<h3>Step 2: Table structure</h3>";
        $structure = mysqli_query($connection, "DESCRIBE TBL_USERS");
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = mysqli_fetch_assoc($structure)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        // Check users in table
        echo "<h3>Step 3: Users in table</h3>";
        $users_query = "SELECT id, no_gaji, fullname, userlevel, isactive, ispaid, created_at FROM TBL_USERS";
        $users_result = mysqli_query($connection, $users_query);
        
        if (mysqli_num_rows($users_result) == 0) {
            echo "❌ No users found in table!<br>";
            echo "<strong>👉 You need to run setup to create sample users!</strong><br>";
            echo "<a href='setup.php' style='background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>🚀 Create Users</a><br><br>";
        } else {
            echo "✅ Found " . mysqli_num_rows($users_result) . " users:<br><br>";
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>ID</th><th>No Gaji</th><th>Full Name</th><th>Level</th><th>Active</th><th>Paid</th><th>Created</th></tr>";
            while ($user = mysqli_fetch_assoc($users_result)) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td><strong>" . $user['no_gaji'] . "</strong></td>";
                echo "<td>" . $user['fullname'] . "</td>";
                echo "<td>" . $user['userlevel'] . "</td>";
                echo "<td>" . $user['isactive'] . "</td>";
                echo "<td>" . $user['ispaid'] . "</td>";
                echo "<td>" . $user['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table><br>";
        }
        
        // Test specific user
        echo "<h3>Step 4: Test specific user login</h3>";
        $test_no_gaji = "ADMIN001";
        $test_password = "password123";
        
        echo "Testing login for: <strong>$test_no_gaji</strong><br>";
        
        $login_query = "SELECT * FROM TBL_USERS WHERE no_gaji = '$test_no_gaji' AND isactive = 'ACTIVE'";
        $login_result = mysqli_query($connection, $login_query);
        
        if (mysqli_num_rows($login_result) == 0) {
            echo "❌ User '$test_no_gaji' not found or not active<br>";
        } else {
            $user = mysqli_fetch_assoc($login_result);
            echo "✅ User found: " . $user['fullname'] . "<br>";
            echo "Password hash in database: <code>" . substr($user['katalaluan'], 0, 20) . "...</code><br>";
            
            // Test password verification
            if (password_verify($test_password, $user['katalaluan'])) {
                echo "✅ Password verification: <strong>SUCCESS</strong><br>";
                echo "👉 Login should work with ADMIN001 / password123<br>";
            } else {
                echo "❌ Password verification: <strong>FAILED</strong><br>";
                echo "👉 Password hash is incorrect. Need to recreate users.<br>";
            }
        }
    }
    
    mysqli_close($connection);
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "<h3>Quick Links:</h3>";
echo "<a href='setup.php' style='background: #007bff; color: white; padding: 8px 16px; margin: 5px; text-decoration: none; border-radius: 5px;'>🔧 Setup</a> ";
echo "<a href='index.php' style='background: #28a745; color: white; padding: 8px 16px; margin: 5px; text-decoration: none; border-radius: 5px;'>🔑 Login</a> ";
echo "<a href='test_new_connection.php' style='background: #6c757d; color: white; padding: 8px 16px; margin: 5px; text-decoration: none; border-radius: 5px;'>🧪 Test Connection</a>";
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background-color: #f5f5f5; 
}
h2 { color: #333; }
h3 { color: #666; margin-top: 20px; }
table { background: white; }
th { background: #f8f9fa; padding: 8px; }
td { padding: 8px; }
</style> 