<?php
$log = '/var/www/html/storage/logs/xosid_login_debug.log';
if (file_exists($log)) {
    $lines = file($log);
    $last_lines = array_slice($lines, -30);
    echo implode("", $last_lines);
} else {
    echo "Log file $log does not exist or is not readable.\n";
}
