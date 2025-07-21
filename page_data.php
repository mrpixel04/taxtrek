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




//echo $_SERVER['PHP_SELF'];

//$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]";


$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
$base_url = dirname($base_url) . '/';

//echo $base_url;


?>
<link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<div class="container">

	<div class="table-responsive">

		<table id="dataTable" class="table table-bordered table-striped">
	        <thead>
	            <tr>
	                <th>BIL</th>
	                <!--<th>NOMBOR FAIL</th>-->
	                <!--<th>NO. RUMAH</th>-->
	                <th>NAMA PEMILIK</th>
	                <th>ALAMAT 1</th>
	                <th>STATUS</th>
	                <th>STATUS KEMASKINI</th>
	                <th>TINDAKAN</th>
	            </tr>
	        </thead>
	        <tbody>
	        	 <?php
	            

	        
	            //$sql = "SELECT * FROM TBL_DATA WHERE STATUS_DATA='BELUM BUAT'";
	            $sql = "SELECT * FROM TBL_DATA";
	            $result = $connection->query($sql);

	            if ($result->num_rows > 0) {
	                while ($row = $result->fetch_assoc()) {
	                    echo "<tr>";
	                    echo "<td>{$row['iddata']}</td>";
	                    //echo "<td>{$row['NOFAILTF']}</td>";
	                    //echo "<td>{$row['NORUMAH']}</td>";
	                    echo "<td>{$row['NAMAPEMILIK']}</td>";
	                    echo "<td>{$row['ALAMAT1']}</td>";
	         
	                   
	                    	if($row['STATUS_DATA'] == "BELUM BUAT"){
	               
	            		?>
	            			<td><span class='badge text-bg-danger'>BELUM BUAT</span></td>

	            		<?php 

	            			}else if($row['STATUS_DATA'] == "BAYAR"){

	            		?>
	            			<td><span class='badge text-bg-warning'>TELAH BAYAR</span></td>
	            		<?php

	            			}else{

	            		?>
	            			<td><span class='badge text-bg-success'>BUAT</span></td>
	            		<?php

	            			}

	            			if($row['STATUS_UPDATE'] == "YES"){

	            		?>
	            				<td><span class='badge text-bg-success'>SUDAH KEMASKINI</span></td>

	            		<?php 

	            			}else{

	            		?>
	            				<td><span class='badge text-bg-danger'>BELUM KEMASKINI</span></td>
	            		<?php 

	            			}

	            		$urlhome = $_SERVER['PHP_SELF'].'?page=page_data.php';



	            		

	            		//$urlprintnotis = "http://localhost/flutterapps/api/taskforce/printnotis.php?data=".$row['iddata'];

	            		//$urlprintnotisbm = $base_url."printnotis.php?data=".$row['iddata'];
	            		
	            		$urlprintnotisbm = $base_url."cetaknotisbm.php?data=".$row['iddata'];

	            		$urlprintnotisbi = $base_url."printnotisbi.php?data=".$row['iddata'];

	            		$urleditdata = "?page=page_edit_data.php&data=".$row['iddata'];
	            		
	            		$urleditnotis = "?page=editalamat.php&data=".$row['iddata'];

	            		

	                    //echo "<td><a href='#' class='view-link' data-details='" . htmlspecialchars(json_encode($row)) . "'>Lihat</a> | <a href='".$urleditnotis."' class='edit-link'>Alamat</a> | <a href='".$urleditdata."' class='edit-link'>Data</a> | <a href='".$urlprintnotisbm."'>Notis BM</a> | <a href='".$urlprintnotisbi."'>Notis BI</a></td>";
	                    
	                    //echo "<td><a href='#' class='view-link' data-details='" . htmlspecialchars(json_encode($row)) . "'>Lihat</a> | <a href='".$urleditnotis."' class='edit-link'>Kemaskini Alamat</a> | <a href='".$urleditdata."' class='edit-link'>Data</a> | <a href='".$urlprintnotisbm."'>Notis BM</a> | <a href='".$urlprintnotisbi."'>Notis BI</a></td>";

	                    // echo "<td><a href='#' class='view-link' data-details='" . htmlspecialchars(json_encode($row)) . "'>Lihat</a> | <a href='".$urleditnotis."' class='edit-link'>Kemaskini Alamat</a> | <a href='".$urlprintnotisbm."'>Notis BM</a> | <a href='".$urlprintnotisbi."'>Notis BI</a></td>";

	                     echo "<td><a href='#' class='view-link' data-details='" . htmlspecialchars(json_encode($row)) . "'>Lihat</a> | <a href='".$urleditnotis."' class='edit-link'>Kemaskini Alamat</a> | <a href='".$urlprintnotisbm."'>Notis BM</a></td>";

	                    echo "</tr>";
	                }
	            }else{
	            	echo "huhuh";
	            }

	            // Close the database connection
	            $connection->close();
	            ?>
	        </tbody>
	    </table>
	           
	</div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">


	$(document).ready(function() {



		var table = $('#dataTable').DataTable({
	        "paging": true,
	        "ordering": true,
	        "info": true,
	        "pageLength": 90, 
    	});

    	$('#dataTable tbody').on('click', 'a.view-link', function() {
		    var data = $(this).data('details');

		     var modalDetails = $('#modal-details');

		    // Clear existing content in the modal
		    modalDetails.empty();

		   	// Iterate through the data properties and create HTML for each field
		    for (var key in data) {
		        if (data.hasOwnProperty(key)) {
		            var rowHtml = '<p>' + key + ': <span>' + data[key] + '</span></p>';
		            modalDetails.append(rowHtml);
		        }
		    }

		 

		    // Show the modal
		    $('#viewModal').modal('show');
		});



	});

</script>

<!-- Modal for View Details -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Maklumat Data Cukai Taksiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-details">
			        
			    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

