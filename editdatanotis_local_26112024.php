<?php

//include("classes/conn.php");

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




$idrecord = (isset($_GET['data']))?$_GET['data']:'';

echo "<br/><br/><br/><br/><br/><br/><br/>huhuhu ".$idrecord;

   // Fetch the record from TBL_DATA based on the provided iddata
    $sql = "SELECT * FROM TBL_DATA WHERE iddata = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt === false) {
        die("Error: " . $connection->error);
    }

    $stmt->bind_param("i", $idrecord); // Assuming 'iddata' is an integer

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $namapemilik = $row['NAMAPEMILIK'];
            $norumah = $row['NORUMAH'];
            $namapremis = $row['NAMAPREMIS'];
            $alamat1 = $row['ALAMAT1'];
            $alamat2 = $row['ALAMAT2'];
            $poskod = $row['POSKOD'];
            $noakaun = $row['NOAKAUN'];
            
            $tarikhbuat = $row['TARIKH_BUAT']; 
            
            $dateTime = new DateTime($tarikhbuat);
            
            $monthNames = [
                1 => 'January', 2 => 'February', 3 => 'March',
                4 => 'April', 5 => 'May', 6 => 'June',
                7 => 'July', 8 => 'August', 9 => 'September',
                10 => 'October', 11 => 'November', 12 => 'Dicember'
            ];
            
            // Format the date as "d F Y" where "F" is the full month name
            $tarikhbuatFinal = $dateTime->format('d ') . $monthNames[$dateTime->format('n')] . $dateTime->format(' Y');
        
        
             
            $tarikhSemVal = new DateTime($tarikhbuat);
            $tarikhSemVal->modify('-1 day');

            $newDate = $tarikhSemVal->format('Y-m-d'); 

            $tarikhSemakDCS = $tarikhSemVal->format('d')." ".$monthNames[$tarikhSemVal->format('n')]." ".$tarikhSemVal->format('Y'); 
            
            
          

             // Check if the "BAKITUNGGAKAN" field contains a decimal point
            if (strpos($row['BAKITUNGGAKAN'], '.') !== false) {
                // If it contains a decimal point, format it with two decimal places
                $formattedBakitunggakan = number_format((float)$row['BAKITUNGGAKAN'], 2, '.', '');
            } else {
                // If it doesn't contain a decimal point, append ".00" to it
                $formattedBakitunggakan = $row['BAKITUNGGAKAN'] . ".00";
            }

           


        } else {
            echo "Record not found";
        }
    } else {
        echo "Error fetching record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();


// Close the database connection
$connection->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['hididrec'];

   $newStatus = $_POST['status'];
	$newDate = $_POST['date'];
	$addrtoptxt1 = $_POST['addrtoptxt1'];
	$addrtoptxt2 = $_POST['addrtoptxt2'];
	$addrtoptxt3 = $_POST['addrtoptxt3'];
	$addrtoptxt4 = $_POST['addrtoptxt4'];
	$addrtoptxt5 = $_POST['addrtoptxt5'];
	$addrbodytxt1 = $_POST['addrbodytxt1'];
	$addrbodytxt2 = $_POST['addrbodytxt2'];
	$addrbodytxt3 = $_POST['addrbodytxt3'];
	$addrbodytxt4 = $_POST['addrbodytxt4'];
	$addrbodytxt5 = $_POST['addrbodytxt5'];

 
    $sql = "UPDATE TBL_DATA SET STATUS_DATA = '$newStatus', TARIKH_BUAT = '$newDate', ADDRTOP1 = '$addrtoptxt1', ADDRTOP2 = '$addrtoptxt2', ADDRTOP3 = '$addrtoptxt3', ADDRTOP4 = '$addrtoptxt4', ADDRTOP5 = '$addrtoptxt5', ADDRBODY1 = '$addrbodytxt1', ADDRBODY2 = '$addrbodytxt2', ADDRBODY3 = '$addrbodytxt3', ADDRBODY4 = '$addrbodytxt4', ADDRBODY5 = '$addrbodytxt5' WHERE iddata = $id";

   	// Execute the SQL query
	if ($connection->query($sql) === TRUE) {
	    // Update successful
	    echo '<script>window.location.href = "http://localhost/flutterapps/api/taskforce/main.php?page=data.php";</script>';
	    exit();
	} else {
	    // Update failed
	    echo "Error updating record: " . $connection->error;
	}

    $connection->close();
}




?>

<div class="container">
	<form class="form" id="editForm" method="POST">
		<table class="table">
		
		  <tbody>
			<tr>
				<td colspan="2" align="text-left" class="bg-danger text-light">
					UPPER LETTER ADDRESS
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $namapemilik; ?>" oninput="this.value = this.value.toUpperCase()" name="addrtoptxt1" id="addrtoptxt1">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $norumah." ".$namapremis; ?>" oninput="this.value = this.value.toUpperCase()" name="addrtoptxt2" id="addrtoptxt2">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $alamat1; ?>" oninput="this.value = this.value.toUpperCase()" name="addrtoptxt3" id="addrtoptxt3">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $alamat2; ?>" oninput="this.value = this.value.toUpperCase()" name="addrtoptxt4" id="addrtoptxt4">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $poskod; ?>" oninput="this.value = this.value.toUpperCase()" name="addrtoptxt5" id="addrtoptxt5">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left" class="bg-danger text-light">
					BODY LETTER ADDRESS
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $namapemilik; ?>" oninput="this.value = this.value.toUpperCase()" name="addrbodytxt1" id="addrbodytxt1">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $norumah." ".$namapremis; ?>" oninput="this.value = this.value.toUpperCase()" name="addrbodytxt2" id="addrbodytxt2">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $alamat1; ?>" oninput="this.value = this.value.toUpperCase()" name="addrbodytxt3" id="addrbodytxt3">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $alamat2; ?>" oninput="this.value = this.value.toUpperCase()" name="addrbodytxt4" id="addrbodytxt4">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $poskod; ?>" oninput="this.value = this.value.toUpperCase()" name="addrbodytxt5" id="addrbodytxt5">
				</td>
			</tr>
			<tr>
                    <td>Status Data</td>
                    <td>
                        <select class="form-select form-select-sm" name="status" id="status">
                            <option value="BUAT" <?php echo ($row['STATUS_DATA'] == 'BUAT') ? 'selected' : ''; ?>>BUAT</option>
                            <option value="BELUM BUAT" <?php echo ($row['STATUS_DATA'] == 'BELUM BUAT') ? 'selected' : ''; ?>>BELUM BUAT</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Tarikh Buat</td>
                    <td>
                        <input type="date" class="form-control form-control-sm" name="tarikhbuat" id="tarikhbuat" value="<?php echo $row['TARIKH_BUAT']; ?>">
                    </td>
                </tr>

		</tbody>
		</table>
		<br/>

		<input id="hididrec" name="hididrec" value="<?php echo $idrecord; ?>" type="hidden" />
		
		<div class="d-grid gap-2">
		  <button type="submit" class="btn btn-primary" id="updateButton">Update</button>
		</div>
		<br/>
		<br/>
	</form>
</div>

