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

// 1. Load Composer autoloader
require_once FCPATH . 'vendor/autoload.php';

// 2. Load emergency autoloader
if (file_exists(FCPATH . 'xosid_emergency_autoload.php')) {
    require_once FCPATH . 'xosid_emergency_autoload.php';
}

require_once BASEPATH . 'core/Common.php';

// We need to bootstrap CodeIgniter enough to get the superobject!
require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';

class DummyController extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/plain');
echo "=== DEBUGGING ADMIN AUTH ===\n";

$ci = new DummyController();

echo "Session ID: " . session_id() . "\n";
echo "Session userdata:\n";
print_r($ci->session->all_userdata());

$siteman = $ci->session->userdata('siteman');
$sesi = $ci->session->userdata('sesi');
$user_id = $ci->session->userdata('user');

echo "siteman: " . var_export($siteman, true) . "\n";
echo "sesi: " . var_export($sesi, true) . "\n";
echo "user (ID): " . var_export($user_id, true) . "\n";

if ($siteman == 1 && $sesi == 'aktif' && $user_id) {
    echo "Basic session variables: VALID\n";
    
    // Check DB user
    $user = $ci->db->get_where('user', array('id' => $user_id))->row();
    if ($user) {
        echo "User found in DB: YES\n";
        echo "User active: " . var_export($user->active, true) . "\n";
        echo "User session in DB: " . var_export($user->session, true) . "\n";
        echo "Does DB session match current session ID? " . ($user->session === session_id() ? "YES" : "NO") . "\n";
    } else {
        echo "User found in DB: NO\n";
    }
} else {
    echo "Basic session variables: INVALID\n";
}
