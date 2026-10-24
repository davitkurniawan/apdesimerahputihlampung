<?php
define('BASEPATH', 'test');
require_once '../donjo-app/config/database.php';
try {
    $pdo = new PDO('mysql:host='.$db['default']['hostname'].';dbname='.$db['default']['database'], $db['default']['username'], $db['default']['password']);
    $stmt = $pdo->query("DESCRIBE tweb_penduduk");
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($res);
} catch (Exception $e) { echo $e->getMessage(); }
