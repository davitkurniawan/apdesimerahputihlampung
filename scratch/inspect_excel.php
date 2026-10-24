<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Rap2hpoutre\FastExcel\FastExcel;

$excelPath = __DIR__ . '/Format_Data_Export_Penduduk.xlsx';
if (!file_exists($excelPath)) {
    die("Excel file not found at $excelPath\n");
}

echo "Loading Excel sheet...\n";
try {
    $rows = (new FastExcel)->import($excelPath);
    echo "Total rows in Excel: " . count($rows) . "\n\n";
    
    if (count($rows) > 0) {
        $firstRow = $rows->first();
        echo "=== HEADERS ===\n";
        print_r(array_keys($firstRow));
        
        echo "\n=== FIRST 2 ROWS CONTENT ===\n";
        $count = 0;
        foreach ($rows as $row) {
            echo "Row " . ($count + 1) . ":\n";
            print_r($row);
            echo "----------------------------------------\n";
            $count++;
            if ($count >= 2) break;
        }
    } else {
        echo "Excel file is empty.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
