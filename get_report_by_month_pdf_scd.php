<?php 
	//include 'connectdb.php';
include("classes/DataBase.class.php");
include("classes/Utility.class.php");
include("classes/RandomStringUtils.class.php");


ob_start();



$month = (isset($_GET['monthval']))?$_GET['monthval']:'';

//$month  = "NOVEMBER";

$monthToSel = "";

if($month == "JANUARI"){
	$monthToSel = "01";
}else if($month == "FEBRUARI"){
	$monthToSel = "02";
}else if($month == "MAC"){
	$monthToSel = "03";
}else if($month == "APRIL"){
	$monthToSel = "04";
}else if($month == "MEI"){
	$monthToSel = "05";
}else if($month == "JUN"){
	$monthToSel = "06";
}else if($month == "JULAI"){
	$monthToSel = "07";
}else if($month == "OGOS"){
	$monthToSel = "08";
}else if($month == "SEPTEMBER"){
	$monthToSel = "09";
}else if($month == "OKTOBER"){
	$monthToSel = "10";
}else if($month == "NOVEMBER"){
	$monthToSel = "11";
}else if($month == "DISEMBER"){
	$monthToSel = "12";
}






?>


<style type="text/css">
	tr, td {
	  padding-top: 10px;
	  padding-bottom: 20px;
	  padding-left: 30px;
	  padding-right: 30px;
	}

</style>

<?php




// Include the main TCPDF library via Composer autoload.
require_once 'vendor/autoload.php';

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle('Site Diary-C&S-Arch');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, '15', PDF_MARGIN_RIGHT);

//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, -1);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica','', 10);
 				
// add a page
$pdf->AddPage();


$tbl = <<<EOD

		<table width="660" height="126" border="1" style="padding-top:10px;padding-bottom:10px;padding-left:4px;padding-right:4px;">
		 	
		  	<tr style="font-size:14px !important;">
		  		<td width="50"><strong>BIL</strong></td>
		  		<td width="200"><strong>CUSTOMER</strong></td>
		  		<td><strong>VEHICLE</strong></td>
		  		<td><strong>PACKAGE</strong></td>
		  		<td width="80"><strong>DATE</strong></td>
		  		<td width="80"><strong>TIME SLOT</strong></td>
		  	</tr>
EOD;



$db = DataBase::getInstance();

$data = array();

if(is_object($db)){


    $sqlsel = "SELECT * FROM ".TBL_BOOKINGS." WHERE MONTH(book_date_request)='".$monthToSel."' ORDER BY ins_dt DESC";
        	$row = $db->executeGrab($sqlsel);
	if(is_array($row)){

		$len = count($row);


		if($len>0){
			
			for($i=0;$i<$len;$i++){

				$userstr = Utility::getUserDetailsByID($row[$i]['ins_by']);

				$arruser = explode("|", $userstr);

				$carstr = Utility::getCarDetsByID($row[$i]['ins_by']);

				$arrcar = explode("|", $carstr);
				//$data = $row['idc']."|".$row['cm']."|".$row['ccol']."|".$row['cplatno'];


				$val1 = ($i+1);
				$val2 = $arruser[1];
				$val3 = $arrcar[1];
				$val4 = $row[$i]['book_package'];
				$val5 = $row[$i]['book_date_request'];
				$val6 = $row[$i]['book_timeslot'];


$tbl .= <<<EOD
		  	<tr>
		  		<td width="50">$val1</td>
		  		<td width="200">$val2</td>
		  		<td>$val3</td>
		  		<td>$val4</td>
		  		<td width="80">$val5</td>
		  		<td width="80">$val6</td>
		  	</tr>
EOD;


			}//end for
			

			
		}else{

$tbl .= <<<EOD
		  	<tr align="center">
		  		<td colspan="6">- Empty record -</td>
		  	</tr>
EOD;

		}

	}

}


$tbl .= <<<EOD
			</table>
		
		<br>
EOD;


$pdf->writeHTML($tbl, true, false, false, false, '');
//$pdf->writeHTML($htmlcontent, true, false, false, false, '');

// set font
$pdf->SetFont('helvetica','', 9);


ob_end_clean();

$finalFileNames = 'report_by_package_'.RandomStringUtils::randomString(10).'.pdf';

//$pdf->Output('report_by_package.pdf', 'I');
$pdf->Output($finalFileNames, 'I');

//echo $pdf->Output($finalFileNames. date('Y-m-d').'.pdf', 'D'); //-->for auto download
?>




</body>
</html>