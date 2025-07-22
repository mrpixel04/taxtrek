<?php
// fix_passwords.php - Fix password hashes for existing users

echo "<h2>🔧 Fix User Passwords</h2>";
echo "<hr>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fix_passwords'])) {
    try {
        include("db_connect.php");
        
        if ($connection === null) {
            echo "❌ Database connection failed<br>";
            die();
        }
        
        // Generate correct password hash
        $password = "password123";
        $correct_hash = password_hash($password, PASSWORD_DEFAULT);
        
        echo "<h3>🔑 Updating passwords...</h3>";
        echo "New password: <strong>$password</strong><br>";
        echo "New hash: <code>" . substr($correct_hash, 0, 30) . "...</code><br><br>";
        
        // Update ADMIN001
        $update_admin = "UPDATE TBL_USERS SET katalaluan = '$correct_hash' WHERE no_gaji = 'ADMIN001'";
        if (mysqli_query($connection, $update_admin)) {
            echo "✅ ADMIN001 password updated successfully<br>";
        } else {
            echo "❌ Failed to update ADMIN001: " . mysqli_error($connection) . "<br>";
        }
        
        // Update EMP001
        $update_emp = "UPDATE TBL_USERS SET katalaluan = '$correct_hash' WHERE no_gaji = 'EMP001'";
        if (mysqli_query($connection, $update_emp)) {
            echo "✅ EMP001 password updated successfully<br>";
        } else {
            echo "❌ Failed to update EMP001: " . mysqli_error($connection) . "<br>";
        }
        
        echo "<br><h3>🧪 Testing updated passwords...</h3>";
        
        // Test ADMIN001
        $test_query = "SELECT * FROM TBL_USERS WHERE no_gaji = 'ADMIN001'";
        $result = mysqli_query($connection, $test_query);
        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['katalaluan'])) {
                echo "✅ ADMIN001 password verification: <strong>SUCCESS</strong><br>";
            } else {
                echo "❌ ADMIN001 password verification: <strong>FAILED</strong><br>";
            }
        }
        
        // Test EMP001
        $test_query = "SELECT * FROM TBL_USERS WHERE no_gaji = 'EMP001'";
        $result = mysqli_query($connection, $test_query);
        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['katalaluan'])) {
                echo "✅ EMP001 password verification: <strong>SUCCESS</strong><br>";
            } else {
                echo "❌ EMP001 password verification: <strong>FAILED</strong><br>";
            }
        }
        
        mysqli_close($connection);
        
        echo "<br><div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<strong>🎉 Password Update Complete!</strong><br>";
        echo "You can now login with:<br>";
        echo "• <strong>ADMIN001</strong> / <strong>password123</strong><br>";
        echo "• <strong>EMP001</strong> / <strong>password123</strong><br>";
        echo "</div>";
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
} else {
    // Show current status
    try {
        include("db_connect.php");
        
        if ($connection !== null) {
            echo "<h3>📋 Current Users Status:</h3>";
            $users_query = "SELECT no_gaji, fullname, isactive FROM TBL_USERS";
            $result = mysqli_query($connection, $users_query);
            
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0; background: white;'>";
            echo "<tr><th style='padding: 8px; background: #f8f9fa;'>No Gaji</th><th style='padding: 8px; background: #f8f9fa;'>Full Name</th><th style='padding: 8px; background: #f8f9fa;'>Status</th></tr>";
            while ($user = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td style='padding: 8px;'>" . $user['no_gaji'] . "</td>";
                echo "<td style='padding: 8px;'>" . $user['fullname'] . "</td>";
                echo "<td style='padding: 8px;'>" . $user['isactive'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            mysqli_close($connection);
        }
    } catch (Exception $e) {
        echo "❌ Error checking users: " . $e->getMessage() . "<br>";
    }
    
    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<strong>⚠️ Password Issue Detected!</strong><br>";
    echo "The password hashes in the database are incorrect. Click the button below to fix them.<br>";
    echo "This will set the password to <strong>password123</strong> for both users.";
    echo "</div>";
    
    echo "<form method='POST' action=''>";
    echo "<button type='submit' name='fix_passwords' style='background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>🔧 Fix Passwords Now</button>";
    echo "</form>";
}

echo "<hr>";
echo "<h3>Quick Links:</h3>";
echo "<a href='check_users.php' style='background: #6c757d; color: white; padding: 8px 16px; margin: 5px; text-decoration: none; border-radius: 5px;'>🔍 Check Users</a> ";
echo "<a href='index.php' style='background: #007bff; color: white; padding: 8px 16px; margin: 5px; text-decoration: none; border-radius: 5px;'>🔑 Try Login</a>";
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    margin: 20px; 
    background-color: #f5f5f5; 
}
h2 { color: #333; }
h3 { color: #666; margin-top: 20px; }
button:hover { transform: scale(1.05); }
</style> 