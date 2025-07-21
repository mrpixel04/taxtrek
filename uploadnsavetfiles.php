<?php 



include('vendor/autoload.php');





if (isset($_POST['submit'])) {

	$file = $_FILES['file']['tmp_name'];
    
    $file = $_FILES['file']['tmp_name'];
    
    if (empty($file)) {
       die("Please select an Excel file.");
    }

    $objPHPExcel = PHPExcel_IOFactory::load($file);


    // Specify the sheet name (You may need to adjust this)
    //$sheet = $objPHPExcel->getSheetByName("TFMEGA");


}



?>