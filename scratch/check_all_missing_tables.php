<?php
$host = 'db';
$user = 'root';
$pass = 'password';
$src_db = 'xosid_tenant_bondo-bangsri';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);

$sql_check_tables = "
    SELECT t1.table_name AS table_name
    FROM information_schema.tables t1 
    WHERE t1.table_schema = '$src_db' 
      AND t1.table_name NOT IN (
          SELECT t2.table_name 
          FROM information_schema.tables t2 
          WHERE t2.table_schema = '$dst_db'
      )
";

$res = $conn->query($sql_check_tables);
$missing = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $missing[] = $row['table_name'];
    }
}

echo "=== ALL MISSING TABLES ===\n";
if (empty($missing)) {
    echo "No missing tables.\n";
} else {
    echo "Count: " . count($missing) . "\n";
    foreach ($missing as $t) {
        echo " - $t\n";
    }
}
