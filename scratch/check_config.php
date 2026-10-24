<?php
define('BASEPATH', 'dummy');
require 'donjo-app/config/database.php';
$db_config = $db['default'];
$pdo = new PDO("mysql:host={$db_config['hostname']};dbname={$db_config['database']}", $db_config['username'], $db_config['password']);

$stmt = $pdo->query("SELECT id, nama_desa FROM config LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

$stmt2 = $pdo->query("SELECT id, config_id, nama FROM tweb_penduduk LIMIT 2");
print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
