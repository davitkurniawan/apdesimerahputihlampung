<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "--- ALL DISTINCT DUSUNS IN CLUSTER --- \n";
$res = $mysqli->query("SELECT DISTINCT dusun FROM tweb_wil_clusterdesa");
while ($row = $res->fetch_assoc()) {
    echo "Dusun: '" . $row['dusun'] . "'\n";
}

echo "\n--- ALL CLUSTERS --- \n";
$res = $mysqli->query("SELECT id, dusun, rw, rt, urut FROM tweb_wil_clusterdesa ORDER BY id ASC");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Dusun: '{$row['dusun']}' | RW: '{$row['rw']}' | RT: '{$row['rt']}' | Urut: {$row['urut']}\n";
}
?>
