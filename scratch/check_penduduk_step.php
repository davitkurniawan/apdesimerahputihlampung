<?php
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

// Add logging to newFromBuilder
echo "A: Calling Penduduk::first()...\n";
flush();

// Do it step by step like Eloquent does internally
$instance = new \App\Models\Penduduk();
echo "B: new Penduduk OK\n";
flush();

$query = $instance->newQuery();
echo "C: newQuery OK\n";
flush();

$results = $query->limit(1)->get(['*']);
echo "D: query->get() returned " . count($results) . " models\n";
flush();

if (count($results) > 0) {
    $first = $results[0];
    echo "E: first model class=" . get_class($first) . " id=" . $first->id . "\n";
    flush();
    
    echo "F: nama=" . $first->nama . "\n";
    flush();
}

echo "\nDone!\n";
