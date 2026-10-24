<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$src_db = 'xosid_tenant_bondo-bangsri';

$conn = new mysqli($host, $user, $pass);
$res = $conn->query("SELECT * FROM `$src_db`.`setting_modul` WHERE parent = id");
echo "=== MODULES WITH parent = id IN BONDO ===\n";
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Modul: {$row['modul']} | Slug: {$row['slug']} | Parent: {$row['parent']}\n";
    }
} else {
    echo "None.\n";
}
