<?php
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost:8080';

if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'production');
if (!defined('FCPATH')) define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
if (!defined('SELF')) define('SELF', 'index.php');
if (!defined('BASEPATH')) define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
if (!defined('APPPATH')) define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
if (!defined('DESAPATH')) define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH')) define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH')) define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL')) define('APP_URL', 'https://demo.xosid.id');
if (!defined('FORCE_HTTPS')) define('FORCE_HTTPS', true);
if (!defined('LOKASI_CONFIG_DESA')) define('LOKASI_CONFIG_DESA', 'desa/config/');

if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('identitas')) {
    function identitas() { return '1'; }
}

require_once __DIR__ . '/../vendor/autoload.php';
// Load xosid_emergency_autoload first as index.php does
if (file_exists(__DIR__ . '/../xosid_emergency_autoload.php')) {
    include_once __DIR__ . '/../xosid_emergency_autoload.php';
}
include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Setup connection resolver manually like index.php does
if (class_exists('Illuminate\Database\Eloquent\Model') && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
    $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
    if ($currentResolver && class_exists('XOSIDConnectionResolver') && !($currentResolver instanceof XOSIDConnectionResolver)) {
        $newResolver = new XOSIDConnectionResolver($currentResolver);
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
    }
}

try {
    echo "=== Simulating Pamong::kepalaDesa()->first() ===\n";
    $kades = \App\Models\Pamong::kepalaDesa()->first();
    echo "Kades Class: " . ($kades ? get_class($kades) : 'NULL') . "\n";
    if ($kades) {
        echo "Kades ID: {$kades->pamong_id} | Nama: {$kades->pamong_nama} | id_pend: " . ($kades->id_pend ?? 'NULL') . "\n";
        $pend = $kades->penduduk;
        echo "  -> Penduduk: " . ($pend ? "FOUND (ID={$pend->id}, Name='{$pend->nama}')" : "NULL") . "\n";
        if ($pend) {
            $map = $pend->map;
            echo "    -> Map: " . ($map ? "FOUND (ID={$map->id})" : "NULL") . "\n";
            if ($map) {
                $map->makeVisible('lat');
                echo "      -> makeVisible succeeded!\n";
            }
        }
    } else {
        echo "Kades not found!\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
