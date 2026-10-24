<?php
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
define('FORCE_HTTPS', false);
define('APP_URL', 'http://localhost:8080');

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key = null, $default = null) { return 'Desa'; }
}
if (!function_exists('ci_auth')) {
    function ci_auth() {
        return new class {
            public $id = 1;
            public function user() { return $this; }
        };
    }
}
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cfg = $app['config']->all();
foreach($cfg as $k => $v) {
    $json = json_encode($v);
    if (strpos($json, '10') !== false) {
        echo "Config key '$k' contains 10\n";
    }
}

// Let's also print specific config values if they exist
echo "database.connections.tenant: " . json_encode($app['config']->get('database.connections.tenant')) . "\n";
echo "database.connections.mysql: " . json_encode($app['config']->get('database.connections.mysql')) . "\n";
