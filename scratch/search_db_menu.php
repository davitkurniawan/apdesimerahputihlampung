<?php
$mysqli = new mysqli("db", "root", "password");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$search = "xosid-menu-label";
echo "Searching all databases and tables for: '$search'\n";

$dbs_res = $mysqli->query("SHOW DATABASES");
while ($db_row = $dbs_res->fetch_row()) {
    $db_name = $db_row[0];
    if (in_array($db_name, ['information_schema', 'mysql', 'performance_schema', 'sys'])) continue;
    
    $mysqli->select_db($db_name);
    
    $tables_res = $mysqli->query("SHOW TABLES");
    if (!$tables_res) continue;
    
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
        
        $query = "SELECT * FROM `$table` WHERE " . implode(" OR ", $where_clauses) . " LIMIT 5";
        try {
            $search_res = $mysqli->query($query);
            if ($search_res && $search_res->num_rows > 0) {
                echo "\nFound in Database: `$db_name` -> Table: `$table` (" . $search_res->num_rows . " rows):\n";
                while ($row = $search_res->fetch_assoc()) {
                    print_r($row);
                }
            }
        } catch (Throwable $e) {
            // skip
        }
    }
}
echo "\nSearch complete.\n";
