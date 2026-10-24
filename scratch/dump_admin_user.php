<?php
$conn = mysqli_connect('xosid_neural_db', 'root', 'password', 'xosid');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$res = mysqli_query($conn, "SELECT * FROM user WHERE id = 1");
$row = mysqli_fetch_assoc($res);
header('Content-Type: text/plain');
print_r($row);
