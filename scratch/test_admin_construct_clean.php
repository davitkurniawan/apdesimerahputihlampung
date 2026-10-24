<?php
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
define('LOKASI_CACHE', FCPATH . 'storage/cache/');
define('LOKASI_USER_PICTURE', FCPATH . 'storage/user_pictures/');
define('LOKASI_DOKUMEN', FCPATH . 'storage/dokumen/');
define('LOKASI_GALERI', FCPATH . 'storage/galeri/');

// 1. Load Composer autoloader
require_once FCPATH . 'vendor/autoload.php';

// 2. Load emergency autoloader
if (file_exists(FCPATH . 'xosid_emergency_autoload.php')) {
    require_once FCPATH . 'xosid_emergency_autoload.php';
}

require_once BASEPATH . 'core/Common.php';

// Bootstrap CI configuration
$config =& get_config();
$config['subclass_prefix'] = 'MY_';

require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';
require_once APPPATH . 'core/Admin_Controller.php';

// Mock session to ensure we are logged in as admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_id'] = 1;
$_SESSION['grup'] = 1; // Admin
$_SESSION['siteman'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['sesi'] = 'aktif';
$_SESSION['admin_level'] = 1;

class Tester extends Admin_Controller {
    public function __construct() {
        parent::__construct();
    }
}

try {
    echo "Instantiating Admin_Controller...\n";
    $tester = new Tester();
    echo "Instantiated successfully!\n";
} catch (Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
