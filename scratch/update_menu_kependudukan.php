<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Update module name for ID 2 to 'Kependudukan'
$mysqli->query("UPDATE setting_modul SET modul = 'Kependudukan' WHERE id = 2");

echo "Database updated successfully: Module ID 2 renamed to 'Kependudukan'!\n";
