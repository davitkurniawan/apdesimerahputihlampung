<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
$res = $conn->query("SELECT id, modul, slug, parent, url, aktif FROM `$dst_db`.`setting_modul` WHERE config_id = 1 AND (modul LIKE '%Integrasi%' OR slug LIKE '%integrasi%' OR slug IN ('dtks', 'dtsen', 'prodeskel', 'ppid', 'data-presisi'))");
echo "=== MODULES RELATED TO INTEGRASI DATA ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Modul: '{$row['modul']}' | Slug: '{$row['slug']}' | Parent: {$row['parent']} | URL: '{$row['url']}' | Aktif: {$row['aktif']}\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
