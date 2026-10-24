<?php
// scratch/inspect_config.php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$subdomains = [
    'master.localhost' => 'xosid',
    'bumidaya.localhost:8080' => 'xosid_tenant_bumidaya',
    'bondo-bangsri.localhost:8080' => 'xosid_tenant_bondo-bangsri',
    'demo.localhost:8080' => 'xosid_tenant_demo'
];

foreach ($subdomains as $host => $db) {
    echo "=== Host: $host ($db) ===\n";
    $_SERVER['HTTP_HOST'] = $host;
    $pdo = xosid_get_db_pdo();
    if (!$pdo) {
        echo "Could not connect to database!\n\n";
        continue;
    }
    
    try {
        echo "All settings:\n";
        $q = $pdo->query("SELECT * FROM setting_aplikasi WHERE `key` LIKE '%captcha%' OR `key` LIKE '%sec%' OR `key` LIKE '%tampil%'");
        while ($row = $q->fetch()) {
            echo "  " . $row['key'] . " = " . $row['value'] . "\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
