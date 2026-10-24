<?php
// scratch/test_request_binding.php

if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'production');
if (!defined('FCPATH')) define('FCPATH', dirname(__DIR__) . '/');
if (!defined('SELF')) define('SELF', 'index.php');
if (!defined('BASEPATH')) define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
if (!defined('APPPATH')) define('APPPATH', dirname(__DIR__) . '/donjo-app/');
if (!defined('VIEWPATH')) define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH')) define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH')) define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH')) define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL')) define('APP_URL', 'http://localhost');
if (!defined('FORCE_HTTPS')) define('FORCE_HTTPS', false);
if (!defined('LOKASI_CONFIG_DESA')) define('LOKASI_CONFIG_DESA', FCPATH . 'desa/config/');

require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';

include_once dirname(__DIR__) . '/xosid_database_helpers.php';

if (class_exists('XOSIDConfigRepository')) {
    $currentConfig = $app->make('config');
    if ($currentConfig && !($currentConfig instanceof XOSIDConfigRepository)) {
        $newConfig = new XOSIDConfigRepository($currentConfig);
        $app->instance('config', $newConfig);
    }
}

// Register XOSIDAuthManager
class XOSIDAuthManager extends \Illuminate\Auth\AuthManager {
    public function guard($name = null) {
        $app = $this->app;
        $bound = $app->bound('request');
        if (!$bound) {
            $app->instance('request', \Illuminate\Http\Request::capture());
        }
        try {
            return parent::guard($name);
        } finally {
            if (!$bound) {
                $app->forgetInstance('request');
            }
        }
    }
}

$newAuth = new XOSIDAuthManager($app);
$app->instance('auth', $newAuth);

try {
    $guard = $app['auth']->guard('admin');
    echo "Guard resolved: " . get_class($guard) . "\n";
    $ref = new \ReflectionClass($guard);
    if ($ref->hasProperty('request')) {
        $prop = $ref->getProperty('request');
        $prop->setAccessible(true);
        $req = $prop->getValue($guard);
        echo "Guard Request type: " . (is_object($req) ? get_class($req) : 'NULL') . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
