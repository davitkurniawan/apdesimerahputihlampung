<?php
$servername = "xosid_neural_db";
$username = "root";
$password = "password";
$dbname = "xosid";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to database: " . $dbname;
$conn->close();
?>
