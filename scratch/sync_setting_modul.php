<?php
$mysqli = new mysqli("db", "root", "password", "xosid_tenant_demo", 3306);
if ($mysqli->connect_error) {
    die("Connect failed: " . $mysqli->connect_error);
}

$res = $mysqli->query("SELECT id, config_id, modul, slug, url, aktif, ikon, urut, level, parent, hidden FROM setting_modul ORDER BY id ASC");
$demo_rows = [];
while ($r = $res->fetch_assoc()) {
    $demo_rows[] = $r;
}

echo "Total modules in demo: " . count($demo_rows) . "\n";

// Target database to sync
$target_db = 'xosid_tenant_tamanasri-purbolinggo';
$mysqli->select_db($target_db);

$updated_count = 0;
foreach ($demo_rows as $row) {
    $config_id = !empty($row['config_id']) ? (int)$row['config_id'] : 1;
    $chk = $mysqli->query("SELECT id FROM setting_modul WHERE id = {$row['id']}");
    if ($chk && $chk->num_rows > 0) {
        $stmt = $mysqli->prepare("UPDATE setting_modul SET parent = ?, urut = ?, level = ?, hidden = ?, aktif = ?, slug = ?, url = ?, ikon = ?, modul = ?, config_id = ? WHERE id = ?");
        $stmt->bind_param("iiiiissssii", $row['parent'], $row['urut'], $row['level'], $row['hidden'], $row['aktif'], $row['slug'], $row['url'], $row['ikon'], $row['modul'], $config_id, $row['id']);
        $stmt->execute();
        $updated_count++;
    } else {
        $stmt = $mysqli->prepare("INSERT INTO setting_modul (id, config_id, modul, slug, url, aktif, ikon, urut, level, parent, hidden) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssisiiii", $row['id'], $config_id, $row['modul'], $row['slug'], $row['url'], $row['aktif'], $row['ikon'], $row['urut'], $row['level'], $row['parent'], $row['hidden']);
        $stmt->execute();
        $updated_count++;
    }
}

echo "Successfully synced $updated_count modules in $target_db to match demo (Enterprise) hierarchy and order!\n";
