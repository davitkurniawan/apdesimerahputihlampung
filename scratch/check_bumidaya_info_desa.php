<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$bumi_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass, $bumi_db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

$res = $conn->query("SELECT id, modul, slug, parent, url, aktif, hidden FROM setting_modul WHERE config_id = 1 AND (id = 200 OR parent = 200 OR slug = 'info-desa' OR parent = (SELECT id FROM setting_modul WHERE config_id = 1 AND slug = 'info-desa'))");
echo "=== INFO DESA MENU IN BUMIDAYA ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Modul: '{$row['modul']}' | Slug: '{$row['slug']}' | Parent: {$row['parent']} | URL: '{$row['url']}' | Aktif: {$row['aktif']} | Hidden: {$row['hidden']}\n";
    }
}
