<?php
// scratch/test_map_query_live.php

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost';

if (!defined('ENVIRONMENT'))
    define('ENVIRONMENT', 'production');
if (!defined('FCPATH'))
    define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
if (!defined('SELF'))
    define('SELF', 'index.php');
if (!defined('BASEPATH'))
    define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
if (!defined('APPPATH'))
    define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
if (!defined('VIEWPATH'))
    define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH'))
    define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH'))
    define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH'))
    define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL'))
    define('APP_URL', 'http://localhost:8080');
if (!defined('FORCE_HTTPS'))
    define('FORCE_HTTPS', false);
if (!defined('LOKASI_CONFIG_DESA'))
    define('LOKASI_CONFIG_DESA', 'desa/config/');

if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('identitas')) {
    function identitas() { return '1'; }
}
if (!function_exists('ci_auth')) {
    function ci_auth() { 
        return new class { 
            public $id = 1; 
            public $id_grup = 1;
            public function user() { return $this; } 
        }; 
    }
}
if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return dirname(__DIR__) . '/storage' . ($path ? '/' . $path : '');
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Force connection resolver
$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Read CI config database
if (file_exists(__DIR__ . '/../desa/config/database.php')) {
    require __DIR__ . '/../desa/config/database.php';
    if (isset($db['default'])) {
        $ci_db = $db['default'];
        $db_pass = $ci_db['password'] ?? 'password';
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
            'strict'    => false,
            'engine'    => null,
        ];
        $app['config']->set('database.connections.default', $laravel_db);
        $app['config']->set('database.connections.mysql', $laravel_db);
        $app['db']->purge('default');
        $app['db']->purge('mysql');
    }
}

echo "Current Laravel Connection Database: " . \Illuminate\Support\Facades\DB::connection()->getDatabaseName() . "\n";

echo "Querying Penduduk 33225...\n";
$pend = \App\Models\Penduduk::find(33225);
if ($pend) {
    echo "  -> Found Penduduk: " . $pend->nama . "\n";
    echo "  -> NIK: " . $pend->nik . "\n";
    echo "  -> id_kk: " . $pend->id_kk . "\n";
    echo "Querying PendudukMap for ID 33225...\n";
    $map = \App\Models\PendudukMap::where('id', 33225)->first();
    echo "  -> Result: " . ($map ? "FOUND (class=" . get_class($map) . ")" : "NULL") . "\n";
    if ($map) {
        var_dump($map->toArray());
        $map->makeVisible('lat');
        echo "  -> makeVisible succeeded!\n";
    }
} else {
    echo "  -> Penduduk 33225 NOT FOUND!\n";
}
