<?php
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');

require_once BASEPATH . 'core/Common.php';

// Try to find get_app_key
$helpers = [
    'general',
    'opensid',
    'core',
    'donjolib'
];

foreach ($helpers as $helper) {
    $file = APPPATH . "helpers/{$helper}_helper.php";
    if (file_exists($file)) {
        try {
            @include_once $file;
            if (function_exists('get_app_key')) {
                echo "Found get_app_key() in {$helper}_helper.php!\n";
                echo "get_app_key() value: " . get_app_key() . "\n";
                exit;
            }
        } catch (Throwable $e) {
            echo "Error loading {$helper}_helper: " . $e->getMessage() . "\n";
        }
    }
}

echo "get_app_key() not found in the listed helpers.\n";
?>
