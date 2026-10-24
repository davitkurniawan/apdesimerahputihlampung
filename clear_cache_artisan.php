<?php
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . DIRECTORY_SEPARATOR . 'donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->call('cache:clear');
echo "Cache clear status: " . $status . "\n";
