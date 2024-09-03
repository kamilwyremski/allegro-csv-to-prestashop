<?php

// Nazwa pliku CSV z danymi JSON z Allegro
$inputCsvFile = 'allegro.csv';

// Nazwa pliku wyjściowego CSV
$outputFileName = 'prestashop.csv';

// Otwarcie pliku wejściowego CSV
$inputFileHandle = fopen($inputCsvFile, 'r');

// Otwarcie pliku wyjściowego CSV
$outputFileHandle = fopen($outputFileName, 'w');

// Dodanie BOM do pliku wyjściowego w celu poprawnego wyświetlania polskich znaków
fprintf($outputFileHandle, chr(0xEF).chr(0xBB).chr(0xBF));

// Przetwarzanie pliku CSV wiersz po wierszu
while (($row = fgetcsv($inputFileHandle, 0, ";")) !== FALSE) {

    $jsonColumn = $row[16];

    if ($jsonColumn) {

        // Parsowanie danych JSON
        $jsonData = json_decode($jsonColumn, true);
        $htmlContent = '';

        // Przetwarzanie sekcji JSON
        if (!empty($jsonData['sections'])) {
            foreach ($jsonData['sections'] as $section) {
                // Przetwarzanie elementów w sekcji
                foreach ($section['items'] as $item) {
                    // Generowanie HTML na podstawie typu elementu
                    if ($item['type'] == 'IMAGE') {
                        $htmlContent .= '<img src="' . $item['url'] . '" alt="Image">';
                    } elseif ($item['type'] == 'TEXT') {
                        $htmlContent .= $item['content'];
                    }
                }
            }
        }

        // Zastąpienie zawartości kolumny JSON wygenerowanym HTML
        $row[16] = $htmlContent;
    }

    // Usunięcie zbędnych kolumn
    $columnsToUnset = [0, 1, 3, 4, 5, 6, 8, 9, 31, 32, 33, 34];
    foreach ($columnsToUnset as $colIndex) {
        unset($row[$colIndex]);
    }

    // Ustawienie wartości w wybranych kolumnach
    $row[7] = '1';  // Aktywność
    $row[10] = preg_replace('/\([^)]*\)/', '', $row[10]);  // Usunięcie tekstu w nawiasach z nazwy kategorii
    $row[10] = implode('|', array_map('trim', explode('>', $row[10])));  // Zmiana separatora kategorii
    $row[30] = 'new';  // Status produktu
    $row[37] = '1';  // Dostępność produktu

    // Usunięcie niepotrzebnych kolumn od indeksu 17 do 29 i powyżej 37
    foreach ($row as $index => $value) {
        if ($index > 37 || ($index >= 17 && $index <= 29)) {
            unset($row[$index]);
        }
    }

    // Zapisanie przetworzonego wiersza do pliku wyjściowego
    fputcsv($outputFileHandle, $row, ';');
}

// Zamknięcie plików
fclose($inputFileHandle);
fclose($outputFileHandle);

echo 'Plik CSV został wygenerowany: ' . $outputFileName;
