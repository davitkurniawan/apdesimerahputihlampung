<?php
$mysqli = new mysqli("db", "root", "password", "", 3306);
if ($mysqli->connect_error) {
    die("Connect failed: " . $mysqli->connect_error);
}

foreach (['xosid_tenant_demo', 'xosid_tenant_tamanasri-purbolinggo'] as $db_name) {
    echo "==================== Database: $db_name ====================\n";
    $mysqli->select_db($db_name);
    $res = $mysqli->query("SELECT id, modul, slug, url, urut, parent, hidden, aktif FROM setting_modul WHERE (parent = 0 OR parent IS NULL) AND hidden = 0 AND aktif = 1 ORDER BY urut ASC");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            echo "ID: {$r['id']} | Urut: {$r['urut']} | Modul: {$r['modul']} | Slug: {$r['slug']} | URL: {$r['url']}\n";
        }
    } else {
        echo "Error: " . $mysqli->error . "\n";
    }
}
