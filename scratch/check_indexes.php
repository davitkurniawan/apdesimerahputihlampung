<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
$res = $conn->query("SHOW INDEX FROM `$dst_db`.`setting_modul`");
echo "=== INDEXES FOR setting_modul ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "Table: {$row['Table']} | Non_unique: {$row['Non_unique']} | Key_name: {$row['Key_name']} | Seq_in_index: {$row['Seq_in_index']} | Column_name: {$row['Column_name']}\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
