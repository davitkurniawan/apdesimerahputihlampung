<?php
$host = 'db';
$user = 'root';
$pass = 'password';

$src_db = 'xosid_tenant_bondo-bangsri';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi MySQL gagal: " . $conn->connect_error . "\n");
}

echo "=== MENGANALISIS CONFIG ID DAN JUMLAH MODUL ===\n";
foreach ([$src_db, $dst_db] as $db) {
    echo "Database: $db\n";
    $res = $conn->query("SELECT config_id, COUNT(*) as cnt FROM `$db`.`setting_modul` GROUP BY config_id");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            echo "  Config ID: {$row['config_id']} | Total Modul: {$row['cnt']}\n";
        }
    } else {
        echo "  Error: " . $conn->error . "\n";
    }
}

echo "\n=== MENCARI MODUL-MODUL DI BONDO YANG TIDAK ADA DI BUMIDAYA ===\n";
$res_src = $conn->query("SELECT * FROM `$src_db`.`setting_modul` ORDER BY id");
$src_mods = [];
while ($row = $res_src->fetch_assoc()) {
    $src_mods[$row['id']] = $row;
}

$res_dst = $conn->query("SELECT * FROM `$dst_db`.`setting_modul` ORDER BY id");
$dst_mods_by_slug = [];
while ($row = $res_dst->fetch_assoc()) {
    if (!empty($row['slug'])) {
        $dst_mods_by_slug[strtolower($row['slug'])] = $row;
    }
}

$missing = [];
foreach ($src_mods as $id => $mod) {
    $slug = strtolower($mod['slug']);
    if (!isset($dst_mods_by_slug[$slug])) {
        $missing[$id] = $mod;
    }
}

echo "Total modul di Bondo yang TIDAK ADA di Bumi Daya (berdasarkan slug): " . count($missing) . "\n";
foreach ($missing as $id => $mod) {
    echo "ID: $id | Parent: {$mod['parent']} | Slug: '{$mod['slug']}' | Modul: '{$mod['modul']}' | URL: '{$mod['url']}' | Aktif: {$mod['aktif']}\n";
}
