<?php
// Test: isolate boot() crash
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

// Test: Is the Eloquent query hydration process the actual point of crash?
// Let's use the query directly and hydrate manually

echo "=== Step 1: Run raw SQL via Pamong's query builder ===\n";
$query = \App\Models\Pamong::where('jabatan_id', 1)->toBase();
echo "Query SQL: " . $query->toSql() . "\n";
$rows = $query->get();
echo "Raw rows: " . count($rows) . "\n";

echo "\n=== Step 2: Manually hydrate each row ===\n";
$pamongInstance = new \App\Models\Pamong();
foreach ($rows as $i => $row) {
    echo "  Hydrating row $i (pamong_id=" . $row->pamong_id . ")...\n";
    flush();
    
    // Pre-load penduduk before newFromBuilder
    $GLOBALS['xosid_in_newFromBuilder'] = true;  // prevent re-entrancy
    
    $model = $pamongInstance->newInstance([], true);
    $model->setRawAttributes((array) $row, true);
    $model->setConnection($pamongInstance->getConnectionName());
    
    // Pre-load penduduk
    if (!empty($row->id_pend)) {
        $pendRow = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
            ->where('id', $row->id_pend)
            ->first();
        if ($pendRow) {
            $pend = new \App\Models\Penduduk();
            $pend->setRawAttributes((array) $pendRow, true);
            $pend->exists = true;
            
            if (class_exists('App\Models\PendudukMap')) {
                $map = new \App\Models\PendudukMap();
                $map->id = $pend->id;
                $map->config_id = $pend->config_id ?? 1;
                $map->exists = true;
                $pend->setRelation('map', $map);
                $pend->setRelation('lokasi', $map);
            }
            
            $model->setRelation('penduduk', $pend);
            echo "    penduduk preloaded: " . $pend->nama . "\n";
        }
    }
    
    unset($GLOBALS['xosid_in_newFromBuilder']);
    $model->exists = true;
    
    echo "    Accessing pamong_nama...\n";
    flush();
    $nama = $model->pamong_nama;
    echo "    pamong_nama = " . var_export($nama, true) . "\n";
    echo "    ROW $i HYDRATED OK!\n";
}

echo "\n=== Step 3: Now try the original Eloquent get() ===\n";
echo "About to call Pamong::where('jabatan_id', 1)->get()...\n";
flush();
$result = \App\Models\Pamong::where('jabatan_id', 1)->get();
echo "GOT " . count($result) . " results!\n";

echo "\n=== All done ===\n";
