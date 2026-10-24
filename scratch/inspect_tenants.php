<?php
// scratch/inspect_tenants.php
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

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function inspect_tenant($host) {
    global $app;
    $_SERVER['HTTP_HOST'] = $host;
    
    $tenant_name = ($host === 'bondo-bangsri.localhost:8080') ? 'bondo-bangsri' : 'bumidaya';
    $db_name = 'xosid_tenant_' . $tenant_name;
    
    if (isset($app['config'])) {
        $config = $app['config'];
        $connections = $config->get('database.connections') ?: [];
        $laravel_db = [
            'driver'    => 'mysql',
            'host'      => 'db',
            'port'      => 3306,
            'database'  => $db_name,
            'username'  => 'root',
            'password'  => 'password',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix'    => '',
            'strict'    => false,
            'engine'    => null,
        ];
        
        foreach (['default', 'mysql', 'opensid', 'tenant', ''] as $name) {
            $connections[$name] = $laravel_db;
            $config->set("database.connections.{$name}", $laravel_db);
        }
        $config->set('database.connections', $connections);
        $config->set('database.default', 'default');
    }

    // Reset connection to make sure it reconnects to the correct tenant database
    \Illuminate\Support\Facades\DB::purge();
    \Illuminate\Support\Facades\DB::reconnect();
    
    // Explicitly set connection resolver for Eloquent
    if (isset($app['db'])) {
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
    }
    
    echo "========================================\n";
    echo "HOST: $host\n";
    echo "Database: " . \Illuminate\Support\Facades\DB::getDatabaseName() . "\n";
    
    $config = \App\Models\Config::first();
    echo "Config ID: " . ($config ? $config->id : 'NULL') . "\n";
    echo "Config Nama Desa: " . ($config ? $config->nama_desa : 'NULL') . "\n";
    
    // Kades
    $kades = \App\Models\Pamong::where('jabatan_id', 1)->first();
    if ($kades) {
        echo "Kades Pamong ID: " . $kades->pamong_id . " | Nama: " . $kades->pamong_nama . " | id_pend: " . $kades->id_pend . "\n";
        $pend = $kades->penduduk;
        if ($pend) {
            echo "  -> Penduduk ID: " . $pend->id . " | Nama: " . $pend->nama . " | NIK: " . $pend->nik . " | id_kk: " . $pend->id_kk . " | kk_level: " . $pend->kk_level . "\n";
            $map = $pend->map;
            echo "    -> Map: " . ($map ? "Exists (lat={$map->lat}, lng={$map->lng})" : "NULL") . "\n";
        } else {
            echo "  -> Penduduk: NULL\n";
        }
    } else {
        echo "Kades: NULL\n";
    }
    
    // Sekdes
    $sekdes = \App\Models\Pamong::where('jabatan_id', 2)->first();
    if ($sekdes) {
        echo "Sekdes Pamong ID: " . $sekdes->pamong_id . " | Nama: " . $sekdes->pamong_nama . " | id_pend: " . $sekdes->id_pend . "\n";
        $pend = $sekdes->penduduk;
        if ($pend) {
            echo "  -> Penduduk ID: " . $pend->id . " | Nama: " . $pend->nama . " | NIK: " . $pend->nik . " | id_kk: " . $pend->id_kk . " | kk_level: " . $pend->kk_level . "\n";
            $map = $pend->map;
            echo "    -> Map: " . ($map ? "Exists (lat={$map->lat}, lng={$map->lng})" : "NULL") . "\n";
        } else {
            echo "  -> Penduduk: NULL\n";
        }
    } else {
        echo "Sekdes: NULL\n";
    }
}

inspect_tenant('bondo-bangsri.localhost:8080');
inspect_tenant('bumidaya.localhost:8080');
