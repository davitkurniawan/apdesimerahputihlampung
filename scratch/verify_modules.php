<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
$res = $conn->query("SELECT id, modul, slug, parent, url, aktif FROM `$dst_db`.`setting_modul` WHERE config_id = 1 AND slug IN ('simbol', 'prodeskel', 'ppid', 'data-presisi', 'prodeskel-ddk', 'ppid-daftar-dokumen', 'data-presisi-sandang')");
echo "=== VERIFYING SYNCED MODULES IN BUMIDAYA ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Modul: '{$row['modul']}' | Slug: '{$row['slug']}' | Parent: {$row['parent']} | URL: '{$row['url']}' | Aktif: {$row['aktif']}\n";
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
