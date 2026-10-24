<?php
define('ENVIRONMENT', 'development');
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Injeksi database connection
require __DIR__ . '/../desa/config/database.php';
$ci_db = $db['default'];
$laravel_db = [
    'driver'    => 'mysql',
    'host'      => $ci_db['hostname'] ?? 'db',
    'port'      => $ci_db['port'] ?? 3306,
    'database'  => $ci_db['database'] ?? 'xosid',
    'username'  => $ci_db['username'] ?? 'root',
    'password'  => $ci_db['password'] ?? 'password',
    'charset'   => 'utf8mb4',
    'collation' => $ci_db['dbcollat'] ?? 'utf8mb4_general_ci',
    'prefix'    => '',
    'strict'    => $ci_db['stricton'] ?? true,
    'engine'    => null,
];
$config = $app['config'];
$connections = $config->get('database.connections') ?: [];
$connections['default'] = $laravel_db;
$config->set('database.connections', $connections);
$config->set('database.default', 'default');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

try {
    if (!function_exists('ci')) {
        function ci() {
            global $dummy_ci;
            if (!isset($dummy_ci)) {
                $dummy_ci = new stdClass();
                $dummy_ci->session = new class {
                    public function flashdata($key) { return null; }
                };
                try {
                    $dummy_ci->list_setting = (object)\Illuminate\Support\Facades\DB::table('setting_aplikasi')->pluck('value', 'key')->toArray();
                } catch (\Throwable $e) {
                    $dummy_ci->list_setting = (object)['sebutan_desa' => 'Desa'];
                }
            }
            return $dummy_ci;
        }
    }
    
    // Stub functions for the view
    if (!function_exists('setting')) { function setting($key) { return ''; } }
    if (!function_exists('favico_desa')) { function favico_desa() { return ''; } }
    if (!function_exists('get_dynamic_title_page_from_path')) { function get_dynamic_title_page_from_path() { return ''; } }
    if (!function_exists('jecho')) { function jecho($cond, $val, $true, $false='') { return $cond == $val ? $true : $false; } }
    if (!function_exists('site_url')) { function site_url($path) { return '/' . $path; } }

    $view = $app->make('view')->make('layanan_mandiri.auth.login', ['cek_anjungan' => ['keyboard' => 0], 'form_action' => '/layanan-mandiri/masuk'])->render();
    file_put_contents(__DIR__ . '/masuk_rendered.html', $view);
    echo "Rendered successfully!";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
