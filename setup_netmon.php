<?php
$conn = new mysqli('db', 'root', 'password', 'xosid');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS xosid_netmon_wilayah (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    kode_desa VARCHAR(10) NOT NULL UNIQUE,
    kode_desa_bps VARCHAR(15) DEFAULT NULL,
    nama_desa VARCHAR(100) NOT NULL,
    kode_kecamatan VARCHAR(6) DEFAULT NULL,
    nama_kecamatan VARCHAR(100) DEFAULT NULL,
    kode_kabupaten VARCHAR(4) DEFAULT NULL,
    nama_kabupaten VARCHAR(100) DEFAULT NULL,
    kode_provinsi VARCHAR(2) DEFAULT NULL,
    nama_provinsi VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "Table xosid_netmon_wilayah created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// Check if tulusrejo is there
$sql2 = "SELECT kode_desa, nama_desa FROM xosid_registrasi_tenant WHERE nama_desa LIKE '%tulusrejo%'";
$res = $conn->query($sql2);
while ($row = $res->fetch_assoc()) {
    echo "Found registered tenant: " . $row['kode_desa'] . " - " . $row['nama_desa'] . "\n";
}

$conn->close();
