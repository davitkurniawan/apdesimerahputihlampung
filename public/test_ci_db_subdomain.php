<?php
header('Content-Type: text/plain');
$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/siteman';

chdir('/var/www/html/');

define('FCPATH', '/var/www/html/');
define('ENVIRONMENT', 'production');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('LOKASI_CONFIG_DESA', 'desa/config/');

// Load CodeIgniter's database config
if (file_exists(APPPATH . 'config/database.php')) {
    include APPPATH . 'config/database.php';
}

echo "Active Group: " . ($active_group ?? 'not set') . "\n";
echo "Query Builder: " . ($query_builder ?? 'not set') . "\n\n";
echo "DB array:\n";
print_r($db ?? 'not set');
?>
