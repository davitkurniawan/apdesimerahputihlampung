<?php
$conn = new mysqli('xosid_neural_db', 'root', 'password', 'xosid');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$res = $conn->query("SHOW CREATE TABLE xosid_member_notifications");
$row = $res->fetch_row();
echo $row[1];
?>
