<?php
header('Content-Type: text/plain');

$db_key = null;
$conn = new mysqli('db', 'root', 'password', 'xosid_tenant_bumidaya');
if (!$conn->connect_error) {
    $res = $conn->query("SELECT app_key FROM config LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) {
        $db_key = $row['app_key'];
    }
    $conn->close();
}

$file_key = file_exists(__DIR__ . '/../desa/app_key') ? file_get_contents(__DIR__ . '/../desa/app_key') : null;

echo "=== KEY COMPARISON ===\n";
echo "Database Key: " . var_export($db_key, true) . " (len: " . strlen($db_key) . ")\n";
echo "File Key:     " . var_export($file_key, true) . " (len: " . strlen($file_key) . ")\n";

if ($db_key === $file_key) {
    echo "EXACT MATCH (===): YES\n";
} else {
    echo "EXACT MATCH (===): NO\n";
}

if (trim($db_key) === trim($file_key)) {
    echo "TRIMMED MATCH:     YES\n";
} else {
    echo "TRIMMED MATCH:     NO\n";
}
