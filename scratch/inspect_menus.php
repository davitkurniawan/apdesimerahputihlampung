<?php
$databases = ['xosid_tenant_demo', 'xosid_tenant_bumidaya'];
$mysqli = new mysqli("db", "root", "password");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

foreach ($databases as $db) {
    echo "=== Database: $db ===\n";
    if (!$mysqli->select_db($db)) {
        echo "Database does not exist.\n";
        continue;
    }
    
    // Select top level active menus (where parent = 0 or parent IS NULL, and hidden = 0, and aktif = 1)
    $res = $mysqli->query("SELECT id, modul, urut, parent, hidden, aktif FROM setting_modul WHERE (parent = 0 OR parent IS NULL) AND hidden = 0 AND aktif = 1 ORDER BY urut ASC");
    if ($res) {
        echo "Top level active menus count: " . $res->num_rows . "\n";
        while ($row = $res->fetch_assoc()) {
            echo "ID: " . $row['id'] . " | Name: " . $row['modul'] . " | Urut: " . $row['urut'] . "\n";
        }
    } else {
        echo "Query failed: " . $mysqli->error . "\n";
    }
}
$mysqli->close();
?>
