<?php
$conn = new mysqli('db', 'root', 'password', 'xosid_tenant_gunungsugih-gunungsugih');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SELECT * FROM setting_aplikasi LIMIT 5");
if (!$result) {
    echo "Query error: " . $conn->error . "\n";
} else {
    echo "Rows: " . $result->num_rows . "\n";
    while($row = $result->fetch_assoc()) {
        print_r($row);
    }
}
