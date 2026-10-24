<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->query("UPDATE setting_aplikasi SET value = '1' WHERE `key` = 'tampilan_anjungan'");
echo "Updated tampilan_anjungan to 1. Rows affected: " . $mysqli->affected_rows . "\n";
?>
