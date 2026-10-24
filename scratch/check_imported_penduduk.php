<?php
define('BASEPATH', 'test');
require_once '../donjo-app/config/database.php';

$dsn = 'mysql:host='.$db['default']['hostname'].';dbname='.$db['default']['database'];
$user = $db['default']['username'];
$pass = $db['default']['password'];

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check total penduduk overall
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tweb_penduduk");
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total Penduduk: " . $res['total'] . "\n";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
