<?php
$conn = new mysqli("db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== MEKARJAYA CLUSTERS ===\n";
$res = $conn->query("SELECT * FROM tweb_wil_clusterdesa WHERE id = 73 OR dusun LIKE '%Mekarjaya%'");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Query failed: " . $conn->error . "\n";
}

echo "\n=== ALL DISTINCT DUSUNS ===\n";
$res2 = $conn->query("SELECT DISTINCT id, dusun, rw, rt FROM tweb_wil_clusterdesa");
if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        printf("ID: %d | Dusun: %s | RW: %s | RT: %s\n", $row['id'], $row['dusun'], $row['rw'], $row['rt']);
    }
}
$conn->close();
