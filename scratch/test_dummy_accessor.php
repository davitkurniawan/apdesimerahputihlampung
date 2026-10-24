<?php
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost';

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
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
            public $grup = 1;
            public $batasi_wilayah = 0;
            public function user() { return $this; } 
            public function check() { return true; }
        }; 
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

try {
    echo "=== testing dummy resident without saving to DB ===\n";
    $pend = new \App\Models\Penduduk();
    $pend->id = 999999;
    $pend->config_id = 1;
    $pend->nama = 'Dummy Pamong';
    $pend->nik = '';
    $pend->setAttribute('id', 999999);
    $pend->setAttribute('config_id', 1);
    $pend->setAttribute('nama', 'Dummy Pamong');
    $pend->setAttribute('nik', '');

    $map = new \App\Models\PendudukMap();
    $map->id = 999999;
    $map->config_id = 1;
    $map->lat = null;
    $map->lng = null;
    $map->setAttribute('id', 999999);
    $map->setAttribute('config_id', 1);
    $map->setAttribute('lat', null);
    $map->setAttribute('lng', null);

    $pend->setRelation('map', $map);
    $pend->setRelation('lokasi', $map);

    echo "map relation: " . ($pend->relationLoaded('map') ? 'LOADED' : 'NOT LOADED') . " | value: " . ($pend->map ? 'FOUND' : 'NULL') . "\n";
    echo "lokasi relation: " . ($pend->relationLoaded('lokasi') ? 'LOADED' : 'NOT LOADED') . " | value: " . ($pend->lokasi ? 'FOUND' : 'NULL') . "\n";

    if ($pend->lokasi) {
        $pend->lokasi->makeVisible('lat');
        echo "SUCCESS: makeVisible on lokasi succeeded!\n";
    } else {
        echo "FAILED: lokasi is null!\n";
    }

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
