<?php
define('BASEPATH', 'test');
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['hapus_data'] = 'hapus';
$_FILES['userfile'] = [
    'name' => 'Data Penduduk-impor-bumidaya.xlsx',
    'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'tmp_name' => '/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya.xlsx',
    'error' => 0,
    'size' => filesize('/Users/kampungjagoan/Downloads/Data Penduduk/Data Penduduk-impor-bumidaya.xlsx')
];

// Bootstrap CI to load Penduduk_model
// Wait, loading CI completely from a standalone script is tricky without standard index.php.
// Let's create a temporary controller in donjo-app/controllers to do it!
