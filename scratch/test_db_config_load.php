<?php
define('ENVIRONMENT', 'production');
define('FCPATH', __DIR__ . '/../');
define('SELF', 'index.php');
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('FORCE_HTTPS', false);
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';

require_once FCPATH . 'vendor/autoload.php';
require_once FCPATH . 'xosid_emergency_autoload.php';
$app = require_once FCPATH . 'bootstrap/app.php';
require_once BASEPATH . 'core/Common.php';

// Load the database config
if (file_exists(APPPATH . 'config/database.php')) {
    include APPPATH . 'config/database.php';
    echo "=== Loaded donjo-app/config/database.php ===\n";
    if (isset($db)) {
        print_r($db);
    } else {
        echo "\$db array is not set!\n";
    }
} else {
    echo "donjo-app/config/database.php does not exist!\n";
}
?>
