<?php
// scratch/test_pamong_attributes.php

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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('kades')) {
    function kades() {
        return \App\Models\RefJabatan::where('id', 1)->first() ?: new class { public $id = 1; public $nama = 'Kepala Desa'; };
    }
}
if (!function_exists('sekdes')) {
    function sekdes() {
        return \App\Models\RefJabatan::where('id', 2)->first() ?: new class { public $id = 2; };
    }
}
if (!function_exists('ci_auth')) {
    function ci_auth() {
        return new class {
            public $id = 1;
            public $username = 'admin';
            public $id_grup = 1;
            public $grup = 1;
            public $batasi_wilayah = 0;
            public function user() { return $this; }
            public function check() { return true; }
        };
    }
}
if (!function_exists('usia')) {
    function usia($tgl_lahir) { return 30; }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Force connection resolver
$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
    $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
}
if ($currentResolver && !($currentResolver instanceof XOSIDConnectionResolver)) {
    $newResolver = new XOSIDConnectionResolver($currentResolver);
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
}

// Inject connection
$config = $app['config'];
$connections = $config->get('database.connections') ?: [];
$laravel_db = [
    'driver'    => 'mysql',
    'host'      => 'db',
    'port'      => 3306,
    'database' => 'xosid_tenant_bondo-bangsri',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
    'strict'    => false,
    'engine'    => null,
];
$connections['default'] = $laravel_db;
$connections['mysql'] = $laravel_db;
$config->set('database.connections', $connections);
$config->set('database.default', 'default');

$cfg = \App\Models\Config::first();
if ($cfg) {
    echo "Config relations:\n";
    print_r(array_keys($cfg->getRelations()));
    
    // Check if map or lokasi methods exist on Config class
    foreach (['map', 'lokasi', 'pamong'] as $m) {
        if (method_exists($cfg, $m)) {
            echo "Method '$m' exists on Config class!\n";
            try {
                $val = $cfg->$m;
                echo "  -> Relation value: " . ($val ? "FOUND (class=" . get_class($val) . ")" : "NULL") . "\n";
            } catch (\Throwable $e) {
                echo "  -> Error loading relation '$m': " . $e->getMessage() . "\n";
            }
        } else {
            echo "Method '$m' does not exist on Config class!\n";
        }
    }
} else {
    echo "Config not found.\n";
}
