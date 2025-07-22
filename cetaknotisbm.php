<?php 
// Start output buffering immediately to prevent any output
ob_start();

// Include database connection
include("db_connect.php");

error_reporting(E_ALL); // Report all errors and warnings
ini_set('display_errors', 1);

// Clear any existing output buffer
ob_clean();

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
           // $namapremis = $row['NAMAPREMIS'];
            $alamat1 = $row['ALAMAT1'];
            $alamat2 = $row['ALAMAT2'];
            $poskod = $row['POSKOD'];
            //$noakaun = $row['NOAKAUN'];
            
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

//generating pdf

// Clear any output buffer before PDF generation
ob_clean();

require_once 'vendor/autoload.php';


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

// HTML table content - Fixed without br tags and proper indentation
$tbl = <<<EOD
<style>
    table { margin-top: 40px; }
</style>

<table border="0">
    <tr style="font-size:11px !important;">
        <td align="right" width="65%" colspan="2">Rujukan Kami&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td> 
        <td width="35%">   (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)DBKL/JKW/2023/HASIL-44</td>
    </tr>
    <tr style="font-size:11px !important;">
        <td width="52%"></td>
        <td align="left" width="13%">Tarikh </td> 
        <td width="35%">:&nbsp;&nbsp;$tarikhbuatFinal</td>
    </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$namapemilik</strong></td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$norumah</strong></td>
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
} else {
    $tbl .= <<<EOD
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$alamat2</strong></td>
    </tr>
    <tr>
        <td colspan="3" style="font-size:10px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>$poskod KUALA LUMPUR</strong></td>
    </tr>
EOD;
}

$tbl .= <<<EOD
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tuan / Puan Pemunya / Penyewa harta,</td>
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOTIS TUNTUTAN TUNGGAKAN CUKAI TAKSIRAN</td>
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NAMA PEMUNYA&nbsp;&nbsp;:&nbsp;&nbsp;$namapemilik</td>
    </tr>
    <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ALAMAT HARTA&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;$norumah $alamat1,</td>
    </tr>
EOD;

if($alamat2 == "-" || $alamat2 == " - " || empty($alamat2) || $alamat2 == null){
    $tbl .= <<<EOD
    <tr style="font-weight:bold !important;">
        <td colspan="2" width="26%"></td>
        <td width="74%" style="font-size:11px !important;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$poskod KUALA LUMPUR
        </td>
    </tr>
EOD;
} else {
    $tbl .= <<<EOD
    <tr style="font-weight:bold !important;">
        <td colspan="2" width="26%"></td>
        <td width="74%" style="font-size:11px !important;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$alamat2, $poskod KUALA LUMPUR
        </td>
    </tr>
EOD;
}

$tbl .= <<<EOD
    <tr style="font-weight:bold !important;">
        <td colspan="3" style="font-size:11px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO AKAUN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;N/A</td>
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adalah saya diarah merujuk kepada perkara di atas.</td>
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dimaklumkan bahawa Cukai Taksiran yang dikenakan bagi harta tersebut masih belum</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dibayar dan adalah dalam tunggakan berjumlah <span style="font-weight:bold;">RM $formattedBakitunggakan</span> sehingga <strong>$tarikhSemakDCS</strong></td> 
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tuan / puan adalah dengan ini dikehendaki menjelaskan bayaran tunggakan di atas</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;kepada Datuk Bandar Kuala Lumpur dalam tempoh <span style="font-weight:bold;">EMPAT BELAS (14) HARI</span> dari tarikh surat</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ini disampaikan.</td> 
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight:bold;">DAN AMBIL PERHATIAN </span>bahawa, jika jumlah yang dinyatakan itu tidak dibayar dengan</td> 
    </tr>
    <tr style="font-size:11px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;segera, maka tindakan undang-undang akan diambil terhadap anda.</td> 
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:12px !important;font-weight:bold;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"MALAYSIA MADANI"</td> 
    </tr>
    <tr style="font-size:12px !important;font-weight:bold;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"BERKHIDMAT UNTUK NEGARA"</td> 
    </tr>
    <tr style="font-size:12px !important;font-weight:bold;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"BERSEDIA MENYUMBANG, BANDARAYA CEMERLANG"</td> 
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:11px !important;">
        <td width="50%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Saya yang menjalankan amanah.</td> 
        <td width="40%;" align="left">
            <div style="border:0.5px solid black;font-size:9px !important;padding:5px !important;font-weight:bold !important;text-align:center !important;">
                Sila abaikan notis ini sekiranya bayaran penuh telah dibuat.
            </div>
        </td>
        <td width="10%;"></td>
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:9px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b.p. Datuk Bandar Kuala Lumpur</td> 
    </tr>
    
    <tr><td colspan="3">&nbsp;</td></tr>
    
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nama Ahli TF21 : <strong>Zakiah Hanum Binti Khusrin @ Kosrin</strong></td> 
    </tr>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. Tel : <strong>013-6245570</strong></td> 
    </tr>
    <tr style="font-size:8px !important;">
        <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tarikh Lawatan : <strong>$tarikhbuatFinal</strong></td> 
    </tr>
</table>
EOD;

// Output the HTML table content
$pdf->writeHTML($tbl, true, false, false, false, '');

// Output the PDF
$filename = 'Notis_Cukai_Pintu_' . (isset($namapemilik) ? str_replace(' ', '_', $namapemilik) : 'Unknown') . '_' . date('Y-m-d') . '.pdf';

// Clean output buffer completely before PDF output
ob_end_clean();

// Output PDF to browser
$pdf->Output($filename, 'I'); // 'I' for inline display, 'D' for download

exit(); // Ensure no further output
?>