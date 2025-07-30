<?php
// Test file for validateuser.php API
// This file demonstrates different ways to test the API

echo "<h2>TaxTrek Employee Validation API - Test Examples</h2>";

echo "<h3>1. Test with CURL (JSON POST)</h3>";
echo "<pre>";
echo "curl -X POST http://your-domain.com/api/validateuser.php \\\n";
echo "  -H \"Content-Type: application/json\" \\\n";
echo "  -d '{\"employee_id\": \"9327\"}'";
echo "</pre>";

echo "<h3>2. Test with CURL (Form Data)</h3>";
echo "<pre>";
echo "curl -X POST http://your-domain.com/api/validateuser.php \\\n";
echo "  -d \"employee_id=9327\"";
echo "</pre>";

echo "<h3>3. Expected Success Response</h3>";
echo "<pre>";
echo json_encode([
    "status" => "success",
    "message" => "Pengguna berdaftar",
    "timestamp" => "2024-01-15 10:30:00",
    "data" => [
        "employee_exists" => true,
        "employee_id" => "9327",
        "employee_name" => "Ahmad bin Ali",
        "department" => "General",
        "position" => "Admin",
        "employment_status" => "ACTIVE",
        "payment_status" => "PAID",
        "phone_number" => "0123456789",
        "email" => "ahmad@company.com",
        "last_login" => "2024-01-15 09:00:00",
        "member_since" => "2023-01-01 08:00:00"
    ]
], JSON_PRETTY_PRINT);
echo "</pre>";

echo "<h3>4. Expected Error Response (User Not Found)</h3>";
echo "<pre>";
echo json_encode([
    "status" => "error",
    "message" => "Pengguna tidak berdaftar",
    "timestamp" => "2024-01-15 10:30:00",
    "data" => [
        "employee_exists" => false,
        "employee_id" => "99999"
    ]
], JSON_PRETTY_PRINT);
echo "</pre>";

echo "<h3>5. JavaScript/AJAX Example</h3>";
echo "<pre>";
echo "// Using fetch API
fetch('http://your-domain.com/api/validateuser.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        employee_id: '9327'
    })
})
.then(response => response.json())
.then(data => {
    console.log('Success:', data);
    if (data.status === 'success' && data.data.employee_exists) {
        alert('Pengguna berdaftar: ' + data.data.employee_name);
    } else {
        alert('Pengguna tidak berdaftar');
    }
})
.catch((error) => {
    console.error('Error:', error);
});";
echo "</pre>";

echo "<h3>6. Test Form (Submit to test API)</h3>";
?>

<form method="POST" action="validateuser.php" style="border: 1px solid #ccc; padding: 20px; margin: 20px 0;">
    <label for="employee_id">Employee ID (No Gaji):</label><br>
    <input type="text" id="employee_id" name="employee_id" placeholder="Enter employee ID (e.g., 9327)" required style="padding: 5px; margin: 10px 0; width: 200px;"><br>
    <input type="submit" value="Test Validation" style="padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer;">
</form>

<?php
echo "<h3>7. PHP Example Usage</h3>";
echo "<pre>";
echo "<?php
// Example PHP code to call the API
\$employee_id = '9327';
\$url = 'http://your-domain.com/api/validateuser.php';

\$data = json_encode(['employee_id' => \$employee_id]);

\$ch = curl_init();
curl_setopt(\$ch, CURLOPT_URL, \$url);
curl_setopt(\$ch, CURLOPT_POST, true);
curl_setopt(\$ch, CURLOPT_POSTFIELDS, \$data);
curl_setopt(\$ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);

\$response = curl_exec(\$ch);
curl_close(\$ch);

\$result = json_decode(\$response, true);

if (\$result['status'] === 'success' && \$result['data']['employee_exists']) {
    echo 'Employee found: ' . \$result['data']['employee_name'];
} else {
    echo 'Employee not found or inactive';
}
?>";
echo "</pre>";

echo "<h3>8. N8N Webhook Test</h3>";
echo "<p>For N8N integration, the API will receive POST data like:</p>";
echo "<pre>";
echo json_encode([
    "employee_id" => "9327",
    "source" => "telegram_bot",
    "request_time" => "2024-01-15T10:30:00Z",
    "telegram_user" => [
        "user_id" => "123456789",
        "username" => "john_doe",
        "first_name" => "John",
        "last_name" => "Doe"
    ]
], JSON_PRETTY_PRINT);
echo "</pre>";

echo "<h3>9. Database Requirements</h3>";
echo "<p>Make sure your database connection file (../db_connect.php) is properly configured and TBL_USERS table exists with the following structure:</p>";
echo "<pre>";
echo "CREATE TABLE TBL_USERS (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    no_gaji VARCHAR(50) NOT NULL,
    katalaluan VARCHAR(255),
    fullname VARCHAR(100),
    userlevel ENUM('CUSTOMER', 'ADMIN'),
    last_login_datetime DATETIME,
    isactive ENUM('ACTIVE', 'NOT ACTIVE'),
    ispaid ENUM('PAID', 'NOT PAID'),
    hpno VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);";
echo "</pre>";
?>