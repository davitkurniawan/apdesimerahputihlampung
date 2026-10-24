<?php
define('ENVIRONMENT', 'production');
define('VERSION', '2606.0.0');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://master.localhost:8080');
define('FORCE_HTTPS', false);
$_SERVER['HTTP_HOST'] = 'master.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Set event dispatcher on Eloquent
\Illuminate\Database\Eloquent\Model::setEventDispatcher($app['events']);

// Boot the model so its static boot method runs (and registers trait listeners)
$theme = new \App\Models\Theme();

$dispatcher = \App\Models\Theme::getEventDispatcher();
$listeners = $dispatcher->getListeners('eloquent.creating: App\Models\Theme');
echo "Creating event listeners:\n";
print_r($listeners);

foreach ($listeners as $listener) {
    if ($listener instanceof Closure) {
        $ref = new ReflectionFunction($listener);
        echo "Closure file: " . $ref->getFileName() . "\n";
        echo "Closure lines: " . $ref->getStartLine() . " to " . $ref->getEndLine() . "\n";
        echo "Static variables:\n";
        print_r($ref->getStaticVariables());
    }
}
