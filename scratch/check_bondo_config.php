<?php
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

// Boot CodeIgniter
require_once BASEPATH . 'core/Common.php';
$CFG =& load_class('Config', 'core');
$CFG->set_item('enable_hooks', FALSE);

// Load database to ensure settings are loaded
$ci =& get_instance();
$ci->load->database();

echo "kades_jabatan_id setting: " . var_export(setting('kades_jabatan_id'), true) . "\n";
echo "sekdes_jabatan_id setting: " . var_export(setting('sekdes_jabatan_id'), true) . "\n";
echo "pj_kades_jabatan_id setting: " . var_export(setting('pj_kades_jabatan_id'), true) . "\n";
