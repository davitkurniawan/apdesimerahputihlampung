<?php
// Scratch script to restore neural-fundamental theme in xosid_tenant_bondo-bangsri

$db_host = 'db';
$db_user = 'root';
$db_pass = 'password';
$db_name = 'xosid_tenant_bondo-bangsri';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

// 1. Deactivate all themes
$conn->query("UPDATE theme SET status = 0");

// 2. Try to activate neural-fundamental
$res = $conn->query("SELECT id FROM theme WHERE slug = 'neural-fundamental'");
if ($res && $res->num_rows > 0) {
    $conn->query("UPDATE theme SET status = 1 WHERE slug = 'neural-fundamental'");
    echo "Successfully activated existing neural-fundamental theme.\n";
} else {
    // 3. Insert and activate neural-fundamental theme
    $sql = "INSERT INTO theme (config_id, nama, slug, versi, sistem, path, status, keterangan)
            VALUES (1, 'Neural Fundamental', 'neural-fundamental', '2605.0.0', 0,
                    'storage/app/themes/neural-fundamental', 1, 'Tema Portal Desa XOSID')";
    if ($conn->query($sql)) {
        echo "Successfully inserted and activated neural-fundamental theme.\n";
    } else {
        echo "Error inserting theme: " . $conn->error . "\n";
    }
}

$conn->close();
?>
