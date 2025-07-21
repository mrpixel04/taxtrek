<?php


// Load the existing image
$originalImage = imagecreatefromjpeg('img1.jpeg');

// Define the font settings
$fontColor = imagecolorallocate($originalImage, 255, 255, 0); // Yellow color (R, G, B)
$font = 15; // Font size
$margin = 10; // Margin from the edges

//$date = date('Y-m-d');
$currentDate = date('Y-m-d l'); // Format: 2023-10-09 Monday
$currentTime = date('H:i:s'); // Format: 22:51:00


// Calculate the position for the text (bottom right corner)
$bbox = imagettfbbox($font, 0, 'arial.ttf', $currentDate);
$textWidth = $bbox[2] - $bbox[0];
$textHeight = $bbox[1] - $bbox[7];
$x = imagesx($originalImage) - $textWidth - $margin;
$y = imagesy($originalImage) - $textHeight - $margin;

// Define the current date and time formats

// Combine the date, day name, and time
$combinedText = $currentTime . "\n" . $currentDate;

// Add the combined text to the image
imagettftext($originalImage, $font, 0, $x, $y, $fontColor, 'arial.ttf', $combinedText);

// Define the directory to save the image
$outputDirectory = 'imagesedit/';
$outputFileName = 'new2.jpg';

// Save the modified image to the specified directory
$outputPath = $outputDirectory . $outputFileName;
imagejpeg($originalImage, $outputPath);

// Clean up resources
imagedestroy($originalImage);

echo 'Image saved to: ' . $outputPath;


/*
// Load the existing image
$originalImage = imagecreatefromjpeg('img1.jpeg');

// Define the date and font settings
time in format 99:00:00
$date = '2023-10-10'; day name
$fontColor = imagecolorallocate($originalImage, 255, 255, 0); // Yellow color (R, G, B)

// Calculate the position for the text (bottom right corner)
$font = 15; // Font size
$margin = 10; // Margin from the edges
$bbox = imagettfbbox($font, 0, 'arial.ttf', $date);
$textWidth = $bbox[2] - $bbox[0];
$textHeight = $bbox[1] - $bbox[7];
$x = imagesx($originalImage) - $textWidth - $margin;
$y = imagesy($originalImage) - $textHeight - $margin;

// Add the date to the image
imagettftext($originalImage, $font, 0, $x, $y, $fontColor, 'arial.ttf', $date);

// Define the directory to save the image
$outputDirectory = 'imagesedit/';
$outputFileName = 'edited_image2.jpg';

// Save the modified image to the specified directory
$outputPath = $outputDirectory . $outputFileName;
imagejpeg($originalImage, $outputPath);

// Clean up resources
imagedestroy($originalImage);

echo 'Image saved to: ' . $outputPath;

*/



?>
