<?php
// scratch/test_user_pamong.php
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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

echo "=== INSPECTING USER 1 (admin) ===\n";
$user = \App\Models\User::find(1);
if (!$user) {
    echo "User 1 not found!\n";
} else {
    echo "User ID: " . $user->id . "\n";
    echo "User Username: " . $user->username . "\n";
    echo "User pamong_id: " . var_export($user->pamong_id, true) . "\n";
    
    $pamong = $user->pamong;
    echo "User Pamong: " . ($pamong ? get_class($pamong) . " (id=" . ($pamong->pamong_id ?? $pamong->id ?? 'N/A') . ")" : 'NULL') . "\n";
    if ($pamong) {
        try {
            $pamong->makeVisible('pamong_nik');
            echo "  -> Pamong makeVisible succeeded!\n";
        } catch (\Throwable $e) {
            echo "  -> Pamong makeVisible FAILED: " . $e->getMessage() . "\n";
        }
    }
}
