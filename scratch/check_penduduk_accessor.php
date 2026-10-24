<?php
// Find which Penduduk accessor/property access causes segfault
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

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Manually create Penduduk without going through Eloquent get()
echo "Step 1: Raw DB fetch...\n";
$raw = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
    ->where('id', 17866)
    ->first();
echo "OK: nama=" . $raw->nama . "\n";

echo "Step 2: Manual hydration...\n";
$pend = new \App\Models\Penduduk();
$pend->setRawAttributes((array)$raw, true);
$pend->exists = true;
echo "OK\n";

echo "Step 3: Access basic attributes...\n";
$basics = ['id', 'nama', 'nik', 'config_id', 'id_kk', 'sex', 'agama_id', 'pendidikan_kk_id', 'pendidikan_sedang_id', 'pekerjaan_id'];
foreach ($basics as $attr) {
    echo "  $attr = ";
    flush();
    echo var_export($pend->getAttribute($attr), true) . "\n";
}

echo "\nStep 4: Access Penduduk accessor properties one by one...\n";
$appendsList = ['pendidikan','pendidikan_kk','pendidikan_sedang','agama','warganegara','golongan_darah','umur','usia','cacat','cara_kb','alamat_wilayah','alamat_wilayah_kartu_keluarga','nama_asuransi','jml_anak','lokasi','status_perkawinan','jenis_kelamin','jenis_kelamin_inisial','jenis_kelamin_id','penduduk_hubungan','penduduk_status','pekerjaan','sakit_menahun','asuransi','bahasa'];

foreach ($appendsList as $prop) {
    echo "  $prop ... ";
    flush();
    try {
        $val = $pend->$prop;
        echo "OK (" . substr(var_export($val, true), 0, 50) . ")\n";
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\nStep 5: Access relations...\n";
$rels = ['wilayah', 'keluarga', 'rtm', 'map', 'lokasi'];
foreach ($rels as $rel) {
    echo "  $rel ... ";
    flush();
    try {
        $val = $pend->$rel;
        echo "OK (" . (is_object($val) ? get_class($val) : (is_null($val) ? 'NULL' : gettype($val))) . ")\n";
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\nDone!\n";
