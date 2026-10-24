<?php
// scratch/inspect_user_query.php

define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('SELF', 'index.php');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once FCPATH . 'vendor/autoload.php';
require_once FCPATH . 'xosid_emergency_autoload.php';
require_once FCPATH . 'xosid_database_helpers.php';

$app = require_once FCPATH . 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Force connection resolver
$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
    $currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
}
if ($currentResolver && !($currentResolver instanceof XOSIDConnectionResolver)) {
    $newResolver = new XOSIDConnectionResolver($currentResolver);
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($newResolver);
}

// Inject connection details
$config = $app['config'];
$connections = $config->get('database.connections') ?: [];
$laravel_db = [
    'driver'    => 'mysql',
    'host'      => 'db',
    'port'      => 3306,
    'database' => 'xosid_tenant_bondo-bangsri',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
    'strict'    => false,
    'engine'    => null,
];
$connections['default'] = $laravel_db;
$connections['mysql'] = $laravel_db;
$config->set('database.connections', $connections);
$config->set('database.default', 'default');

\Illuminate\Support\Facades\DB::enableQueryLog();

// Dynamically add ForceConfigIdOneScope to App\Models\Config
if (class_exists('App\Models\Config')) {
    \App\Models\Config::addGlobalScope('force_single_row', function($builder) {
        $query = $builder->getQuery();
        $query->wheres = [];
        $query->setBindings([], 'where');
        $builder->where('config.id', 1);
    });
}

echo "--- 1. Querying Config::find(1) ---\n";
try {
    $c1 = \App\Models\Config::find(1);
    echo "Config(1): " . ($c1 ? "Found (ID={$c1->id}, nama_desa='{$c1->nama_desa}')" : "NULL") . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n--- 2. Querying Config::find(10) ---\n";
try {
    $c10 = \App\Models\Config::find(10);
    echo "Config(10): " . ($c10 ? "Found (ID={$c10->id}, nama_desa='{$c10->nama_desa}')" : "NULL") . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n--- Executed SQL Queries ---\n";
print_r(\Illuminate\Support\Facades\DB::getQueryLog());
