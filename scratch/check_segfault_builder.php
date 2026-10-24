<?php
// Check if Pamong overrides newFromBuilder
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

$GLOBALS['xosid_hooks_registered'] = true;
$GLOBALS['xosid_in_retrieved_callback'] = true;

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

echo "=== Checking method resolution for key methods ===\n";
$rc = new ReflectionClass('App\Models\Pamong');
$methods_to_check = ['newFromBuilder', 'newInstance', 'newModelQuery', 'newQuery', 
                      'setRawAttributes', 'getAttribute', 'fireModelEvent', 'boot',
                      'newBaseQueryBuilder'];

foreach ($methods_to_check as $method) {
    if ($rc->hasMethod($method)) {
        $m = $rc->getMethod($method);
        echo "  $method: " . $m->getDeclaringClass()->getName();
        $fn = $m->getFileName();
        if ($fn) {
            echo " (" . basename($fn) . ":" . $m->getStartLine() . ")";
        } else {
            echo " (IonCube-encoded, no file info)";
        }
        echo "\n";
    } else {
        echo "  $method: NOT FOUND\n";
    }
}

echo "\n=== Checking Pamong parent classes ===\n";
$class = $rc;
while ($class) {
    echo "  " . $class->getName();
    $fn = $class->getFileName();
    if ($fn) echo " (" . basename($fn) . ")";
    echo "\n";
    $class = $class->getParentClass();
}

echo "\n=== Checking newBaseQueryBuilder ===\n";
// Pamong has its own newBaseQueryBuilder! This might be the issue
$m = $rc->getMethod('newBaseQueryBuilder');
echo "Declared in: " . $m->getDeclaringClass()->getName() . "\n";
$fn = $m->getFileName();
echo "File: " . ($fn ?: 'IonCube-encoded') . "\n";

echo "\n=== Attempting hydration with tracing ===\n";
// Turn on IonCube tracer if possible
$raw = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
    ->where('jabatan_id', 1)
    ->limit(1)
    ->first();

echo "Got raw data. Now manually calling newFromBuilder on base Model...\n";

// Call parent's newFromBuilder directly
$pamong = new \App\Models\Pamong();
echo "A: Created empty Pamong\n";
$instance = $pamong->newInstance([], true);
echo "B: newInstance created, class=" . get_class($instance) . "\n";
$instance->setRawAttributes((array) $raw, true);
echo "C: setRawAttributes done\n";
$instance->setConnection($pamong->getConnectionName());
echo "D: setConnection done\n";

// Pre-load penduduk
echo "E: About to pre-load penduduk...\n";
$pendRow = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
    ->where('id', $instance->id_pend)
    ->first();
if ($pendRow) {
    $pend = (new \App\Models\Penduduk())->newInstance([], true);
    $pend->setRawAttributes((array)$pendRow, true);
    
    if (class_exists('App\Models\PendudukMap')) {
        $map = new \App\Models\PendudukMap();
        $map->id = $pend->id;
        $map->config_id = $pend->config_id ?? 1;
        $pend->setRelation('map', $map);
        $pend->setRelation('lokasi', $map);
    }
    
    $instance->setRelation('penduduk', $pend);
    echo "F: penduduk relation pre-loaded (nama=" . $pend->nama . ")\n";
}

echo "G: About to fire retrieved event...\n";
$instance->fireModelEvent('retrieved', false);
echo "H: Event fired OK\n";

echo "I: Accessing pamong_nama...\n";
$nama = $instance->pamong_nama;
echo "J: pamong_nama = " . var_export($nama, true) . "\n";

echo "\n=== Done ===\n";
