<?php
define('BASEPATH', 'dummy');
require 'donjo-app/config/database.php';
$db_config = $db['default'];
$pdo = new PDO("mysql:host={$db_config['hostname']};dbname={$db_config['database']}", $db_config['username'], $db_config['password']);

$stmt = $pdo->query("SHOW FULL COLUMNS FROM tweb_penduduk");
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $c) {
    if ($c['Null'] == 'NO' && $c['Default'] === null && $c['Extra'] != 'auto_increment') {
        echo $c['Field'] . "\n";
    }
}
