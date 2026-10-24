<?php
// scratch/dump_scopes_only.php

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
define('FORCE_HTTPS', false);
define('APP_URL', 'http://localhost:8080');

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cfg = new App\Models\Config;
echo "Traits on Config: " . implode(', ', class_uses($cfg)) . "\n";
echo "Global Scopes on Config: " . implode(', ', array_keys($cfg->getGlobalScopes())) . "\n";
echo "Config Connection Name: " . $cfg->getConnectionName() . "\n";

$pamong = new App\Models\Pamong;
echo "Traits on Pamong: " . implode(', ', class_uses($pamong)) . "\n";
echo "Global Scopes on Pamong: " . implode(', ', array_keys($pamong->getGlobalScopes())) . "\n";
echo "Pamong Connection Name: " . $pamong->getConnectionName() . "\n";

$user = new App\Models\User;
echo "Traits on User: " . implode(', ', class_uses($user)) . "\n";
echo "Global Scopes on User: " . implode(', ', array_keys($user->getGlobalScopes())) . "\n";
echo "User Connection Name: " . $user->getConnectionName() . "\n";
