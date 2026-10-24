<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "--- ALL SETTING_MODUL --- \n";
$res = $mysqli->query("SELECT id, modul, slug, aktif FROM setting_modul ORDER BY id ASC");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Modul: '{$row['modul']}' | Slug: '{$row['slug']}' | Aktif: {$row['aktif']}\n";
}
?>
