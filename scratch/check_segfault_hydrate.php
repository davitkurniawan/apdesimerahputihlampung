<?php
// Test: Pamong hydration segfault - use raw stdClass hydration instead
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

// Test: manually create a Pamong and fill it
echo "=== Test A: Manual newFromBuilder (simulating hydration) ===\n";
try {
    $raw = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
        ->where('jabatan_id', 1)
        ->limit(1)
        ->first();
    
    echo "Raw data fetched OK. pamong_id=" . $raw->pamong_id . "\n";
    
    echo "About to call Pamong::newFromBuilder()...\n";
    $pamong = new \App\Models\Pamong();
    
    // Instead of newFromBuilder, let's try fill/setAttribute step by step
    echo "Setting attributes one by one...\n";
    foreach ((array)$raw as $k => $v) {
        echo "  Setting $k = " . substr(var_export($v, true), 0, 50) . "\n";
        try {
            $pamong->setAttribute($k, $v);
        } catch (\Throwable $e) {
            echo "  ERROR on $k: " . $e->getMessage() . "\n";
        }
    }
    echo "All attributes set OK!\n";
    
    echo "Setting exists=true...\n";
    $pamong->exists = true;
    echo "exists set OK!\n";
    
    echo "Syncing original...\n";
    $pamong->syncOriginal();
    echo "syncOriginal OK!\n";
    
    echo "Firing retrieved event manually...\n";
    // This is what newFromBuilder does after setting attributes
    // $pamong->fireModelEvent('retrieved', false);
    echo "SKIPPING event fire to isolate...\n";
    
    echo "Accessing pamong_nama: " . $pamong->pamong_nama . "\n";
    echo "Accessing pamong_id: " . $pamong->pamong_id . "\n";
    
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test B: Check if Pamong has booted/boot/retrieved events ===\n";
try {
    $rc = new ReflectionClass('App\Models\Pamong');
    echo "Parent class: " . ($rc->getParentClass() ? $rc->getParentClass()->getName() : 'none') . "\n";
    
    $traits = $rc->getTraitNames();
    echo "Traits (" . count($traits) . "): " . implode(', ', $traits) . "\n";
    
    $methods = $rc->getMethods();
    echo "Methods:\n";
    foreach ($methods as $m) {
        if ($m->getDeclaringClass()->getName() === 'App\Models\Pamong') {
            echo "  " . $m->getName() . " (declared in Pamong)\n";
        }
    }
    
    echo "\nChecking for boot/booted/retrieved methods...\n";
    foreach (['boot', 'booted', 'retrieved', 'initializePamong', 'bootPamong'] as $method) {
        echo "  $method: " . ($rc->hasMethod($method) ? 'EXISTS (in ' . $rc->getMethod($method)->getDeclaringClass()->getName() . ')' : 'not found') . "\n";
    }
} catch (\Throwable $e) {
    echo "Reflection ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Test C: Check newFromBuilder override ===\n";
try {
    $rc = new ReflectionClass('App\Models\Pamong');
    if ($rc->hasMethod('newFromBuilder')) {
        $m = $rc->getMethod('newFromBuilder');
        echo "newFromBuilder declared in: " . $m->getDeclaringClass()->getName() . "\n";
        echo "File: " . $m->getFileName() . "\n";
        echo "Lines: " . $m->getStartLine() . "-" . $m->getEndLine() . "\n";
    } else {
        echo "No newFromBuilder method in Pamong (using parent)\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== All tests completed ===\n";
