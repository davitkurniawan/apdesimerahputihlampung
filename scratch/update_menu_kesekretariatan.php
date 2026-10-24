<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Rename 'Sekretariat' (ID 15) to 'Kesekretariatan' and move under 'Digital Office' (ID 4)
$mysqli->query("UPDATE setting_modul SET modul = 'Kesekretariatan', parent = 4, urut = 6 WHERE id = 15");

echo "Database updated successfully: Sekretariat renamed to Kesekretariatan under Digital Office!\n";
