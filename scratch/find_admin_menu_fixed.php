<?php
define('FORCE_HTTPS', false);
define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('SELF', 'index.php');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost';
$_SERVER['REQUEST_URI'] = '/index.php/beranda';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once '/var/www/html/vendor/autoload.php';
ob_start();
require_once BASEPATH . 'core/CodeIgniter.php';
ob_end_clean();

if (function_exists('admin_menu')) {
    $r = new ReflectionFunction('admin_menu');
    echo "Found admin_menu in: " . $r->getFileName() . ":" . $r->getStartLine() . "\n";
} else {
    echo "admin_menu is not defined\n";
}
