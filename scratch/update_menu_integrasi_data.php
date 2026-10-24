<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Rename 'Satu Data' (ID 352) to 'Integrasi Data' and set urut
$mysqli->query("UPDATE setting_modul SET modul = 'Integrasi Data', urut = 40 WHERE id = 352");

// 2. Move DTSEN (ID 512) to be a child of Integrasi Data (ID 352)
$mysqli->query("UPDATE setting_modul SET parent = 352, urut = 2 WHERE id = 512");

// 3. Make sure DTKS (ID 484) has urut = 1
$mysqli->query("UPDATE setting_modul SET urut = 1 WHERE id = 484");

echo "Database updated successfully: Satu Data renamed to Integrasi Data and DTSEN moved under it!\n";
