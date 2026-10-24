<?php
header('Content-Type: text/plain');
exec('php -l /var/www/html/index.php 2>&1', $output, $return_var);
echo implode("\n", $output) . "\n";
echo "Exit Code: $return_var\n";
?>
