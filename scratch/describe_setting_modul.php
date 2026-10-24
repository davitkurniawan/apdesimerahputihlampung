<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
$res = $conn->query("DESCRIBE `$dst_db`.`setting_modul`");
echo "=== COLUMNS FOR setting_modul ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "Field: {$row['Field']} | Type: {$row['Type']} | Null: {$row['Null']} | Key: {$row['Key']} | Default: {$row['Default']} | Extra: {$row['Extra']}\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
