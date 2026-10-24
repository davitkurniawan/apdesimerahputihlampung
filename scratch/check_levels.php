<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
$res = $conn->query("SELECT id, modul, slug, parent, level, hidden, urut FROM `$dst_db`.`setting_modul` WHERE config_id = 1 AND slug IN ('satu-data', 'dtks', 'dtsen', 'prodeskel', 'ppid', 'data-presisi')");
echo "=== MODULE COLUMNS COMPARISON ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Modul: '{$row['modul']}' | Slug: '{$row['slug']}' | Parent: {$row['parent']} | Level: {$row['level']} | Hidden: {$row['hidden']} | Urut: {$row['urut']}\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
