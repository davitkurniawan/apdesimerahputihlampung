<?php
// Test: Penduduk model hydration - does it segfault?
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

echo "1. Penduduk::where('id', 17866)->first()...\n";
flush();
$p = \App\Models\Penduduk::where('id', 17866)->first();
echo "   OK: nama=" . ($p ? $p->nama : 'NULL') . "\n";

echo "2. Penduduk::find(17866)...\n";
flush();
$p2 = \App\Models\Penduduk::find(17866);
echo "   OK: nama=" . ($p2 ? $p2->nama : 'NULL') . "\n";

echo "3. Access \$p->wilayah...\n";
flush();
try {
    $wil = $p->wilayah;
    echo "   OK: wilayah=" . ($wil ? (get_class($wil) . " " . ($wil->dusun ?? 'N/A')) : 'NULL') . "\n";
} catch (\Throwable $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "4. Access \$p->keluarga...\n";
flush();
try {
    $kel = $p->keluarga;
    echo "   OK: keluarga=" . ($kel ? get_class($kel) : 'NULL') . "\n";
} catch (\Throwable $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "5. Penduduk::with('wilayah')->first()...\n";
flush();
$p3 = \App\Models\Penduduk::with('wilayah')->where('id', 17866)->first();
echo "   OK: nama=" . ($p3 ? $p3->nama : 'NULL') . "\n";

echo "6. Penduduk \$appends check...\n";
$rc = new ReflectionClass('App\Models\Penduduk');
$appendsProp = $rc->getProperty('appends');
$appendsProp->setAccessible(true);
$appends = $appendsProp->getValue($p);
echo "   \$appends = " . json_encode($appends) . "\n";

echo "7. Check if Penduduk has accessor that lazy loads...\n";
echo "   Methods containing 'get' and 'Attribute' in Penduduk:\n";
$methods = $rc->getMethods();
$pendAccessors = [];
foreach ($methods as $m) {
    if ($m->getDeclaringClass()->getName() === 'App\Models\Penduduk' && 
        preg_match('/^get(.+)Attribute$/', $m->getName(), $match)) {
        $pendAccessors[] = $m->getName();
    }
}
foreach ($pendAccessors as $acc) {
    echo "     $acc\n";
}

echo "\nAll done!\n";
