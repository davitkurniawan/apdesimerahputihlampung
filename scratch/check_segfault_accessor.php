<?php
// Test: Isolate which Pamong accessor causes segfault
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

$raw = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
    ->where('jabatan_id', 1)
    ->limit(1)
    ->first();

echo "Raw data fetched. pamong_id=" . $raw->pamong_id . "\n";

$pamong = new \App\Models\Pamong();
foreach ((array)$raw as $k => $v) {
    $pamong->setAttribute($k, $v);
}
$pamong->exists = true;
$pamong->syncOriginal();

echo "Model constructed and filled. Now testing property accesses one by one...\n";

// Test each attribute access with flush
$attrs = array_keys((array)$raw);
foreach ($attrs as $attr) {
    echo "  Accessing \$pamong->$attr ... ";
    flush();
    try {
        $val = $pamong->$attr;
        echo "OK (=" . substr(var_export($val, true), 0, 40) . ")\n";
        flush();
    } catch (\Throwable $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
        flush();
    }
}

echo "\nNow testing Pamong-specific relation accessors...\n";
$relations = ['penduduk', 'jabatan', 'user', 'media_sosial_decoded'];
foreach ($relations as $rel) {
    echo "  Accessing \$pamong->$rel ... ";
    flush();
    try {
        $val = $pamong->$rel;
        echo "OK (type=" . gettype($val) . ")\n";
        flush();
    } catch (\Throwable $e) {
        echo "EXCEPTION: " . $e->getMessage() . "\n";
        flush();
    }
}

echo "\n=== Reflection: check Pamong accessors/getters ===\n";
$rc = new ReflectionClass('App\Models\Pamong');
$methods = $rc->getMethods();
foreach ($methods as $m) {
    $name = $m->getName();
    if ($m->getDeclaringClass()->getName() === 'App\Models\Pamong' && 
        (strpos($name, 'get') === 0 || strpos($name, 'Attribute') !== false || strpos($name, 'scope') === 0)) {
        echo "  " . $name . " (in " . $m->getDeclaringClass()->getName() . ")\n";
    }
}

echo "\n=== Done ===\n";
