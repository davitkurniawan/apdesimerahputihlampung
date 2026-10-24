<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$db_bondo = 'xosid_tenant_bondo-bangsri';
$db_bumi = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

// 1. Fetch Bumi Daya admin password hash
$res = $conn->query("SELECT password FROM `$db_bumi`.`user` WHERE username = 'admin'");
if (!$res || $res->num_rows === 0) {
    die("Gagal mengambil password admin dari Bumi Daya.\n");
}
$row = $res->fetch_assoc();
$bumi_hash = $row['password'];

// 2. Update Bondo admin password hash
$stmt = $conn->prepare("UPDATE `$db_bondo`.`user` SET password = ? WHERE username = 'admin'");
if ($stmt) {
    $stmt->bind_param("s", $bumi_hash);
    if ($stmt->execute()) {
        echo "=== SUKSES ===\n";
        echo "Password admin Bondo berhasil disamakan dengan password admin Bumi Daya.\n";
    } else {
        echo "Gagal mengupdate password Bondo: " . $stmt->error . "\n";
    }
    $stmt->close();
} else {
    echo "Gagal menyiapkan query update: " . $conn->error . "\n";
}
