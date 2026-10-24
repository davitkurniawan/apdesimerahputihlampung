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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// Ensure database connection is Bondo
$config = $app['config'];
$connections = $config->get('database.connections') ?: [];
$laravel_db = [
    'driver'    => 'mysql',
    'host'      => 'db',
    'port'      => 3306,
    'database'  => 'xosid_tenant_bondo-bangsri',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
    'strict'    => false,
    'engine'    => null,
];
foreach (['default', 'mysql', 'opensid', 'tenant', ''] as $name) {
    $config->set("database.connections.{$name}", $laravel_db);
}
$config->set('database.default', 'default');

\Illuminate\Support\Facades\DB::purge();
\Illuminate\Support\Facades\DB::reconnect();

$configModel = new \App\Models\Config();
echo "Config Class: " . get_class($configModel) . "\n";
echo "Methods:\n";
$methods = get_class_methods($configModel);
foreach ($methods as $m) {
    if (in_array($m, ['pamong', 'user', 'penduduk', 'kades', 'sekdes'])) {
        echo "Relationship method found: $m\n";
    }
}

$firstConfig = \App\Models\Config::first();
if ($firstConfig) {
    echo "First config attributes: " . json_encode($firstConfig->getAttributes()) . "\n";
    echo "pamong relation: " . ($firstConfig->pamong ? get_class($firstConfig->pamong) : "NULL") . "\n";
    if ($firstConfig->pamong) {
        echo "pamong attributes: " . json_encode($firstConfig->pamong->getAttributes()) . "\n";
    }
}
