<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('scratch/test_impor.xlsx');
$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray();
echo "HEADERS:\n";
print_r($rows[2]); // OpenSID templates usually have headers on row 3
print_r($rows[3]);
print_r($rows[4]);
