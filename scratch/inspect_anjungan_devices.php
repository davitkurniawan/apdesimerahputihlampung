<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$res = $mysqli->query("SHOW TABLES LIKE '%anjungan%'");
while ($row = $res->fetch_row()) {
    echo "Table: {$row[0]}\n";
    $res2 = $mysqli->query("SELECT * FROM `{$row[0]}`");
    if ($res2) {
        while ($row2 = $res2->fetch_assoc()) {
            print_r($row2);
        }
    }
}
?>
