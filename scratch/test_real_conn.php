<?php
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
define('FCPATH', dirname(__DIR__) . '/');
require_once FCPATH . 'desa/config/database.php';
$config = $db['default'];
$conn = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
if ($conn->connect_error) {
    echo "Connection Failed: " . $conn->connect_error . "\n";
} else {
    echo "Connection Succeeded!\n";
    $res = $conn->query("SELECT app_key FROM config LIMIT 1");
    if ($res) {
        $row = $res->fetch_assoc();
        echo "app_key: " . $row['app_key'] . "\n";
    } else {
        echo "Query Failed: " . $conn->error . "\n";
    }
    $conn->close();
}
