<?php
$content = file_get_contents('/var/www/html/debug_dbmanager.txt');
preg_match('/Config: (.*)/', $content, $matches);
if (isset($matches[1])) {
    $config = json_decode($matches[1], true);
    if (isset($config['database'])) {
        echo "Database config exists. Keys: " . implode(', ', array_keys($config['database'])) . "\n";
        if (isset($config['database']['connections'])) {
            echo "Connections: " . json_encode($config['database']['connections']) . "\n";
        } else {
            echo "Connections key is missing!\n";
        }
    } else {
        echo "Database key is missing entirely!\n";
    }
}
