<?php 


include("classes/conn.php");


ob_start();



$idrecord = (isset($_GET['data']))?$_GET['data']:'';


//echo $idrecord;


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


require_once('tcpdf/tcpdf.php');


class MYPDF extends TCPDF {
    public function Header() {
        // Header content (if any)
    }

    public function Footer() {
        // Footer content (if any)
    }
}

function echoNbsp($count) {
    $nbsp = str_repeat('&nbsp;', $count);
    echo $nbsp;
}



// Create a new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// Set document information
$pdf->SetCreator('Frankis');
$pdf->SetAuthor('Mr FBI');
$pdf->SetTitle('SURAT TUNTUTAN CUKAI PINTU 2023');
$pdf->SetSubject('SURAT TUNTUTAN CUKAI PINTU 2023');
$pdf->SetKeywords('TCPDF, PDF, SURAT_TUNTUTAN_CUKAI_PINTU_2023_F19');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Set top padding
$pdf->SetMargins(0, 20, 0);
$pdf->SetAutoPageBreak(TRUE, 0);

$pdf->Image('images/headerimg.png', 4, 3, 200, 48, 'png', '', 'center', true, 150, '', false, false, 0, false, false, false);

// HTML table content

$tbl = <<<EOD
 <style>
       

        p{
            padding-top:300px !important;
        }          
    </style>
   <br/>
   <br/>
   <br/>
   <br/>
   <br/>
   <br/>
   <br/>
   <br/>
   <br/>
  
<table border="0">
    <tr style="font-size:11px !important;">
        <td align="right" width="65%" colspan="2">Reference&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td> 
        <td width="35%">   (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)DBKL/JKW/2023/HASIL-44</td>
    </tr>
     <tr style="font-size:11px !important;">
        <td width="52%"></td>
        <td align="left" width="13%">Date </td> 
        <td width="35%">:&nbsp;&nbsp;$tarikhbuatFinal</td>
    </tr>
 
    <br/>
     <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$namapemilik</strong></td>
        
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$norumah $namapremis</strong></td>
        
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$alamat1</strong></td>
        
    </tr>
EOD;

if($alamat2 == "-" || $alamat2 == " - " || empty($alamat2) || $alamat2 == null){

$tbl .= <<<EOD

<tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$poskod KUALA LUMPUR</strong></td>
        
    </tr>

EOD;

}else{

$tbl .= <<<EOD
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$alamat2</strong></td>
        
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$poskod</strong></td>
        
    </tr>

EOD;

}


$tbl .= <<<EOD
    

    <br/>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Owner / Tenant,</td>
        
    </tr>
   
    <br/>
    <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOTICE OF DEMAND OF ASSESSMENT TAX ARREARS</td>
        
    </tr>
  
    <br/>
     <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NAME OF OWNER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;$namapemilik</td>
        
    </tr>
     <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROPERTY ADDRESS&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;$norumah $namapremis $alamat1,</td>
        
    </tr>

EOD;

if($alamat2 == "-" || $alamat2 == " - " || empty($alamat2) || $alamat2 == null){

$tbl .= <<<EOD
    <tr style="font-weight:bold !important;">
        <td colspan="2" width="26%">
        </td>
        <td width="74%" style="font-size:11px !important;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$poskod KUALA LUMPUR
        </td>
    </tr>
EOD;

}else{

$tbl .= <<<EOD
    
     <tr style="font-weight:bold !important;">
        <td colspan="2" width="26%">
        </td>
        <td width="74%" style="font-size:11px !important;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$alamat2 , $poskod KUALA LUMPUR
        </td>
    </tr>

EOD;

}

$tbl .= <<<EOD
    <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACCOUNT NO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;$noakaun</td>
        
      
    </tr>
    <br/>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We refer to the above matter.</td>
        
      
    </tr>
     <br/>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kindly be informed that the Assessment Tax payable for the said property has remain</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;unpaid and is in arrears amounting to  <span style="font-weight:bold;">RM $formattedBakitunggakan</span>&nbsp; as at <strong>$tarikhSemakDCS</strong></td> 
    </tr>
    <br/>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You are hereby required to pay the above arrears to the Mayor of Kuala Lumpur within</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:bold;">FOURTEEN (14) DAYS </span> from the date of this letter.</td> 
    </tr>

     <br/>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:bold;">PLEASE TAKE NOTE  </span>that if the above amount is not paid in full within the stipulated </td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;period, the Mayor of Kuala Lumpur will therefore proceed with legal action pursuant to the Local </td> 
    </tr>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Government Act 1976 (Act 171) to recover the arrears where all related costs and expenses shall </td> 
    </tr>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;be borne by you.</td> 
    </tr>
    
    <br/>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thank you.</td> 
    </tr>
    <br/>
     <tr style="font-size:12px !important;font-weight:bols;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"MALAYSIA MADANI"</td> 
    </tr>
     <br/>
    <tr style="font-size:12px !important;font-weight:bols;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"BERKHIDMAT UNTUK NEGARA"</td> 
    </tr>
    <tr style="font-size:12px !important;font-weight:bols;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"BERSEDIA MENYUMBANG, BANDARAYA CEMERLANG"</td> 
    </tr>
    <br/>
      <tr style="font-size:11px !important;">
        <td width="50%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sincerely,</td> 
        <td width="40%;"  align:"left">
            <div style="border:0.5px solid black;font-size:9px !important;padding-left:16px !important;font-weight:bold !important;text-align:center !important;" height="40px;"><br/>&nbsp;If full settlement has been made, please<br/>disregard this message.<br/></div>
        
        </td>
        <td width="10%;"></td>
    </tr>
    <tr style="font-size:9px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;o.b. Mayor of Kuala Lumpur</td> 
    </tr>
    <br/>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TF19 Member\'s name : <strong>Zakiah Hanum Binti Khusrin @ Kosrin</strong></td> 
    </tr>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tel No. : <strong>013-6245570</strong></td> 
    </tr>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date of Visit : <strong>$tarikhbuatFinal</strong></td> 
    </tr>


    
</table>
EOD;


// Output the HTML table content
$pdf->writeHTML($tbl, true, false, false, false, '');


while( ob_get_level() ) {
    ob_end_clean();
}

// Output the PDF to the browser or save it to a file
$pdf->Output('tuntutannotis2023.pdf', 'I');
//$pdf->Output('tuntutannotis2023.pdf', 'D');


?>




