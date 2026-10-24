<?php
// scratch/inspect_config_relations_reflection.php

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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (isset($app['db'])) {
    \Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);
}

$config = new \App\Models\Config();
$ref = new ReflectionClass(get_class($config));

echo "=== Methods on Config ===\n";
foreach ($ref->getMethods() as $method) {
    // Only methods defined in Config class directly
    if ($method->getDeclaringClass()->getName() === get_class($config)) {
        $name = $method->getName();
        if ($method->getNumberOfParameters() === 0) {
            try {
                $res = $config->$name();
                if ($res instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    echo "Relation Method: $name -> Returns relation class " . get_class($res) . "\n";
                } else {
                    echo "Method: $name -> Returns type: " . (is_object($res) ? get_class($res) : gettype($res)) . "\n";
                }
            } catch (\Throwable $e) {
                echo "Method: $name -> Threw error: " . $e->getMessage() . "\n";
            }
        } else {
            echo "Method: $name -> Requires parameters\n";
        }
    }
}
