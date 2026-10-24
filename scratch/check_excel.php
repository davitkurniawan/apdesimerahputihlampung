<?php
require 'vendor/autoload.php';
use Rap2hpoutre\FastExcel\FastExcel;

$collection = (new FastExcel)->import('scratch/test_impor.xlsx');
foreach ($collection as $row) {
    echo $row['nik'] . "\n";
}
