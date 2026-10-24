<?php
$servername = "xosid_neural_db";
$username = "root";
$password = "password";
$dbname = "xosid";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM anjungan");
if ($result) {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Error querying table: " . $conn->error . "\n";
}
$conn->close();
