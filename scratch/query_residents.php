<?php
$servername = "xosid_neural_db";
$username = "root";
$password = "password";
$dbname = "xosid";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT COUNT(*) as count FROM tweb_penduduk");
$row = $result->fetch_assoc();
echo "Total Residents: " . $row['count'] . "\n\n";

$result = $conn->query("SELECT id, nik, nama, status_dasar FROM tweb_penduduk LIMIT 5");
echo "First 5 Residents:\n";
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']} | NIK: {$row['nik']} | Name: {$row['nama']} | Status Dasar: {$row['status_dasar']}\n";
}

$conn->close();
?>
