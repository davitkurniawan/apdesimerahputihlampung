<?php
// scratch/check_pamong_query.php
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bondo-bangsri.localhost';

if (!defined('ENVIRONMENT'))
    define('ENVIRONMENT', 'production');
if (!defined('FCPATH'))
    define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
if (!defined('SELF'))
    define('SELF', 'index.php');
if (!defined('BASEPATH'))
    define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
if (!defined('APPPATH'))
    define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
if (!defined('VIEWPATH'))
    define('VIEWPATH', APPPATH . 'views/');
if (!defined('DESAPATH'))
    define('DESAPATH', FCPATH . 'desa/');
if (!defined('STORAGEPATH'))
    define('STORAGEPATH', FCPATH . 'storage/');
if (!defined('MODPATH'))
    define('MODPATH', FCPATH . 'Modules/');
if (!defined('APP_URL'))
    define('APP_URL', 'http://localhost:8080');
if (!defined('FORCE_HTTPS'))
    define('FORCE_HTTPS', false);
if (!defined('LOKASI_CONFIG_DESA'))
    define('LOKASI_CONFIG_DESA', 'desa/config/');

if (!function_exists('setting')) {
    function setting($key, $default = null) { return 'Desa'; }
}

if (!function_exists('identitas')) {
    function identitas() {
        return '1';
    }
}

if (!function_exists('app')) {
    function app($abstract = null, array $parameters = []) {
        if (is_null($abstract)) {
            return \Illuminate\Container\Container::getInstance();
        }
        return \Illuminate\Container\Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return STORAGEPATH . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../xosid_config_id_scope_hijack.php';

try {
    @include_once __DIR__ . '/../donjo-app/helpers/opensid_helper.php';
} catch (\Throwable $e) {}

include_once __DIR__ . '/../xosid_database_helpers.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

if (class_exists('App\Models\Pamong')) {
    $builder = \App\Models\Pamong::kepalaDesa();
    echo "SQL: " . $builder->toSql() . "\n";
    echo "Bindings: " . json_encode($builder->getBindings()) . "\n";
    
    $res = $builder->first();
    echo "RESULT: " . ($res ? json_encode($res->toArray()) : 'NULL') . "\n";
}
