<?php
define('BASEPATH', 'dummy');
require 'donjo-app/config/database.php';
$db_config = $db['default'];
$pdo = new PDO("mysql:host={$db_config['hostname']};dbname={$db_config['database']}", $db_config['username'], $db_config['password']);

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach($tables as $t) {
    if (strpos($t, 'tenant') !== false || strpos($t, 'saas') !== false) {
        echo $t . "\n";
    }
}
