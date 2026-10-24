<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
$res = $mysqli->query("SELECT id, modul, slug, url, aktif, ikon, parent FROM setting_modul WHERE parent = 0 AND aktif = 1 ORDER BY urut");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Modul: {$row['modul']} | Slug: {$row['slug']} | URL: {$row['url']} | Ikon: {$row['ikon']}\n";
}
