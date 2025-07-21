<?php 

error_reporting(E_ALL); // Report all errors and warnings
ini_set('display_errors', 1);


$host = "localhost";
$username = "root";
$password = "";
$database = "dbtaxtrek";

// Create a database connection
$connection = new mysqli($host, $username, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


$idrecord = isset($_GET['data']) ? $_GET['data'] : '';

//echo "<br/><br/><br/><br/><br/><br/><br/>huhuhu " . $idrecord;
$sql = "SELECT * FROM TBL_DATA WHERE iddata = $idrecord"; // Avoided parameterized query
$result = $connection->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $namapemilik = $row['NAMAPEMILIK'];
    $norumah = $row['NORUMAH'];
    //$namapremis = $row['NAMAPREMIS'];
    $alamat1 = $row['ALAMAT1'];
    $alamat2 = $row['ALAMAT2'];
    $poskod = $row['POSKOD'];
    //$noakaun = $row['NOAKAUN'];
    $tarikhbuat = $row['TARIKH_BUAT'];

    $dateTime = new DateTime($tarikhbuat);

    $monthNames = [
        1 => 'January', 2 => 'February', 3 => 'March',
        4 => 'April', 5 => 'May', 6 => 'June',
        7 => 'July', 8 => 'August', 9 => 'September',
        10 => 'October', 11 => 'November', 12 => 'December'
    ];

    $tarikhbuatFinal = $dateTime->format('d ') . $monthNames[$dateTime->format('n')] . $dateTime->format(' Y');

    $tarikhSemVal = new DateTime($tarikhbuat);
    $tarikhSemVal->modify('-1 day');

    $newDate = $tarikhSemVal->format('Y-m-d');
    $tarikhSemakDCS = $tarikhSemVal->format('d') . " " . $monthNames[$tarikhSemVal->format('n')] . " " . $tarikhSemVal->format('Y');

    $formattedBakitunggakan = strpos($row['BAKITUNGGAKAN'], '.') !== false
        ? number_format((float)$row['BAKITUNGGAKAN'], 2, '.', '')
        : $row['BAKITUNGGAKAN'] . ".00";
} else {
    echo "Record not found";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


	//print_r($_POST);


	$id = $_POST['hididrec'];
	$newStatus = $_POST['status'];
    $newDate = $_POST['tarikhbuat'];

    $norumahtop = $_POST['norumahupper'];
    $addr1top = $_POST['addrtop1'];
    $addr2top = $_POST['addrtop2'];
    $postcodetop = $_POST['postcodetop'];

    $norumahbody = $_POST['norumahbody'];
    $addr1body = $_POST['addrbodytxt1'];
    $addr2body = $_POST['addrbodytxt2'];
    $postcodebody = $_POST['postcodebody'];



   	$sql = "UPDATE TBL_DATA SET STATUS_DATA = '$newStatus',TARIKH_BUAT ='$newDate',NORUMAH_TOP='$norumahtop',ADDR1_TOP='$addr1top',ADDR2_TOP='$addr2top',POSTCODE_TOP='$postcodetop',NORUMAH='$norumahbody',ALAMAT1='$addr1body',ALAMAT2='$addr2body',POSKOD='$postcodebody' WHERE iddata=".(int)$id;


   	if ($connection->query($sql) === TRUE) {
        echo '<script>window.location.href = "http://localhost/flutterapps/api/taxtrek/main.php";</script>';
        exit();
    } else {
        echo "Error updating record: " . $connection->error;
    }

  
}

// Close the database connection
$connection->close();


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
					<input class="form-control form-control-sm" type="text" value="<?php echo $namapemilik; ?>" oninput="this.value = this.value.toUpperCase()" name="namapemilikupper" id="namapemilikupper">
				</td>
			</tr>
			<!--
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $norumah." ".$namapremis; ?>" oninput="this.value = this.value.toUpperCase()" name="addrtoptxt2" id="addrtoptxt2">
				</td>
			</tr>
		-->
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $norumah; ?>" oninput="this.value = this.value.toUpperCase()" name="norumahupper" id="norumahupper">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $alamat1; ?>" oninput="this.value = this.value.toUpperCase()" name="addrtop1" id="addrtop1">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $alamat2; ?>" oninput="this.value = this.value.toUpperCase()" name="addrtop2" id="addrtop2">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $poskod; ?>" oninput="this.value = this.value.toUpperCase()" name="postcodetop" id="postcodetop">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left" class="bg-danger text-light">
					BODY LETTER ADDRESS
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $namapemilik; ?>" oninput="this.value = this.value.toUpperCase()" name="namapemilikbody" id="namapemilikbody">
				</td>
			</tr>
			<!--
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $norumah." ".$namapremis; ?>" oninput="this.value = this.value.toUpperCase()" name="addrbodytxt2" id="addrbodytxt2">
				</td>
			</tr>
		-->
		<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $norumah; ?>" oninput="this.value = this.value.toUpperCase()" name="norumahbody" id="norumahbody">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $alamat1; ?>" oninput="this.value = this.value.toUpperCase()" name="addrbodytxt1" id="addrbodytxt1">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $alamat2; ?>" oninput="this.value = this.value.toUpperCase()" name="addrbodytxt2" id="addrbodytxt2">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="text-left">
					<input class="form-control form-control-sm" type="text" value="<?php echo $poskod; ?>" oninput="this.value = this.value.toUpperCase()" name="postcodebody" id="postcodebody">
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


