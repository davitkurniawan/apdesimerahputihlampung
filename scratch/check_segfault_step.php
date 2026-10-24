<?php
// Extremely minimal: Does Pamong newInstance() segfault?
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

echo "Step 1: new Pamong()\n";
$p = new \App\Models\Pamong();
echo "Step 1 OK\n";

echo "Step 2: newInstance\n";
$p2 = $p->newInstance([], true);
echo "Step 2 OK, class=" . get_class($p2) . "\n";

echo "Step 3: multiple newInstance calls\n";
for ($i = 0; $i < 5; $i++) {
    echo "  $i: ";
    flush();
    $pi = $p->newInstance([], true);
    echo "OK\n";
}

echo "Step 4: setRawAttributes on newInstance\n";
$raw = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
    ->where('jabatan_id', 1)
    ->limit(1)
    ->first();
$p3 = $p->newInstance([], true);
$p3->setRawAttributes((array)$raw, true);
echo "Step 4 OK\n";

echo "Step 5: Pamong::hydrate\n";
flush();
$rows = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
    ->where('jabatan_id', 1)
    ->limit(1)
    ->get()
    ->all();

echo "  Got " . count($rows) . " raw rows\n";

// Manually do what getModels does
$models = [];
foreach ($rows as $row) {
    echo "  Creating model for pamong_id=" . $row->pamong_id . "...\n";
    flush();
    $model = $p->newFromBuilder($row);
    echo "  newFromBuilder OK\n";
    flush();
    $models[] = $model;
}
echo "Step 5 OK with " . count($models) . " models\n";

echo "Step 6: access pamong_nama on hydrated model\n";
flush();
$nama = $models[0]->pamong_nama;
echo "Step 6 OK: pamong_nama = " . var_export($nama, true) . "\n";

echo "\n=== Now try Pamong::where()->get() ===\n";
flush();
$result = \App\Models\Pamong::where('jabatan_id', 1)->get();
echo "GOT " . count($result) . " results\n";

echo "DONE!\n";
