<?php
define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . '/');
define('BASEPATH', FCPATH . 'vendor/codeigniter/framework/system/');
define('APPPATH', FCPATH . 'donjo-app/');

require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'helpers/url_helper.php';

echo "Helper loaded.\n";
if (function_exists('redirect')) {
    echo "redirect function exists.\n";
} else {
    echo "redirect function does not exist.\n";
}
// Try to call redirect but catch exit
try {
    redirect('koneksi_database/config');
} catch (\Throwable $e) {
    echo "Error calling redirect: " . $e->getMessage() . "\n";
}
echo "Done.\n";
