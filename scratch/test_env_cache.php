<?php
putenv('CACHE_PATH=/tmp/test_cache_path');
putenv('CACHE_DIR=/tmp/test_cache_dir');

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
$_SERVER['HTTP_HOST'] = 'localhost:8080';

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return 'http://localhost:8080/' . $path;
    }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) {
        return 'Desa';
    }
}
if (!function_exists('kades')) {
    function kades() {
        return 'Kepala Desa';
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Cache Path in config: " . config('cache.stores.file.path') . "\n";
