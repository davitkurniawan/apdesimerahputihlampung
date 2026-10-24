<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$res = $mysqli->query("SELECT * FROM setting_aplikasi WHERE `key` LIKE '%anjungan%' OR `key` LIKE '%mandiri%' OR `key` LIKE '%layanan%'");
while ($row = $res->fetch_assoc()) {
    echo "{$row['key']} => {$row['value']} ({$row['judul']})\n";
}
?>
