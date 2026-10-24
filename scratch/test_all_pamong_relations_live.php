<?php
// scratch/test_all_pamong_relations_live.php

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
if (!function_exists('app')) {
    function app($abstract = null, array $parameters = []) {
        if (is_null($abstract)) {
            return \Illuminate\Container\Container::getInstance();
        }
        return \Illuminate\Container\Container::getInstance()->make($abstract, $parameters);
    }
}

// 1. Load autoloader and bootstrap index.php resolver
require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}
if (function_exists('xosid_register_pamong_and_penduduk_hooks')) {
    xosid_register_pamong_and_penduduk_hooks();
}

// 2. Fetch all pamong using the live Event Dispatcher and SQL drivers
$pamongs = \App\Models\Pamong::all();
echo "Total pamongs loaded: " . count($pamongs) . "\n\n";

$all_success = true;
foreach ($pamongs as $idx => $p) {
    echo "Pamong #$idx: ID=" . $p->pamong_id . " | Nama='" . $p->pamong_nama . "' | NIK='" . $p->pamong_nik . "' | id_pend='" . ($p->id_pend ?? 'NULL') . "'\n";
    try {
        $pend = $p->penduduk;
        echo "  -> Penduduk relation: " . ($pend ? "FOUND (Nama='" . $pend->nama . "', NIK='" . $pend->nik . "', ID=" . $pend->id . ")" : "NULL") . "\n";
        if (!$pend) {
            $all_success = false;
        } else {
            $map = $pend->map;
            echo "    -> Map relation: " . ($map ? "FOUND" : "NULL") . "\n";
            if (!$map) {
                $all_success = false;
            } else {
                $map->makeVisible('lat');
                echo "      -> makeVisible on map succeeded!\n";
            }
        }
    } catch (\Throwable $e) {
        $all_success = false;
        echo "  -> ERROR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

if ($all_success) {
    echo "SUCCESS: All pamong records successfully resolved their relations and map makeVisible checks!\n";
} else {
    echo "FAILED: Some pamong records failed to resolve relations or map makeVisible checks.\n";
}
