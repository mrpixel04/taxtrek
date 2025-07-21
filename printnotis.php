<?php 


//include("classes/conn.php");


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
            $noakaun = $row['NOAKAUNDREAMS'];
            
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
   <br/>
  
<table border="0">
    <tr style="font-size:11px !important;">
        <td align="right" width="65%" colspan="2">Rujukan Kami   : </td> 
        <td width="35%">   (    )DBKL/JKW/2023/HASIL-44</td>
    </tr>
     <tr style="font-size:11px !important;">
        <td width="52%"></td>
        <td align="left" width="13%">Tarikh </td> 
        <td width="35%">:&nbsp;&nbsp;'.$tarikhbuatFinal.'</td>
    </tr>
 
    <br/>
     <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$namapemilik.'</strong></td>
        
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$norumah.' </strong></td>
        
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
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ALAMAT HARTA&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;'.$norumah.' '.$alamat1.',</td>
        
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
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dibayar dan adalah dalam tunggakan berjumlah <span style="font-weight:bold;">RM '.$formattedBakitunggakan.'</span>&nbsp; sehingga <strong>'.$tarikhSemakDCS.'</strong></td> 
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
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Ahli TF21 : <strong>Zakiah Hanum Binti Khusrin @ Kosrin</strong></td> 
    </tr>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. Tel : <strong>013-6245570</strong></td> 
    </tr>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarikh Lawatan : <strong>'.$tarikhbuatFinal.'</strong></td> 
    </tr>


    
</table>
';


// Output the HTML table content
$pdf->writeHTML($html, true, false, false, false, '');


while( ob_get_level() ) {
    ob_end_clean();
}

// Output the PDF to the browser or save it to a file
$pdf->Output('tuntutannotis2024.pdf', 'I');
//$pdf->Output('tuntutannotis2023.pdf', 'D');


?>




