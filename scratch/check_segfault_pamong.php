<?php
// Ultra-minimal segfault isolation test
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

// Disable ALL hooks
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

echo "=== Step 1: new Pamong() ===\n";
try {
    $p = new \App\Models\Pamong();
    echo "OK: new Pamong() succeeded. Class: " . get_class($p) . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Step 2: Raw DB query for pamong table ===\n";
try {
    $rows = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
        ->where('jabatan_id', 1)
        ->limit(1)
        ->get();
    echo "OK: DB::table query returned " . count($rows) . " rows\n";
    if (count($rows) > 0) {
        echo "First row: " . json_encode($rows[0], JSON_PRETTY_PRINT) . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Step 3: Pamong::query()->toSql() ===\n";
try {
    $sql = \App\Models\Pamong::query()->toSql();
    echo "SQL: $sql\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Step 4: Pamong query with get() ===\n";
try {
    echo "About to run Pamong::where('jabatan_id', 1)->get()...\n";
    $result = \App\Models\Pamong::where('jabatan_id', 1)->get();
    echo "OK: got " . count($result) . " results\n";
    foreach ($result as $r) {
        echo "  pamong_id=" . $r->pamong_id . " nama=" . $r->pamong_nama . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Step 5: Pamong first() ===\n";
try {
    echo "About to run Pamong::where('jabatan_id', 1)->first()...\n";
    $kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
    echo "OK: kades = " . ($kades ? "ID=" . $kades->pamong_id : "NULL") . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== All steps completed ===\n";
