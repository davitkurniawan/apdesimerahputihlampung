<?php
$conn = new mysqli("db", "root", "password", "xosid_tenant_bumidaya");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}
$res = $conn->query("SELECT id, modul, slug, url, aktif, parent FROM setting_modul WHERE parent = 0 AND aktif = 1 ORDER BY urut");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        printf("ID: %d | Modul: %s | Slug: %s | URL: %s\n", $row['id'], $row['modul'], $row['slug'], $row['url']);
    }
} else {
    echo "Error: " . $conn->error . "\n";
}
$conn->close();
