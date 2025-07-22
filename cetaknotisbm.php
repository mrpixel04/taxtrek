<?php 

// Include database connection
include("classes/conn.php");

error_reporting(E_ALL); // Report all errors and warnings
ini_set('display_errors', 1);


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


?>