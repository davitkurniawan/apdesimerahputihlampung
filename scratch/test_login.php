<?php
require_once __DIR__ . '/../xosid_ajax_db.php';

// Check DB values
$res = $conn->query("SELECT * FROM xosid_settings WHERE setting_key = 'superadmin_2fa_enabled'");
echo "DB superadmin_2fa_enabled:\n";
var_dump($res->fetch_assoc());

$is2fa = false;
$res2fa = $conn->query("SELECT setting_value FROM xosid_settings WHERE setting_key = 'superadmin_2fa_enabled'");
if ($res2fa && $res2fa->num_rows > 0) {
    $row = $res2fa->fetch_assoc();
    echo "Fetched row value: " . $row['setting_value'] . "\n";
    if ($row['setting_value'] == '1') {
        $is2fa = true;
    }
}
echo "is2fa status: " . ($is2fa ? 'true' : 'false') . "\n";
