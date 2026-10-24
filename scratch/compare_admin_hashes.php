<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$db_bondo = 'xosid_tenant_bondo-bangsri';
$db_bumi = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

echo "=== COMPARING PASSWORD HASHES FOR admin ===\n";
foreach ([$db_bondo, $db_bumi] as $db) {
    $res = $conn->query("SELECT password FROM `$db`.`user` WHERE username = 'admin'");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        echo "Database: $db | Hash: {$row['password']}\n";
    } else {
        echo "Database: $db | 'admin' not found.\n";
    }
}
