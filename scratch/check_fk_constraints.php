<?php
require_once __DIR__ . '/../xosid_emergency_autoload.php';
$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

echo "=== FOREIGN KEYS REFERENCING tweb_penduduk ===\n";
$sql = "SELECT 
            TABLE_NAME, 
            COLUMN_NAME, 
            CONSTRAINT_NAME, 
            REFERENCED_TABLE_NAME, 
            REFERENCED_COLUMN_NAME 
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE 
            REFERENCED_TABLE_NAME = 'tweb_penduduk'";

$stmt = $pdo->query($sql);
$has_fk = false;
while ($row = $stmt->fetch()) {
    $has_fk = true;
    echo "Table: {$row['TABLE_NAME']} | Column: {$row['COLUMN_NAME']} | Constraint: {$row['CONSTRAINT_NAME']}\n";
}
if (!$has_fk) {
    echo "No explicit foreign keys referencing tweb_penduduk found in INFORMATION_SCHEMA.\n";
}

echo "\n=== FOREIGN KEYS ON tweb_penduduk ITS-SELF ===\n";
$sql = "SELECT 
            TABLE_NAME, 
            COLUMN_NAME, 
            CONSTRAINT_NAME, 
            REFERENCED_TABLE_NAME, 
            REFERENCED_COLUMN_NAME 
        FROM 
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE 
            TABLE_NAME = 'tweb_penduduk' AND REFERENCED_TABLE_NAME IS NOT NULL";

$stmt = $pdo->query($sql);
$has_fk_self = false;
while ($row = $stmt->fetch()) {
    $has_fk_self = true;
    echo "Column: {$row['COLUMN_NAME']} | References Table: {$row['REFERENCED_TABLE_NAME']} | Column: {$row['REFERENCED_COLUMN_NAME']}\n";
}
if (!$has_fk_self) {
    echo "No explicit foreign keys on tweb_penduduk table found.\n";
}
