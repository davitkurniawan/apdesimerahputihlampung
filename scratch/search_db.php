<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$search = "sistem data rakyat";
echo "Searching all tables for: '$search'\n";

$tables_res = $mysqli->query("SHOW TABLES");
while ($t_row = $tables_res->fetch_row()) {
    $table = $t_row[0];
    
    // Get text columns
    $cols_res = $mysqli->query("SHOW COLUMNS FROM `$table`");
    $text_cols = [];
    while ($c_row = $cols_res->fetch_assoc()) {
        $type = strtolower($c_row['Type']);
        if (strpos($type, 'char') !== false || strpos($type, 'text') !== false) {
            $text_cols[] = $c_row['Field'];
        }
    }
    
    if (empty($text_cols)) continue;
    
    // Build query
    $where_clauses = [];
    foreach ($text_cols as $col) {
        $where_clauses[] = "`$col` LIKE '%" . $mysqli->real_escape_string($search) . "%'";
    }
    
    $query = "SELECT * FROM `$table` WHERE " . implode(" OR ", $where_clauses) . " LIMIT 10";
    try {
        $search_res = $mysqli->query($query);
        if ($search_res && $search_res->num_rows > 0) {
            echo "\nFound in table: `$table` (" . $search_res->num_rows . " rows):\n";
            while ($row = $search_res->fetch_assoc()) {
                print_r($row);
            }
        }
    } catch (Throwable $e) {
        // Collation mismatch, try casting each column
        $where_clauses_cast = [];
        foreach ($text_cols as $col) {
            $where_clauses_cast[] = "CAST(`$col` AS CHAR) LIKE '%" . $mysqli->real_escape_string($search) . "%'";
        }
        $query_cast = "SELECT * FROM `$table` WHERE " . implode(" OR ", $where_clauses_cast) . " LIMIT 10";
        try {
            $search_res = $mysqli->query($query_cast);
            if ($search_res && $search_res->num_rows > 0) {
                echo "\nFound in table (cast): `$table` (" . $search_res->num_rows . " rows):\n";
                while ($row = $search_res->fetch_assoc()) {
                    print_r($row);
                }
            }
        } catch (Throwable $e2) {
            // skip
        }
    }
}
echo "\nSearch complete.\n";
?>
