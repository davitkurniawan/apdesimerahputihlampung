<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$res = $mysqli->query("SELECT id, modul, slug, url, parent FROM setting_modul ORDER BY id ASC");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Modul: {$row['modul']} | Slug: {$row['slug']} | URL: {$row['url']} | Parent: {$row['parent']}\n";
}
