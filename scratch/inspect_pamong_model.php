<?php
// scratch/inspect_pamong_model.php
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

// We need to load CodeIgniter core files to reflect CodeIgniter models
require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Model.php';
if (file_exists(APPPATH . 'core/MY_Model.php')) {
    require_once APPPATH . 'core/MY_Model.php';
}

// Find Pamong_model file
$model_file = APPPATH . 'models/Pamong_model.php';
if (file_exists($model_file)) {
    echo "Pamong_model.php exists!\n";
    // We cannot require it directly if it is encrypted, but let's try to class_exists or load it
    // Wait, CodeIgniter loader loads it, or we can require it if PHP can parse it (if loader is registered)
    try {
        @include_once $model_file;
    } catch (\Throwable $e) {
        echo "Error requiring Pamong_model: " . $e->getMessage() . "\n";
    }
    
    if (class_exists('Pamong_model')) {
        echo "Pamong_model loaded!\n";
        $ref = new ReflectionClass('Pamong_model');
        foreach ($ref->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === 'Pamong_model') {
                echo " - " . $method->getName() . "\n";
            }
        }
    } else {
        echo "Pamong_model class not found after require\n";
    }
} else {
    echo "Pamong_model.php not found at $model_file\n";
}
