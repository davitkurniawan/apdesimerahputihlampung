<?php
// Simulate full CodeIgniter bootstrap
define('LOKASI_CACHE', '/var/www/html/storage/framework/cache');
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

// Load emergency autoloader
require_once __DIR__ . '/../xosid_emergency_autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Boot CodeIgniter Core
require_once BASEPATH . 'core/Common.php';

$config =& get_config();
$config['subclass_prefix'] = 'MY_';

require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';

// Include Admin_Controller
require_once APPPATH . 'core/Admin_Controller.php';

// Load Pengurus
require_once APPPATH . 'controllers/buku_umum/Pengurus.php';

// Mock session to ensure we are logged in as admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_id'] = 1;
$_SESSION['grup'] = 1; // Admin
$_SESSION['siteman'] = 1;

// Instantiate dummy Admin_Controller first to set the get_instance() superobject
class Tester extends Admin_Controller {
    public function __construct() {
        parent::__construct();
    }
}

try {
    $tester = new Tester();
    
    // Now instantiate Pengurus
    $pengurus = new Pengurus();
    
    // Check permission properties!
    echo "=== PERMISSION STATE ===\n";
    echo "akses_modul: " . $pengurus->akses_modul . "\n";
    
    // Let's reflect on $pengurus properties to see all dynamic attributes
    $ref = new ReflectionClass('Pengurus');
    $prop = $ref->getProperty('akses_modul');
    $prop->setAccessible(true);
    echo "akses_modul (reflected): " . $prop->getValue($pengurus) . "\n";
    
} catch (Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
