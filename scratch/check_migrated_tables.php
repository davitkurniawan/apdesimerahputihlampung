<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$src_db = 'xosid_tenant_bondo-bangsri';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);

echo "=== CHECKING TABLES STARTING WITH prodeskel, ppid, data_presisi ===\n";
foreach ([$src_db, $dst_db] as $db) {
    echo "Database: $db\n";
    $res = $conn->query("SHOW TABLES FROM `$db` LIKE 'prodeskel%'");
    echo "  prodeskel tables: " . ($res ? $res->num_rows : 0) . "\n";
    $res = $conn->query("SHOW TABLES FROM `$db` LIKE 'ppid%'");
    echo "  ppid tables:      " . ($res ? $res->num_rows : 0) . "\n";
    $res = $conn->query("SHOW TABLES FROM `$db` LIKE 'data_presisi%'");
    echo "  data_presisi tables: " . ($res ? $res->num_rows : 0) . "\n";
}
