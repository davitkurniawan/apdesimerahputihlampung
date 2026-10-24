<?php
// scratch/read_last_queries.php
$lines = file('/var/www/html/storage/logs/xosid_laravel_queries.log');
$last_lines = array_slice($lines, -50);
foreach ($last_lines as $line) {
    echo $line;
}
