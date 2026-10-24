<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

$patterns = ['%pekerjaan%', '%pendidikan%', '%agama%', '%sex%'];
foreach ($patterns as $pat) {
    echo "=== TABLES LIKE '$pat' ===\n";
    $res = $conn->query("SHOW TABLES LIKE '$pat'");
    while ($row = $res->fetch_row()) {
        echo "  " . $row[0] . "\n";
    }
}

// Let's print some rows from reference tables to see values
$ref_tables = ['tweb_penduduk_sex', 'tweb_penduduk_pekerjaan', 'tweb_penduduk_pendidikan_kk', 'tweb_penduduk_agama'];
foreach ($ref_tables as $table) {
    $check = $conn->query("SHOW TABLES LIKE '$table'");
    if ($check->num_rows > 0) {
        echo "\n=== CONTENT OF $table ===\n";
        $res = $conn->query("SELECT * FROM $table");
        while ($row = $res->fetch_assoc()) {
            print_r($row);
        }
    }
}

$conn->close();
