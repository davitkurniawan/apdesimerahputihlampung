<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
$res = $mysqli->query("SHOW TABLES");
while ($row = $res->fetch_row()) {
    $table = $row[0];
    $fields = [];
    $fres = $mysqli->query("SHOW COLUMNS FROM `$table`");
    while ($frow = $fres->fetch_assoc()) {
        $type = strtolower($frow['Type']);
        if (strpos($type, 'varchar') !== false || strpos($type, 'text') !== false) {
            $fields[] = $frow['Field'];
        }
    }
    if (empty($fields)) continue;
    
    $where = [];
    foreach ($fields as $f) {
        $where[] = "`$f` LIKE '%yurisdiksi%'";
    }
    $query = "SELECT * FROM `$table` WHERE " . implode(" OR ", $where);
    $qres = $mysqli->query($query);
    if ($qres && $qres->num_rows > 0) {
        echo "Found in table: $table\n";
        while ($qrow = $qres->fetch_assoc()) {
            print_r($qrow);
        }
    }
}
