<?php
if (!function_exists('base_url')) {
    function base_url() {
        return 'http://localhost:8080/';
    }
}
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return dirname(__DIR__) . '/storage' . ($path ? '/' . ltrim($path, '/') : '');
    }
}

// Define necessary constants
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', FCPATH . 'vendor/codeigniter/framework/system/');
define('APPPATH', FCPATH . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "StatusEnum::all():\n";
print_r(\App\Enums\StatusEnum::all());

echo "\nAktifEnum::all():\n";
if (class_exists('\App\Enums\AktifEnum')) {
    print_r(\App\Enums\AktifEnum::all());
}
