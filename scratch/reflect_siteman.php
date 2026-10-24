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
require_once __DIR__ . '/../xosid_emergency_autoload.php';

// Include illuminate_helper.php
require_once __DIR__ . '/../donjo-app/helpers/illuminate_helper.php';
require_once __DIR__ . '/../donjo-app/helpers/general_helper.php';
require_once __DIR__ . '/../donjo-app/helpers/opensid_helper.php';
require_once __DIR__ . '/../donjo-app/helpers/core_helper.php';

// Mock CodeIgniter environment
class CI_Controller {}
class MY_Controller extends CI_Controller {}

// Let's load Siteman controller file
require_once __DIR__ . '/../donjo-app/controllers/Siteman.php';

$ref = new ReflectionClass('Siteman');
echo "Class: Siteman\n";
echo "Parent: " . get_parent_class('Siteman') . "\n";
echo "Methods:\n";
foreach ($ref->getMethods() as $m) {
    if ($m->getDeclaringClass()->getName() === 'Siteman') {
        echo " - " . $m->getName() . "\n";
    }
}
