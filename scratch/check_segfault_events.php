<?php
// Test: Check what Pamong::boot() does and whether it registers retrieved events
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

echo "=== Check event dispatcher for Pamong events ===\n";
$disp = \Illuminate\Database\Eloquent\Model::getEventDispatcher();
echo "Dispatcher class: " . get_class($disp) . "\n";

// Use reflection to get registered listeners
$rc = new ReflectionClass(get_class($disp));
if ($rc->hasProperty('listeners')) {
    $prop = $rc->getProperty('listeners');
    $prop->setAccessible(true);
    $listeners = $prop->getValue($disp);
    
    echo "\nAll event keys containing 'Pamong':\n";
    foreach ($listeners as $key => $cbs) {
        if (stripos($key, 'Pamong') !== false || stripos($key, 'pamong') !== false) {
            echo "  $key: " . count($cbs) . " listener(s)\n";
        }
    }
    
    echo "\nAll event keys containing 'retrieved':\n";
    foreach ($listeners as $key => $cbs) {
        if (stripos($key, 'retrieved') !== false) {
            echo "  $key: " . count($cbs) . " listener(s)\n";
            foreach ($cbs as $i => $cb) {
                if (is_string($cb)) {
                    echo "    [$i] string: $cb\n";
                } elseif ($cb instanceof Closure) {
                    $rf = new ReflectionFunction($cb);
                    echo "    [$i] Closure in " . $rf->getFileName() . ":" . $rf->getStartLine() . "\n";
                } elseif (is_array($cb)) {
                    echo "    [$i] array: " . (is_object($cb[0]) ? get_class($cb[0]) : $cb[0]) . "::" . $cb[1] . "\n";
                } else {
                    echo "    [$i] " . gettype($cb) . "\n";
                }
            }
        }
    }

    echo "\nAll event keys containing 'wildcard' or '*':\n";
    if ($rc->hasProperty('wildcards')) {
        $wprop = $rc->getProperty('wildcards');
        $wprop->setAccessible(true);
        $wildcards = $wprop->getValue($disp);
        foreach ($wildcards as $key => $cbs) {
            if (stripos($key, 'retrieved') !== false) {
                echo "  WILDCARD $key: " . count($cbs) . " listener(s)\n";
                foreach ($cbs as $i => $cb) {
                    if ($cb instanceof Closure) {
                        $rf = new ReflectionFunction($cb);
                        echo "    [$i] Closure in " . $rf->getFileName() . ":" . $rf->getStartLine() . "\n";
                    } else {
                        echo "    [$i] " . gettype($cb) . "\n";
                    }
                }
            }
        }
    }
} else {
    echo "No 'listeners' property found\n";
}

echo "\n=== Check Pamong global scopes ===\n";
$pamong = new \App\Models\Pamong();
$globalScopes = $pamong->getGlobalScopes();
echo "Global scopes (" . count($globalScopes) . "):\n";
foreach ($globalScopes as $name => $scope) {
    echo "  $name: " . (is_object($scope) ? get_class($scope) : gettype($scope)) . "\n";
}

echo "\n=== Checking if Pamong boot registered retrieved observer ===\n";
// Check if there's a retrieved event for Pamong
$eventName = 'eloquent.retrieved: App\Models\Pamong';
if ($disp->hasListeners($eventName)) {
    echo "YES: '$eventName' has listeners!\n";
    // This could be the source of the segfault
} else {
    echo "NO: '$eventName' has no listeners.\n";
}

echo "\n=== Step: Try Pamong get() with event dispatcher completely removed ===\n";
echo "Removing event dispatcher...\n";
\Illuminate\Database\Eloquent\Model::unsetEventDispatcher();
echo "Event dispatcher removed. Now trying Pamong::where()->get()...\n";
flush();
try {
    $result = \App\Models\Pamong::where('jabatan_id', 1)->get();
    echo "SUCCESS: Got " . count($result) . " results\n";
    if (count($result) > 0) {
        echo "  First: pamong_id=" . $result[0]->pamong_id . "\n";
        // Try accessing pamong_nama
        echo "  Accessing pamong_nama...\n";
        $nama = $result[0]->pamong_nama;
        echo "  pamong_nama = " . var_export($nama, true) . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Done ===\n";
