<?php
define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

// Mock CI environment so we can boot CodeIgniter
ob_start();
require_once BASEPATH . 'core/CodeIgniter.php';
ob_end_clean();

$cfg = new App\Models\Config;
echo "Traits on Config: " . implode(', ', class_uses($cfg)) . "\n";
echo "Global Scopes on Config: " . implode(', ', array_keys($cfg->getGlobalScopes())) . "\n";

$pamong = new App\Models\Pamong;
echo "Traits on Pamong: " . implode(', ', class_uses($pamong)) . "\n";
echo "Global Scopes on Pamong: " . implode(', ', array_keys($pamong->getGlobalScopes())) . "\n";
