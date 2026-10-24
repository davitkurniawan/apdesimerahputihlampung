<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);

// Define LOKASI_CACHE manually to prevent Undefined Constant error
define('LOKASI_CACHE', STORAGEPATH . 'framework/cache');

// Load emergency autoloader
if (file_exists(dirname(__DIR__) . '/xosid_emergency_autoload.php')) {
    require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Boot CodeIgniter
require_once BASEPATH . 'core/Common.php';

$config =& get_config();
$config['subclass_prefix'] = 'MY_';

require_once BASEPATH . 'core/Controller.php';
require_once APPPATH . 'core/MY_Controller.php';

class Dummy extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
}

$ci = new Dummy();
echo "CodeIgniter booted successfully!\n";

echo "Checking database connection...\n";
if (isset($ci->db)) {
    echo "DB is initialized!\n";
    echo "Running query: SELECT * FROM gawai_layanan...\n";
    try {
        $query = $ci->db->get('gawai_layanan');
        $results = $query->result_array();
        echo "Found " . count($results) . " rows:\n";
        print_r($results);
    } catch (Exception $e) {
        echo "Query Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "DB is NOT initialized!\n";
}
