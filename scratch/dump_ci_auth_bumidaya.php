<?php
// scratch/dump_ci_auth_bumidaya.php

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

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://localhost:8080/' . $path; }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}
if (!function_exists('kades')) {
    function kades() { return 'Kepala Desa'; }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (function_exists('ci_auth')) {
    echo "ci_auth() exists!\n";
    $auth = ci_auth();
    echo "ci_auth() returned: " . gettype($auth) . "\n";
    if (is_object($auth)) {
        try {
            $user = $auth->user();
            echo "user() returned type: " . gettype($user) . "\n";
            if (is_object($user)) {
                echo "user() class: " . get_class($user) . "\n";
                print_r($user->toArray());
            } else {
                var_dump($user);
            }
        } catch (\Throwable $e) {
            echo "ERROR calling user(): " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
        }
    }
} else {
    echo "ci_auth() does NOT exist!\n";
}
