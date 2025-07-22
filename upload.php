<?php 

require __DIR__.'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

include('classes/conn.php');


if (isset($_POST['submit'])) {
    $file = $_FILES['file']['tmp_name'];
    
    // Check if a file was uploaded
    if (empty($file)) {
        die("Please select an Excel file.");
    }

    // Create a new PhpSpreadsheet object
    $objPHPExcel = IOFactory::load($file);

  	//conneciton db tuff here

    // Specify the sheet name (You may need to adjust this)
    $sheet = $objPHPExcel->getSheetByName("Sheet1");

    // Loop through rows in the Excel file
    foreach ($sheet->getRowIterator() as $row) {
        $data = [];
        $cellIterator = $row->getCellIterator();

        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }

        // Assuming the first row contains column names
        if (empty($header)) {
            $header = $data;
        } else {

            /*
            $insertData = [];
            foreach ($header as $key => $col) {
                $insertData[$col] = $data[$key];
            }

            // Insert data into MySQL table (TBL_DATA)
            $columns = implode(", ", array_keys($insertData));
            $values = "'" . implode("', '", $insertData) . "'";

       
            //$sql = "INSERT INTO TBL_DATA ($columns) VALUES ($values)";
            $sql = "INSERT INTO TBL_DATA (BIL,NOMBORFAILTF19,NOAKAUN,NAMAPEMILIK,NORUMAH,NAMAPREMIS,ALAMAT1,ALAMAT2,ALAMAT3,POSKOD,BAKITUNGGAKAN) VALUES ($values)";

            if ($connection->query($sql) === TRUE) {
                echo "Data inserted successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . $connection->error;
            }
            */
            
            //echo $values."<br/>==============================<br/>";
        }
    }

    // Close the database connection
    $connection->close();
}





/*
require __DIR__.'/vendor/autoload.php';

echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

// Add some data
echo date('H:i:s') . " Add some data\n";
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');

// Rename sheet
echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Simple');

// Save Excel 2007 file
echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Echo done
echo date('H:i:s') . " Done writing file.\r\n";

*/



?>