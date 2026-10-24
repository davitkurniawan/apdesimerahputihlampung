<?php
$conn = new mysqli("xosid_neural_db", "root", "password", "xosid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

$patterns = ['%keuangan%', '%anggaran%', '%realisasi%', '%apbd%'];
foreach ($patterns as $pat) {
    echo "=== TABLES LIKE '$pat' ===\n";
    $res = $conn->query("SHOW TABLES LIKE '$pat'");
    while ($row = $res->fetch_row()) {
        echo "  " . $row[0] . "\n";
    }
}

$conn->close();
