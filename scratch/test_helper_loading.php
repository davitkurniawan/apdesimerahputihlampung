<?php
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('FCPATH', '/var/www/html/');

$helpers = glob(APPPATH . 'helpers/*_helper.php');
foreach ($helpers as $helper) {
    ob_start();
    try {
        @include_once $helper;
    } catch (\Throwable $e) {}
    ob_end_clean();
    if (function_exists('admin_menu')) {
        echo "Found admin_menu in: " . $helper . "\n";
        exit;
    }
}
echo "Not found in donjo-app/helpers\n";
