<?php
// scratch/inspect_user_relations.php
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

$user = new \App\Models\User();
$ref = new ReflectionClass(get_class($user));

echo "=== Relations on User ===\n";
foreach ($ref->getMethods() as $method) {
    if ($method->getDeclaringClass()->getName() === get_class($user)) {
        $name = $method->getName();
        if ($method->getNumberOfParameters() === 0) {
            try {
                $res = $user->$name();
                if ($res instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    echo "Relation Method: $name -> Returns relation class " . get_class($res) . "\n";
                }
            } catch (\Throwable $e) {
                // Ignore errors
            }
        }
    }
}
