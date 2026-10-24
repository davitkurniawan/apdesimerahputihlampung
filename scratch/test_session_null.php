<?php
// scratch/test_session_null.php

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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (strpos($errstr, 'Attempt to read property') !== false) {
        echo "========================================\n";
        echo "WARNING: $errstr\n";
        echo "File: $errfile | Line: $errline\n";
        echo "========================================\n";
    }
    return false;
});

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Force connection resolver
$currentResolver = \Illuminate\Database\Eloquent\Model::getConnectionResolver();
if (!$currentResolver && isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

// 1. First test: Unset $_SESSION['user'] completely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
unset($_SESSION['user']);
$_SESSION['user_id'] = 999; // different ID

echo "--- Testing with \$_SESSION['user'] unset ---\n";
try {
    $configModel = \App\Models\Config::first();
    if ($configModel) {
        $configModel->pamong()->first();
    }
} catch (\Throwable $e) {}

// 2. Second test: Unset $_SESSION['user_id'] completely
unset($_SESSION['user_id']);
$_SESSION['user'] = null;

echo "--- Testing with both unset/null ---\n";
try {
    $configModel = \App\Models\Config::first();
    if ($configModel) {
        $configModel->pamong()->first();
    }
} catch (\Throwable $e) {}
