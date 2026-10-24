<?php
// Check $with property on all models
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

echo "=== Checking \$with on all models ===\n";
$modelDir = '/var/www/html/app/Models/';
$files = glob($modelDir . '*.php');
sort($files);

foreach ($files as $file) {
    $basename = basename($file, '.php');
    $className = 'App\\Models\\' . $basename;
    
    if (!class_exists($className)) continue;
    
    try {
        $rc = new ReflectionClass($className);
        if ($rc->isAbstract()) continue;
        
        if ($rc->hasProperty('with')) {
            $prop = $rc->getProperty('with');
            $prop->setAccessible(true);
            $instance = $rc->newInstanceWithoutConstructor();
            $withVal = $prop->getValue($instance);
            if (!empty($withVal)) {
                echo "  $className: \$with = " . json_encode($withVal) . "\n";
            }
        }
    } catch (\Throwable $e) {
        // Skip
    }
}

echo "\n=== Checking \$appends on key models ===\n";
$keyModels = ['Config', 'Pamong', 'Penduduk', 'User'];
foreach ($keyModels as $name) {
    $className = 'App\\Models\\' . $name;
    if (!class_exists($className)) continue;
    try {
        $rc = new ReflectionClass($className);
        if ($rc->hasProperty('appends')) {
            $prop = $rc->getProperty('appends');
            $prop->setAccessible(true);
            $instance = $rc->newInstanceWithoutConstructor();
            $appendsVal = $prop->getValue($instance);
            echo "  $className: \$appends = " . json_encode($appendsVal) . "\n";
        }
    } catch (\Throwable $e) {
        echo "  $className: ERROR " . $e->getMessage() . "\n";
    }
}

echo "\n=== Simulate identitas_desa flow ===\n";
echo "1. Config::first()...\n";
$desa = \App\Models\Config::first();
echo "   OK: " . $desa->nama_desa . "\n";

echo "2. \$desa->pamong (kades)...\n";
$kades = $desa->pamong;
echo "   OK: " . ($kades ? $kades->pamong_nama : "NULL") . "\n";

echo "3. \$desa->sekdes...\n";
$sekdes = $desa->sekdes;
echo "   OK: " . ($sekdes ? $sekdes->pamong_nama : "NULL") . "\n";

echo "4. Pamong::aktif()->get()...\n";
try {
    $pamongList = \App\Models\Pamong::where('pamong_status', 1)->get();
    echo "   OK: " . count($pamongList) . " active pamong\n";
    foreach ($pamongList as $p) {
        echo "     ID=" . $p->pamong_id . " nama=" . $p->pamong_nama . "\n";
    }
} catch (\Throwable $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "5. SettingAplikasi query...\n";
try {
    $settings = \Illuminate\Support\Facades\DB::table('setting_aplikasi')
        ->where('config_id', 1)
        ->whereIn('key', ['sebutan_desa', 'sebutan_dusun'])
        ->get();
    echo "   OK: " . count($settings) . " settings\n";
} catch (\Throwable $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
