<?php
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bumidaya.localhost';

// Define mocks if not already defined
if (!function_exists('site_url')) {
    function site_url($path = '') { return '/' . $path; }
}
if (!function_exists('base_url')) {
    function base_url($path = '') { return '/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'mock_setting'; }
}
if (!function_exists('can')) {
    function can($perm) { return true; }
}
if (!function_exists('form_open')) {
    function form_open($action = '', $attributes = '') { return '<form>'; }
}
if (!function_exists('ci_route')) {
    function ci_route($name, $params = []) { return '/' . $name; }
}
if (!function_exists('get_dynamic_title_page_from_path')) {
    function get_dynamic_title_page_from_path() { return 'Mock Title'; }
}
if (!function_exists('favico_desa')) {
    function favico_desa() { return 'favicon.ico'; }
}
if (!function_exists('cek_koneksi_internet')) {
    function cek_koneksi_internet() { return true; }
}
if (!function_exists('SebutanDesa')) {
    function SebutanDesa($title = '') { return 'Desa'; }
}
if (!function_exists('AmbilVersi')) {
    function AmbilVersi() { return '2607.0.0'; }
}
if (!function_exists('AmbilFoto')) {
    function AmbilFoto($f) { return $f; }
}

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Manually configure tenant database connection
$config = $app->make('config');
$config->set('database.connections.mysql.database', 'xosid_tenant_bumidaya');
$config->set('database.connections.mysql.username', 'root');
$config->set('database.connections.mysql.password', 'password');
$config->set('database.connections.mysql.host', 'db');
$config->set('database.connections.mysql.port', 3306);
$config->set('database.default', 'mysql');

use Illuminate\Support\Facades\View;

try {
    $authObj = new stdClass();
    $authObj->nama = 'Administrator';
    $authObj->foto = 'user.png';
    $authObj->grup = 1;

    $data = [
        'wilayah' => 'Dusun',
        'parent' => 0,
        'backUrl' => '#',
        'level' => 'dusun',
        'title' => 'Daftar Dusun',
        'jabatan' => 'Kepala Dusun',
        'refreshOrder' => false,
        'auth' => $authObj,
    ];
    
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['nama'] = 'Administrator';
    $_SESSION['siteman'] = 1;
    $_SESSION['grup'] = 1;
    $_SESSION['admin_level'] = 1;
    $_SESSION['sesi'] = 'aktif';
    
    echo "Compiling and rendering view...\n";
    $html = View::make('admin.wilayah.index', $data)->render();
    echo "Render success! HTML Length: " . strlen($html) . "\n";
} catch (\Throwable $e) {
    echo "ERROR RENDERING VIEW:\n";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . " Line " . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
