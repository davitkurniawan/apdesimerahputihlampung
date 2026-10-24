<?php
// Test: Check what eagerLoad is set on Pamong query
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

\Illuminate\Database\Eloquent\Model::unsetEventDispatcher();

echo "=== Check Pamong query builder state ===\n";
$builder = \App\Models\Pamong::where('jabatan_id', 1);

// Check eager loads
$rc = new ReflectionClass(get_class($builder));
if ($rc->hasProperty('eagerLoad')) {
    $prop = $rc->getProperty('eagerLoad');
    $prop->setAccessible(true);
    $eagerLoads = $prop->getValue($builder);
    echo "Eager loads (" . count($eagerLoads) . "):\n";
    foreach ($eagerLoads as $name => $cb) {
        echo "  $name\n";
    }
} else {
    echo "No eagerLoad property\n";
}

// Check scopes
echo "\nApplied scopes:\n";
$scoped = $builder->applyScopes();
if ($rc->hasProperty('scopes')) {
    $prop = $rc->getProperty('scopes');
    $prop->setAccessible(true);
    $scopes = $prop->getValue($builder);
    foreach ($scopes as $name => $scope) {
        echo "  $name: " . (is_object($scope) ? get_class($scope) : gettype($scope)) . "\n";
    }
}

// Check with (default eager loads set in model via $with)
echo "\nModel \$with property:\n";
$pamongModel = new \App\Models\Pamong();
$rp = new ReflectionClass(get_class($pamongModel));
if ($rp->hasProperty('with')) {
    $withProp = $rp->getProperty('with');
    $withProp->setAccessible(true);
    $withVal = $withProp->getValue($pamongModel);
    echo "  \$with = " . var_export($withVal, true) . "\n";
} else {
    echo "  No \$with property\n";
}

echo "\nModel \$appends property:\n";
if ($rp->hasProperty('appends')) {
    $appendsProp = $rp->getProperty('appends');
    $appendsProp->setAccessible(true);
    $appendsVal = $appendsProp->getValue($pamongModel);
    echo "  \$appends = " . var_export($appendsVal, true) . "\n";
} else {
    echo "  No \$appends property\n";
}

echo "\n=== Step 1: getModels() ===\n";
$builder2 = \App\Models\Pamong::where('jabatan_id', 1);
$scoped2 = $builder2->applyScopes();

echo "Running base query...\n";
flush();
$rawResults = $scoped2->getQuery()->get(['*'])->all();
echo "Got " . count($rawResults) . " raw results\n";

echo "Running hydrate...\n";
flush();
$hydrated = $scoped2->getModel()->hydrate($rawResults)->all();
echo "Hydrated " . count($hydrated) . " models\n";

echo "Checking eagerLoadRelations...\n";
flush();
// Check eagerLoad on the scoped builder
$rc2 = new ReflectionClass(get_class($scoped2));
$ep2 = $rc2->getProperty('eagerLoad');
$ep2->setAccessible(true);
$el2 = $ep2->getValue($scoped2);
echo "Eager loads on scoped builder: " . count($el2) . "\n";
foreach ($el2 as $name => $cb) {
    echo "  $name\n";
}

if (count($el2) > 0 && count($hydrated) > 0) {
    echo "\nEager loading relations...\n";
    flush();
    foreach ($el2 as $name => $constraint) {
        echo "  Loading relation '$name'...\n";
        flush();
        try {
            $hydrated = $scoped2->eagerLoadRelation($hydrated, $name, $constraint);
            echo "  '$name' loaded OK\n";
        } catch (\Throwable $e) {
            echo "  '$name' FAILED: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n=== Now try full get() ===\n";
flush();
$result = \App\Models\Pamong::where('jabatan_id', 1)->get();
echo "GOT " . count($result) . " results!\n";

echo "\nDONE!\n";
