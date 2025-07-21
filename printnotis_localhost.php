<?php 


include("classes/conn.php");
require_once('tcpdf/tcpdf.php');

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
                1 => 'Januari', 2 => 'Februari', 3 => 'Mac',
                4 => 'April', 5 => 'Mei', 6 => 'Jun',
                7 => 'Julai', 8 => 'Ogos', 9 => 'September',
                10 => 'Oktober', 11 => 'November', 12 => 'Disember'
            ];
            
            // Format the date as "d F Y" where "F" is the full month name
            $tarikhbuatFinal = $dateTime->format('d ') . $monthNames[$dateTime->format('n')] . $dateTime->format(' Y');
        
        
            
            $tarikhSemVal = new DateTime($tarikhbuat);
            $tarikhSemVal->modify('-1 day');

            $newDate = $tarikhSemVal->format('Y-m-d'); 

            $tarikhSemakDCS = $tarikhSemVal->format('d').$monthNames[$tarikhSemVal->format('n')].$tarikhSemVal->format('Y'); 
            


             // Check if the "BAKITUNGGAKAN" field contains a decimal point
            if (strpos($row['BAKITUNGGAKAN'], '.') !== false) {
                // If it contains a decimal point, format it with two decimal places
                $formattedBakitunggakan = number_format((float)$row['BAKITUNGGAKAN'], 2, '.', '');
            } else {
                // If it doesn't contain a decimal point, append ".00" to it
                $formattedBakitunggakan = $row['BAKITUNGGAKAN'] . ".00";
            }

            //echo "BAKI TUNGGAKAN (RM): " . $formattedBakitunggakan . "<br>";


            // Display the values in a plain row format
            /*
            echo "BIL: " . $row['BIL'] . "<br>";
            echo "NOMBOR FAIL TF19: " . $row['NOMBORFAILTF19'] . "<br>";
            echo "NO AKAUN: " . $row['NOAKAUN'] . "<br>";
            echo "NAMA PEMILIK: " . $row['NAMAPEMILIK'] . "<br>";
            echo "NO. RUMAH: " . $row['NORUMAH'] . "<br>";
            echo "NAMA PREMIS: " . $row['NAMAPREMIS'] . "<br>";
            echo "ALAMAT 1: " . $row['ALAMAT1'] . "<br>";
            echo "ALAMAT 2: " . $row['ALAMAT2'] . "<br>";
            echo "ALAMAT 3: " . $row['ALAMAT3'] . "<br>";
            echo "POSKOD: " . $row['POSKOD'] . "<br>";
            echo "BAKI TUNGGAKAN (RM): " . $row['BAKITUNGGAKAN'] . "<br>";
            */
     

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
$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('PDF Example');
$pdf->SetSubject('TCPDF Example');
$pdf->SetKeywords('TCPDF, PDF, example');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Set top padding
$pdf->SetMargins(0, 20, 0);
$pdf->SetAutoPageBreak(TRUE, 0);

//$pdf->Image('images/headerimg.jpg', 16, 29, 18, 18, 'jpeg', '', '', true, 150, '', false, false, 0, false, false, false);

//$pdf->Image('images/headerimg.jpg', 15, 140, 75, 113, 'JPG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 1, false, false, false);
//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
$pdf->Image('images/headerimg.png', 16, 8, 190, 38, 'png', '', '', true, 150, '', false, false, 0, false, false, false);

// HTML table content
$html = '
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
<table border="0">
    <tr style="font-size:11px !important;">
        <td align="right" width="65%" colspan="2">Rujukan Kami   : </td> 
        <td width="35%">   (    )DBKL/JKW/2023/HASIL-44</td>
    </tr>
     <tr style="font-size:11px !important;">
        <td width="52%"></td>
        <td align="left" width="13%">Tarikh </td> 
        <td width="35%">:&nbsp;&nbsp;</td>
    </tr>
     <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$namapemilik.'</strong></td>
        
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$norumah.' '.$namapremis.'</strong></td>
        
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$alamat1.'</strong></td>
        
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$alamat2.'</strong></td>
        
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$poskod.' KUALA LUMPUR</strong></td>
        
    </tr>
  
    <br/>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tuan / Puan Pemunya / Penyewa harta,</td>
        
    </tr>
   
    <br/>
    <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOTIS TUNTUTAN TUNGGAKAN CUKAI TAKSIRAN</td>
        
    </tr>
  
    <br/>
     <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NAMA PEMUNYA&nbsp;&nbsp;:&nbsp;&nbsp;'.$namapemilik.'</td>
        
    </tr>
     <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ALAMAT HARTA&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;'.$norumah.' '.$namapremis.' '.$alamat1.',</td>
        
    </tr>
    <tr style="font-weight:bold !important;">
        <td colspan="2" width="26%">
        </td>
        <td width="74%" style="font-size:11px !important;">
            &nbsp;&nbsp;'.$alamat2.','.$poskod.' 
        </td>
    </tr>
    <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO AKAUN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;'.$noakaun.'</td>
        
      
    </tr>
    <br/>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adalah saya diarah merujuk kepada perkara di atas.</td>
        
      
    </tr>
     <br/>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dimaklumkan bahawa Cukai Taksiran yang dikenakan bagi harta tersebut masih belum</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dibayar dan adalah dalam tunggakan berjumlah <span style="font-weight:bold;">RM '.$formattedBakitunggakan.'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; sehingga'.$tarikhSemakDCS.'</td> 
    </tr>
    <br/>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tuan / puan adalah dengan ini dikehendaki menjelaskan bayaran tunggakan di atas</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;kepada Datuk Bandar Kuala Lumpur dalam tempoh <span style="font-weight:bold;">EMPAT BELAS (14) HARI</span> dari tarikh surat</td> 
    </tr>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ini disampaikan.</td> 
    </tr>
     <br/>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:bold;">DAN AMBIL PERHATIAN </span>bahawa, jika jumlah yang dinyatakan itu tidak dibayar dengan</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sepenuhnya dalam tempoh yang ditetapkan maka pihak Datuk Bandar Kuala Lumpur akan</td> 
    </tr>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;meneruskan dengan tindakan undang-undang menurut Akta Kerajaan Tempatan 1976 (Akta</td> 
    </tr>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;171) di mana segala kos dan perbelanjaan berkaitan hendaklah ditanggung sepenuhnya oleh</td> 
    </tr>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tuan / puan.</td> 
    </tr>
    <br/>
     <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sekian, terima kasih.</td> 
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
        <td width="50%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saya yang menjalankan amanah.</td> 
        <td width="40%;"  align:"left">
            <div style="border:0.5px solid black;font-size:9px !important;padding-left:16px !important;font-weight:bold !important;text-align:center !important;" height="40px;"><\n><br/>&nbsp;Sila abaikan notis ini sekiranya bayaran<br/>penuh telah dibuat.<br/></div>
        
        </td>
        <td width="10%;"></td>
    </tr>
    <tr style="font-size:9px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b.p. Datuk Bandar Kuala Lumpur</td> 
    </tr>
    <br/>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Ahli TF19 : <strong>Zakiah Hanum Binti Khusrin @ Kosrin</strong></td> 
    </tr>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. Tel : <strong>013-6245570</strong></td> 
    </tr>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarikh Lawatan : </td> 
    </tr>


    
</table>
';

// Output the HTML table content
$pdf->writeHTML($html, true, false, false, false, '');

// Output the PDF to the browser or save it to a file
$pdf->Output('example.pdf', 'I');



?>




