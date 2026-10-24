<?php
require_once __DIR__ . '/../vendor/autoload.php';
define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
define('APPPATH', __DIR__ . '/../donjo-app/');
define('ENVIRONMENT', 'development');

require_once BASEPATH . 'core/Common.php';
require_once BASEPATH . 'core/Controller.php';

try {
    require_once APPPATH . 'core/MY_Controller.php';
    require_once APPPATH . 'core/Admin_Controller.php';
    require_once APPPATH . 'controllers/Mandiri.php';
    
    // Boot Laravel Eloquent / DB support
    // (If available, otherwise we'll query raw PDO or mysqli)
    $dsn = "mysql:host=127.0.0.1;port=3307;dbname=xosid;charset=utf8mb4";
    // Check if we are inside docker or on host (inside docker the host is 'db')
    if (gethostname() !== 'kampungjagoan') {
        $dsn = "mysql:host=db;port=3306;dbname=xosid;charset=utf8mb4";
    }
    
    $pdo = new PDO($dsn, 'root', 'password', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "=== SETTING APLIKASI ===\n";
    $stmt = $pdo->query("SELECT * FROM setting_aplikasi WHERE `key` LIKE '%mandiri%' OR `key` LIKE '%layanan%'");
    while ($row = $stmt->fetch()) {
        echo $row['key'] . " => " . $row['value'] . "\n";
    }
    
    echo "\n=== SETTING MODUL ===\n";
    $stmt = $pdo->query("SELECT * FROM setting_modul WHERE `modul` LIKE '%mandiri%' OR `modul` LIKE '%lapak%' OR `url` LIKE '%mandiri%' OR `url` LIKE '%lapak%'");
    while ($row = $stmt->fetch()) {
        echo "ID: " . $row['id'] . " | Modul: " . $row['modul'] . " | URL: " . $row['url'] . " | Aktif: " . $row['aktif'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
