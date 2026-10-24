<?php
$databases = ['xosid_tenant_demo', 'xosid_tenant_bumidaya', 'xosid_tenant_bumidaya-palas'];

foreach ($databases as $dbName) {
    echo "========================================\n";
    echo "Database: $dbName\n";
    echo "========================================\n";
    try {
        $pdo = new PDO("mysql:host=db;dbname=$dbName;charset=utf8mb4", 'root', 'password');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check columns of setting_aplikasi
        $stmt = $pdo->query("DESCRIBE setting_aplikasi");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Columns in setting_aplikasi:\n";
        $keyCol = 'key';
        foreach ($cols as $c) {
            echo " - {$c['Field']}\n";
            if (strtolower($c['Field']) === 'key' || strtolower($c['Field']) === 'key_setting') {
                $keyCol = $c['Field'];
            }
        }
        
        // Query theme settings
        $stmt = $pdo->prepare("SELECT * FROM setting_aplikasi WHERE `$keyCol` LIKE :theme OR value LIKE :neural");
        $stmt->execute(['theme' => '%tema%', 'neural' => '%neural%']);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Theme-related settings:\n";
        foreach ($rows as $r) {
            echo "  {$r[$keyCol]} = {$r['value']}\n";
        }
        
    } catch (Exception $e) {
        echo "  Failed: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
