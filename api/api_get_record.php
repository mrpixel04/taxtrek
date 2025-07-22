<?php 

// Include database connection
include('../classes/conn.php');

if($connection){
	echo "OK";
}else{
	echo "NOT OK";
}


$sqlselect = "SELECT * FROM TBL_DATA WHERE STATUS_DATA='BELUM BUAT'";



//$result = $connection->query($sqlselect);



?>