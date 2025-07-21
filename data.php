<?php

include("classes/conn.php");


?>
<!--<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">-->
<link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<div class="container">
	<div class="table-responsive">
	    <table id="dataTable" class="table table-bordered table-striped">
	        <thead>
	            <tr>
	                <th>BIL</th>
	                <th>NOMBOR FAIL TF19</th>
	                <th>NO. RUMAH</th>
	                <th>NAMA PREMIS</th>
	                <th>STATUS BUAT</th>
	                <th>TARKH BUAT</th>
	                <th>ACTION</th>
	            </tr>
	        </thead>
	        <tbody>
	           
	            <?php
	            

	        
	            $sql = "SELECT * FROM TBL_DATA";
	            $result = $connection->query($sql);

	            if ($result->num_rows > 0) {
	                while ($row = $result->fetch_assoc()) {
	                    echo "<tr>";
	                    echo "<td>{$row['BIL']}</td>";
	                    echo "<td>{$row['NOMBORFAILTF19']}</td>";
	                    echo "<td>{$row['NORUMAH']}</td>";
	                    echo "<td>{$row['NAMAPREMIS']}</td>";
	         
	                   
	                    	if($row['STATUS_DATA'] == "BELUM BUAT"){
	               
	            		?>
	            			<td><span class='badge text-bg-danger'>BELUM BUAT</span></td>
	            		<?php
	            			}else{

	            		?>
	            			<td><span class='badge text-bg-success'>BUAT</span></td>
	            		<?php

	            			}
	            		echo "<td>{$row['TARIKH_BUAT']}</td>";
	            		$urlprintnotis = "http://localhost/flutterapps/api/taskforce/printnotis.php?data=".$row['iddata'];
	            		$urlprintnotisbi = "http://localhost/flutterapps/api/taskforce/printnotisbi.php?data=".$row['iddata'];

	            		$urleditnotis = "?page=editdatanotis.php&data=".$row['iddata'];
	            		

	                    //echo "<td><a href='#' class='view-link' data-details='" . htmlspecialchars(json_encode($row)) . "'>View</a> | <a href='#' class='edit-link' data-details='" . htmlspecialchars(json_encode($row)) . "'>Edit</a> | <a href='".$urlprintnotis."'>Notis BM</a> | <a href='".$urlprintnotisbi."'>Notis BI</a></td>";

	                    echo "<td><a href='#' class='view-link' data-details='" . htmlspecialchars(json_encode($row)) . "'>View</a> | <a href='".$urleditnotis."' class='edit-link'>Edit Notice</a> | <a href='".$urlprintnotis."'>Notis BM</a> | <a href='".$urlprintnotisbi."'>Notis BI</a></td>";

	                    echo "</tr>";
	                }
	            }

	            // Close the database connection
	            $connection->close();
	            ?>
	        </tbody>
	    </table>
	</div>
	
</div>


<!-- Include jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">

var currentIdData; 

$(document).ready(function() {
    // Initialize DataTable
   var table = $('#dataTable').DataTable({
        "paging": true,
        "ordering": true,
        "info": true,
        "pageLength": 15, 
    });

	// Handle the "View" link click
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

	// Handle the "Edit" link click
	$('#dataTable tbody').on('click', 'a.edit-link', function() {
	    var data = $(this).data('details');
	    
	    // Populate the modal with the record details
	    $('#status').val(data.STATUS_DATA); // Populate the dropdown
	    $('#datepicker').val(data.TARIKH_BUAT); // Populate the date picker

	    var namaPemilik = $('#namaPemilik').val(data.NAMAPEMILIK);
	    var noRumah = $('#noRumah').val(data.NORUMAH);
	    var namaPremis = $('#namaPremis').val(data.NAMAPREMIS);
	    var alamat1 = $('#alamat1').val(data.ALAMAT1);
	    var alamat2 = $('#alamat2').val(data.ALAMAT2);
	    var alamat3 = $('#alamat3').val(data.ALAMAT3);
	    var poskod = $('#poskod').val(data.POSKOD);
	    var bakitTunggakan = $('#bakitTunggakan').val(data.BAKITUNGGAKAN);

	    currentIdData = data.iddata;

	    // Show the modal
	    $('#editModal').modal('show');
	});

	$('#saveChanges').on('click', function() {
	    // Get the values from the modal fields
	    var newStatus = $('#status').val();
	    var newDate = $('#datepicker').val();
	    var namaPemilik = $('#namaPemilik').val();
	    var noRumah = $('#noRumah').val();
	    var namaPremis = $('#namaPremis').val();
	    var alamat1 = $('#alamat1').val();
	    var alamat2 = $('#alamat2').val();
	    var alamat3 = $('#alamat3').val();
	    var poskod = $('#poskod').val();
	    var bakitTunggakan = $('#bakitTunggakan').val();


	    $.ajax({
	        type: 'POST',
	        url: 'update_record.php', // Create this PHP file to handle the update
	        data: {
	            id: currentIdData,
	            status: newStatus,
	            date: newDate,
	            namaPemilik: namaPemilik,
	            noRumah: noRumah,
	            namaPremis:namaPremis,
	            alamat1: alamat1,
	            alamat2: alamat2,
	            alamat3: alamat3,
	            poskod: poskod,
	            bakitTunggakan: bakitTunggakan,
	        },
	        success: function(response) {
	            // Handle success (e.g., close the modal, refresh the table, etc.)
	            $('#editModal').modal('hide');
	            // Refresh the table or update the specific row in the table as needed
	            location.reload(true);
	        },
	        error: function(xhr, status, error) {
	            // Handle error
	            console.error(error);
	        }
	    });
	
	});




});

</script>
<!-- Modal for View Details -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Details</h5>
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

<!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add fields here to edit the record -->
                <form id="editForm">
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="BUAT">BUAT</option>
                            <option value="BELUM BUAT">BELUM BUAT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="datepicker">Date</label>
                        <input type="date" class="form-control" id="datepicker" name="datepicker">
                    </div>
                    <!-- Additional fields -->
                    <div class="mb-3">
                        <label for="namaPemilik">Nama Pemilik</label>
                        <input type="text" class="form-control" id="namaPemilik" name="namaPemilik">
                    </div>
                    <div class="mb-3">
                        <label for="noRumah">No. Rumah</label>
                        <input type="text" class="form-control" id="noRumah" name="noRumah">
                    </div>
                    <div class="mb-3">
                        <label for="namaPemilik">Nama Premis</label>
                        <input type="text" class="form-control" id="namaPremis" name="namaPremis">
                    </div>
                    <div class="mb-3">
                        <label for="alamat1">Alamat 1</label>
                        <input type="text" class="form-control" id="alamat1" name="alamat1">
                    </div>
                    <div class="mb-3">
                        <label for="alamat2">Alamat 2</label>
                        <input type="text" class="form-control" id="alamat2" name="alamat2">
                    </div>
                     <div class="mb-3">
                        <label for="alamat2">Alamat 3</label>
                        <input type="text" class="form-control" id="alamat3" name="alamat3">
                    </div>
                    <div class="mb-3">
                        <label for="poskod">Poskod</label>
                        <input type="text" class="form-control" id="poskod" name="poskod">
                    </div>
                    <div class="mb-3">
                        <label for="bakitTunggakan">Baki Tunggakan (RM)</label>
                        <input type="text" class="form-control" id="bakitTunggakan" name="bakitTunggakan">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>
