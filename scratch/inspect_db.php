<?php
$db_host = 'db';
$db_user = 'root';
$db_pass = 'password';
$db_name = 'xosid';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "--- Privilege / Permission Tables Search ---\n";
    $matched_tables = [];
    foreach ($tables as $t) {
        if (preg_match('/(akses|hak|permission|role|privilege|grup|menu)/i', $t)) {
            $matched_tables[] = $t;
        }
    }
    print_r($matched_tables);

    foreach ($matched_tables as $mt) {
        echo "\n--- Table: $mt ---\n";
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM `$mt`");
            $count = $stmt->fetch()['total'];
            echo "Total rows: $count\n";
            if ($count > 0) {
                $stmt = $pdo->query("SELECT * FROM `$mt` LIMIT 5");
                print_r($stmt->fetchAll());
            }
        } catch (Exception $ex) {
            echo "Error reading table $mt: " . $ex->getMessage() . "\n";
        }
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
