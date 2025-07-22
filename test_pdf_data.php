<?php
include("db_connect.php");

echo "<h2>PDF Generation Test</h2>";

// Check if TBL_DATA table has any records
$sql = "SELECT iddata, NAMAPEMILIK, ALAMAT1, BAKITUNGGAKAN FROM TBL_DATA LIMIT 5";
$result = mysqli_query($connection, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<h3>📄 Available Records for PDF Generation:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Nama Pemilik</th><th>Alamat</th><th>Tunggakan</th><th>Action</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['iddata'] . "</td>";
        echo "<td>" . htmlspecialchars($row['NAMAPEMILIK']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ALAMAT1']) . "</td>";
        echo "<td>RM " . number_format($row['BAKITUNGGAKAN'], 2) . "</td>";
        echo "<td><a href='cetaknotisbm.php?data=" . $row['iddata'] . "' target='_blank'>🔗 Generate PDF</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><p><strong>✅ Click any 'Generate PDF' link above to test PDF generation</strong></p>";
    
} else {
    echo "<p>❌ No data found in TBL_DATA table</p>";
    echo "<p>You need to add some sample data to test PDF generation.</p>";
    
    // Provide a test link anyway
    echo "<br><p><a href='cetaknotisbm.php?data=999' target='_blank'>🔗 Test PDF with invalid ID (will show error message)</a></p>";
}

mysqli_close($connection);
?>

<style>
table { border-collapse: collapse; margin: 10px 0; }
th { background-color: #f0f0f0; padding: 8px; }
td { padding: 8px; }
a { color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
</style> 