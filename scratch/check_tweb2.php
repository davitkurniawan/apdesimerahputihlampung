<?php
$conn = new mysqli("db", "root", "password", "xosid");
$res = $conn->query("SELECT COUNT(*) as cnt FROM tweb_penduduk");
print_r($res->fetch_assoc());
