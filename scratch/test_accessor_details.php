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
    $resident = \App\Models\Penduduk::find(17866);
    if ($resident) {
        echo "Initially:\n";
        echo "  map loaded: " . ($resident->relationLoaded('map') ? 'YES' : 'NO') . "\n";
        echo "  lokasi loaded: " . ($resident->relationLoaded('lokasi') ? 'YES' : 'NO') . "\n";
        echo "  lokasi value: " . ($resident->lokasi ? 'FOUND' : 'NULL') . "\n";

        // Let's set 'lokasi' relation to a mock object
        $mock = new class { public $is_mock = true; public function makeVisible($a) { echo "makeVisible called on Mock!\n"; } };
        $resident->setRelation('lokasi', $mock);
        
        echo "\nAfter setRelation('lokasi', mock):\n";
        echo "  lokasi loaded: " . ($resident->relationLoaded('lokasi') ? 'YES' : 'NO') . "\n";
        echo "  lokasi value class: " . (is_object($resident->lokasi) ? get_class($resident->lokasi) : 'N/A') . "\n";
        
        if (isset($resident->lokasi->is_mock)) {
            echo "  SUCCESS: Accessor respected the loaded relation!\n";
        } else {
            echo "  WARNING: Accessor IGNORED the loaded relation and re-evaluated/queried!\n";
        }
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
