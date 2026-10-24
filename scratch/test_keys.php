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
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

$pamong = new \App\Models\Pamong();
echo "Key Name: " . $pamong->getKeyName() . "\n";
echo "isRelation('user')? " . ($pamong->isRelation('user') ? 'YES' : 'NO') . "\n";
echo "Relation SQL: " . $pamong->user()->toSql() . "\n";

$sekdes = \App\Models\Pamong::where('jabatan_id', 2)->first();
if ($sekdes) {
    echo "Sekdes Pamong Attributes:\n" . json_encode($sekdes->getAttributes(), JSON_PRETTY_PRINT) . "\n";
    echo "Sekdes User Relation Loaded? " . ($sekdes->relationLoaded('user') ? 'YES' : 'NO') . "\n";
    $user = $sekdes->user;
    if ($user) {
        echo "Sekdes User Class: " . get_class($user) . "\n";
        echo "Sekdes User Attributes: " . json_encode($user->getAttributes(), JSON_PRETTY_PRINT) . "\n";
        echo "Sekdes User pamong_id value: " . var_export($user->pamong_id, true) . "\n";
    } else {
        echo "Sekdes User is NULL!\n";
    }
} else {
    echo "Sekdes not found!\n";
}

$kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
if ($kades) {
    echo "\nKades Pamong Attributes:\n" . json_encode($kades->getAttributes(), JSON_PRETTY_PRINT) . "\n";
    echo "Kades User Relation Loaded? " . ($kades->relationLoaded('user') ? 'YES' : 'NO') . "\n";
    $user = $kades->user;
    if ($user) {
        echo "Kades User Class: " . get_class($user) . "\n";
        echo "Kades User Attributes: " . json_encode($user->getAttributes(), JSON_PRETTY_PRINT) . "\n";
        echo "Kades User pamong_id value: " . var_export($user->pamong_id, true) . "\n";
    } else {
        echo "Kades User is NULL!\n";
    }
} else {
    echo "Kades not found!\n";
}
