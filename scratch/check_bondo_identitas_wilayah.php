<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$bondo_db = 'xosid_tenant_bondo-bangsri';

$conn = new mysqli($host, $user, $pass, $bondo_db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

$res = $conn->query("SELECT id, modul, slug, parent, url, aktif, hidden FROM setting_modul WHERE id = 200 OR parent = 200");
echo "=== MODULES WITH ID OR PARENT = 200 IN BONDO ===\n";
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Modul: '{$row['modul']}' | Slug: '{$row['slug']}' | Parent: {$row['parent']} | URL: '{$row['url']}' | Aktif: {$row['aktif']} | Hidden: {$row['hidden']}\n";
    }
}
