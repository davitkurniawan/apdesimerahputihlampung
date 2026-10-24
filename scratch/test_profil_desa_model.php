<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('FCPATH', __DIR__ . '/../');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('ENVIRONMENT', 'production');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('DESAPATH', __DIR__ . '/../desa/');
define('STORAGEPATH', __DIR__ . '/../storage/');
define('MODPATH', __DIR__ . '/../Modules/');
define('LOKASI_CONFIG_DESA', 'desa/config/');

// Load emergency autoloader early
if (file_exists(FCPATH . 'xosid_emergency_autoload.php')) {
    require_once FCPATH . 'xosid_emergency_autoload.php';
}

// Boot Laravel
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Set Eloquent connection resolver
if (class_exists('Illuminate\Database\Eloquent\Model') && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

echo "=== TESTING LARAVEL MODELS DIRECTLY WITH EMERGENCY AUTOLOADER ===\n";

try {
    if (class_exists('App\Models\Config')) {
        $config = \App\Models\Config::first();
        if ($config) {
            echo "Config table: " . $config->getTable() . "\n";
            echo "Config attributes: " . json_encode($config->getAttributes()) . "\n";
        } else {
            echo "Config record not found in table\n";
        }
    } else {
        echo "Class App\Models\Config not found\n";
    }
} catch (Throwable $e) {
    echo "Config error: " . $e->getMessage() . "\n";
}

try {
    if (class_exists('App\Models\ProfilDesa')) {
        $profil = \App\Models\ProfilDesa::first();
        if ($profil) {
            echo "ProfilDesa table: " . $profil->getTable() . "\n";
            echo "ProfilDesa attributes: " . json_encode($profil->getAttributes()) . "\n";
        } else {
            echo "ProfilDesa record not found in table\n";
        }
    } else {
        echo "Class App\Models\ProfilDesa not found\n";
    }
} catch (Throwable $e) {
    echo "ProfilDesa error: " . $e->getMessage() . "\n";
}
