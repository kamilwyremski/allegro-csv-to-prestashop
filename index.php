<?php

// Name of the CSV file with JSON data from Allegro
$inputCsvFile = 'allegro.csv';

// Name of the output CSV file
$outputFileName = 'prestashop.csv';

// Open the input CSV file
$inputFileHandle = fopen($inputCsvFile, 'r');

// Open the output CSV file
$outputFileHandle = fopen($outputFileName, 'w');

// Add BOM to the output file for correct display of Polish characters
fprintf($outputFileHandle, chr(0xEF).chr(0xBB).chr(0xBF));

// Process the CSV file row by row
while (($row = fgetcsv($inputFileHandle, 0, ";")) !== FALSE) {

    $jsonColumn = $row[16];

    if ($jsonColumn) {

        // Parse the JSON data
        $jsonData = json_decode($jsonColumn, true);
        $htmlContent = '';

        // Process the JSON sections
        if (!empty($jsonData['sections'])) {
            foreach ($jsonData['sections'] as $section) {
                // Process items in the section
                foreach ($section['items'] as $item) {
                    // Generate HTML based on the item type
                    if ($item['type'] == 'IMAGE') {
                        $htmlContent .= '<img src="' . $item['url'] . '" alt="Image">';
                    } elseif ($item['type'] == 'TEXT') {
                        $htmlContent .= $item['content'];
                    }
                }
            }
        }

        // Replace the content of the JSON column with the generated HTML
        $row[16] = $htmlContent;
    }

    // Remove unnecessary columns
    $columnsToUnset = [0, 1, 3, 4, 5, 6, 8, 9, 31, 32, 33, 34];
    foreach ($columnsToUnset as $colIndex) {
        unset($row[$colIndex]);
    }

    // Set values in selected columns
    $row[7] = '1';  // Activity
    $row[10] = preg_replace('/\([^)]*\)/', '', $row[10]);  // Remove text in parentheses from the category name
    $row[10] = implode('|', array_map('trim', explode('>', $row[10])));  // Change the category separator
    $row[30] = 'new';  // Product status
    $row[37] = '1';  // Product availability

    // Remove unnecessary columns from index 17 to 29 and above 37
    foreach ($row as $index => $value) {
        if ($index > 37 || ($index >= 17 && $index <= 29)) {
            unset($row[$index]);
        }
    }

    // Save the processed row to the output file
    fputcsv($outputFileHandle, $row, ';');
}

// Close the files
fclose($inputFileHandle);
fclose($outputFileHandle);

echo 'The CSV file has been generated: ' . $outputFileName;
