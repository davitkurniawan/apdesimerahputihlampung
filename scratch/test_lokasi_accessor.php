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
    // Let's find a resident (e.g. 17866 or 33225)
    $resident = \App\Models\Penduduk::find(17866);
    echo "Resident: " . ($resident ? $resident->nama : 'NULL') . "\n";
    if ($resident) {
        echo "Raw map relation loaded status: " . ($resident->relationLoaded('map') ? 'YES' : 'NO') . "\n";
        echo "Raw map relation value: " . ($resident->map ? 'FOUND' : 'NULL') . "\n";
        echo "Raw lokasi attribute: " . ($resident->lokasi ? 'FOUND' : 'NULL') . "\n";
        
        // Let's unset the map relation and see what lokasi returns
        $resident->unsetRelation('map');
        echo "After unsetting map relation:\n";
        echo "  map relation Loaded: " . ($resident->relationLoaded('map') ? 'YES' : 'NO') . "\n";
        echo "  lokasi attribute: " . ($resident->lokasi ? 'FOUND' : 'NULL') . "\n";
        
        // Let's set the map relation to null
        $resident->setRelation('map', null);
        echo "After setting map relation to null:\n";
        echo "  map relation: " . ($resident->map ? 'FOUND' : 'NULL') . "\n";
        echo "  lokasi attribute: " . ($resident->lokasi ? 'FOUND' : 'NULL') . "\n";
        
        // Let's set a dummy map relation
        $map = new \App\Models\PendudukMap();
        $map->id = $resident->id;
        $map->lat = null;
        $map->lng = null;
        $resident->setRelation('map', $map);
        echo "After setting dummy map relation:\n";
        echo "  map relation: " . ($resident->map ? 'FOUND' : 'NULL') . "\n";
        echo "  lokasi attribute: " . ($resident->lokasi ? 'FOUND' : 'NULL') . "\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
