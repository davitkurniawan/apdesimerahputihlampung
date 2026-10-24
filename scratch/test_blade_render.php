<?php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', FCPATH . 'vendor/codeigniter/framework/system/');
define('APPPATH', FCPATH . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CONFIG_DESA', 'desa/config/');

if (!function_exists('resource_path')) {
    function resource_path($path = '') {
        return FCPATH . 'resources/' . ltrim($path, '/');
    }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return FCPATH . 'storage/' . ltrim($path, '/');
    }
}
if (!function_exists('config_path')) {
    function config_path($path = '') {
        return FCPATH . 'config/' . ltrim($path, '/');
    }
}
if (!function_exists('app_path')) {
    function app_path($path = '') {
        return FCPATH . 'app/' . ltrim($path, '/');
    }
}
if (!function_exists('public_path')) {
    function public_path($path = '') {
        return FCPATH . 'public/' . ltrim($path, '/');
    }
}
if (!function_exists('base_path')) {
    function base_path($path = '') {
        return FCPATH . ltrim($path, '/');
    }
}
if (!function_exists('ci')) {
    function ci() {
        return function_exists('get_instance') ? get_instance() : null;
    }
}

require_once FCPATH . 'vendor/autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';

// Include database helpers
include_once FCPATH . 'xosid_database_helpers.php';

// Load site_url etc.
if (file_exists(FCPATH . 'xosid_emergency_autoload.php')) {
    require_once FCPATH . 'xosid_emergency_autoload.php';
}

// Include all CI helpers
$ci_helpers = [
    'core_helper.php',
    'database_helper.php',
    'donjolib_helper.php',
    'general_helper.php',
    'illuminate_helper.php',
    'nomor_rtm_helper.php',
    'opensid_helper.php',
    'password_helper.php',
    'pict_helper.php',
    'surat_helper.php',
    'tglindo_helper.php',
    'theme_helper.php',
    'web_helper.php'
];
foreach ($ci_helpers as $helper) {
    try {
        @include_once FCPATH . 'donjo-app/helpers/' . $helper;
    } catch (\Throwable $e) {
        echo "Error loading helper $helper: " . $e->getMessage() . "\n";
    }
}

// Try to read database config and inject
if (file_exists(FCPATH . 'desa/config/database.php')) {
    require FCPATH . 'desa/config/database.php';
    if (isset($db['default'])) {
        $ci_db = $db['default'];
        $db_pass = $ci_db['password'] ?? 'password';
        if (is_string($db_pass) && strpos($db_pass, 'eyJpdiI6') === 0) {
            try {
                if (isset($app['encrypter'])) {
                    $db_pass = $app['encrypter']->decrypt($db_pass);
                }
            } catch (\Throwable $e) {}
        }
        $laravel_db = [
            'driver'    => 'mysql',
            'host'      => $ci_db['hostname'] ?? 'db',
            'port'      => $ci_db['port'] ?? 3306,
            'database'  => $ci_db['database'] ?? 'xosid',
            'username'  => $ci_db['username'] ?? 'root',
            'password'  => $db_pass,
            'charset'   => 'utf8mb4',
            'collation' => $ci_db['dbcollat'] ?? 'utf8mb4_general_ci',
            'prefix'    => '',
            'strict'    => $ci_db['stricton'] ?? true,
            'engine'    => null,
        ];
        
        $config = $app['config'];
        $connections = $config->get('database.connections') ?: [];
        $connections['default'] = $laravel_db;
        $connections['mysql'] = $laravel_db;
        $config->set('database.connections', $connections);
        $config->set('database.default', 'default');
    }
}

try {
    $desa = (array)\Illuminate\Support\Facades\DB::table('config')->first();
    echo "Desa loaded: " . $desa['nama_desa'] . "\n";

    echo "Testing setting('sebutan_desa')...\n";
    try {
        echo "Result: " . setting('sebutan_desa') . "\n";
    } catch (\Throwable $e) {
        echo "Failed setting(): " . $e->getMessage() . "\n";
    }

    echo "Testing favico_desa()...\n";
    try {
        echo "Result: " . favico_desa() . "\n";
    } catch (\Throwable $e) {
        echo "Failed favico_desa(): " . $e->getMessage() . "\n";
    }

    echo "Testing gambar_desa()...\n";
    try {
        echo "Result: " . gambar_desa($desa['logo']) . "\n";
    } catch (\Throwable $e) {
        echo "Failed gambar_desa(): " . $e->getMessage() . "\n";
    }

    // Setup view variables
    $view_file = FCPATH . 'resources/views/xosid/portal2.blade.php';
    
    $html = $app['view']->file($view_file, ['desa' => $desa])->render();
    echo "RENDER SUCCESS, LENGTH: " . strlen($html) . "\n";
    echo substr($html, 0, 500) . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
