<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$src_db = 'xosid_tenant_bondo-bangsri';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);

echo "=== CHECKING SIMBOL MODULE IN BOTH DATABASES ===\n";
foreach ([$src_db, $dst_db] as $db) {
    echo "Database: $db\n";
    $res = $conn->query("SELECT * FROM `$db`.`setting_modul` WHERE slug = 'simbol' OR modul = 'Simbol'");
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            echo "  ID: {$row['id']} | Config ID: {$row['config_id']} | Parent: {$row['parent']} | Slug: {$row['slug']} | Modul: {$row['modul']} | Aktif: {$row['aktif']}\n";
        }
    } else {
        echo "  Not found.\n";
    }
}
