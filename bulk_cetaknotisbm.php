<?php
// bulk_cetaknotisbm.php - Bulk PDF generator for multiple records using printnotis.php template
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

// Include database connection
include("db_connect.php");

// Get the list of data IDs from URL parameter
$dataIds = isset($_GET['ids']) ? $_GET['ids'] : '';

if (empty($dataIds)) {
    echo "Error: No data IDs provided.";
    exit();
}

// Parse comma-separated IDs
$idArray = explode(',', $dataIds);
$idArray = array_filter(array_map('trim', $idArray)); // Remove empty values

if (empty($idArray)) {
    echo "Error: Invalid data IDs.";
    exit();
}

// Include Composer autoloader for TCPDF
require_once 'vendor/autoload.php';

// Clean any output before PDF generation
ob_clean();

// Custom PDF class (same as printnotis.php)
class MYPDF extends TCPDF {
    public function Header() {
        // Header content (if any)
    }

    public function Footer() {
        // Footer content (if any)
    }
}

// Create new PDF document using the same class as printnotis.php
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information (same as printnotis.php)
$pdf->SetCreator('Frankis');
$pdf->SetAuthor('Mr FBI');
$pdf->SetTitle('BULK SURAT TUNTUTAN CUKAI PINTU 2023');
$pdf->SetSubject('BULK SURAT TUNTUTAN CUKAI PINTU 2023');
$pdf->SetKeywords('TCPDF, PDF, BULK_SURAT_TUNTUTAN_CUKAI_PINTU_2023_F19');

// Remove default header/footer (same as printnotis.php)
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$recordCount = 0;
$totalRecords = count($idArray);

// Loop through each data ID and generate PDF content
foreach ($idArray as $dataId) {
    $dataId = mysqli_real_escape_string($connection, $dataId);
    
    // Fetch record data using prepared statement (same as printnotis.php)
    $sql = "SELECT * FROM TBL_DATA WHERE iddata = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt === false) {
        continue; // Skip this record if prepare fails
    }

    $stmt->bind_param("i", $dataId); // Assuming 'iddata' is an integer

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $recordCount++;

            // Add a new page for each record
            $pdf->AddPage();

            // CRITICAL: Reset margins and positioning for each page (same as printnotis.php)
            $pdf->SetMargins(0, 20, 0);
            $pdf->SetAutoPageBreak(TRUE, 0);
            
            // Reset Y position to ensure consistent positioning
            $pdf->SetY(20);

            // Add header image (same as printnotis.php) - positioned consistently
            $pdf->Image('images/headerimg.png', 4, 3, 200, 48, 'png', '', 'center', true, 150, '', false, false, 0, false, false, false);

            // Extract data with same logic as printnotis.php
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
            
            // Check if the "BAKITUNGGAKAN" field contains a decimal point (same logic as printnotis.php)
            if (strpos($row['BAKITUNGGAKAN'], '.') !== false) {
                // If it contains a decimal point, format it with two decimal places
                $formattedBakitunggakan = number_format((float)$row['BAKITUNGGAKAN'], 2, '.', '');
            } else {
                // If it doesn't contain a decimal point, append ".00" to it
                $formattedBakitunggakan = $row['BAKITUNGGAKAN'] . ".00";
            }

            // HTML table content (EXACT SAME as printnotis.php with record counter added)
            $html = '
             <style>
                   
                    p{
                        padding-top:290px !important;
                    }          
                </style>
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
                    <td width="35%">   DBKL/JKW/2025/HASIL-26 (  )</td>
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
                        &nbsp;'.$alamat2.','.$poskod.' 
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
                    <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dibayar, jumlah tunggakan adalah sebanyak <span style="font-weight:bold;">RM '.$formattedBakitunggakan.'</span>&nbsp; sehingga <strong>'.$tarikhSemakDCS.'</strong></td> 
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
                <tr style="font-size:8px !important; color: #999;">
                    <td colspan="3" style="text-align:center; border-top: 1px solid #ddd; padding-top: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Bulk Print - Record '.$recordCount.' of '.$totalRecords.' (ID: '.$dataId.')</em></td> 
                </tr>
            </table>';

            // Output the HTML table content - ensure clean rendering
            $pdf->writeHTML($html, true, false, false, false, '');
            
            // Reset any leftover positioning after content
            $pdf->lastPage();

        } else {
            // Record not found - add error page
            $pdf->AddPage();
            $pdf->SetMargins(0, 20, 0);
            $pdf->SetY(20);
            $pdf->writeHTML('<div style="text-align: center; color: red; font-size: 14px; margin: 50px 0;">
                <strong>RALAT: Data dengan ID ' . htmlspecialchars($dataId) . ' tidak dijumpai.</strong>
            </div>', true, false, true, false, '');
        }
    }

    // Close the statement
    $stmt->close();
}

// Close database connection
if ($connection) {
    mysqli_close($connection);
}

// Clean output buffer (same as printnotis.php)
while( ob_get_level() ) {
    ob_end_clean();
}

// Generate filename
$filename = 'bulk_tuntutannotis2024_' . date('Y-m-d_H-i-s') . '_' . $recordCount . 'records.pdf';

// Output PDF (same as printnotis.php)
$pdf->Output($filename, 'I');

exit();
?> 