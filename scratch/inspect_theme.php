<?php
$databases = ['xosid_tenant_demo', 'xosid_tenant_bumidaya', 'xosid_tenant_bumidaya-palas'];

foreach ($databases as $dbName) {
    echo "Database: $dbName\n";
    try {
        $pdo = new PDO("mysql:host=db;dbname=$dbName;charset=utf8mb4", 'root', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check setting_aplikasi for theme or similar
        try {
            $stmt = $pdo->query("SELECT * FROM setting_aplikasi WHERE key_setting LIKE '%tema%' OR key_setting LIKE '%theme%' OR value LIKE '%theme%' OR value LIKE '%neural%'");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $r) {
                echo "  setting_aplikasi: {$r['key_setting']} = {$r['value']}\n";
            }
        } catch (Exception $e) {
            echo "  setting_aplikasi query failed: " . $e->getMessage() . "\n";
        }
        
        // Let's also check all settings
        try {
            $stmt = $pdo->query("SELECT * FROM setting_aplikasi");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "  Total settings: " . count($rows) . "\n";
            // Print a few important settings
            foreach ($rows as $r) {
                if (in_array($r['key_setting'], ['web_theme', 'theme', 'tema', 'active_theme'])) {
                    echo "    Important setting: {$r['key_setting']} = {$r['value']}\n";
                }
            }
        } catch (Exception $e) {}

    } catch (Exception $e) {
        echo "  Connection failed: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
